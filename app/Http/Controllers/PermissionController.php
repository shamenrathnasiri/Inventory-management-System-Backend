<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    /**
     * Master list of all permission modules and their available actions.
     * This should match the frontend permissionModules.js
     */
    private array $permissionModules = [
        // Dashboard
        'dashboard' => ['view', 'edit'],

        // Pending Approvals
        'pendingApprovals' => ['view', 'edit', 'approve'],

        // Master Files
        'customer' => ['view', 'edit', 'delete', 'create'],
        'supplier' => ['view', 'edit', 'delete', 'create'],
        'center' => ['view', 'edit', 'delete', 'create'],
        'discountLevel' => ['view', 'edit', 'delete', 'create'],
        'productType' => ['view', 'edit', 'delete', 'create'],
        'product' => ['view', 'edit', 'delete', 'create'],
        'productList' => ['view', 'edit', 'delete', 'create'],

        // Inventory Control
        'invoices' => ['view', 'edit', 'delete', 'create', 'print', 'approve'],
        'salesOrder' => ['view', 'edit', 'delete', 'create', 'approve'],
        'salesReturn' => ['view', 'edit', 'delete', 'create', 'approve'],
        'grn' => ['view', 'edit', 'delete', 'create', 'approve'],
        'purchaseReturn' => ['view', 'edit', 'delete', 'create', 'approve'],
        'purchaseOrder' => ['view', 'edit', 'delete', 'create', 'approve'],

        // Stock Control
        'stockTransfer' => ['view', 'edit', 'delete', 'create', 'approve'],
        'stockVerification' => ['view', 'edit', 'delete', 'create', 'approve'],
        'inventory' => ['view', 'edit'],

        // Admin & User Management
        'userManagement' => ['view', 'edit', 'delete', 'create'],
        'permissionManagement' => ['view', 'edit'],
        'roleManagement' => ['view', 'edit', 'delete', 'create'],

        // Accounting
        'accounting' => ['view'],
        'accountingDashboard' => ['view'],
        'chartOfAccounts' => ['view', 'edit', 'create'],
        'accountList' => ['view', 'edit'],
        'supplierEnterBill' => ['view', 'edit', 'create'],
        'payment' => ['view', 'edit', 'create', 'approve'],
        'advancePayment' => ['view', 'edit', 'create'],
        'makeDeposit' => ['view', 'edit', 'create'],
        'receipt' => ['view', 'edit', 'create', 'print'],
        'journalEntry' => ['view', 'edit', 'create'],
        'pettyCash' => ['view', 'edit', 'create'],
        'cheque' => ['view', 'edit', 'create'],
        'bankReconciliation' => ['view', 'edit'],
        'transactions' => ['view', 'edit'],
        'transactionsList' => ['view'],

        // Reports
        'ledger' => ['view', 'export'],
        'trialBalance' => ['view', 'export'],
        'incomeStatement' => ['view', 'export'],
        'balanceSheet' => ['view', 'export'],
        'cashFlowStatement' => ['view', 'export'],
        'financeReports' => ['view', 'export'],
        'accountingReports' => ['view', 'export'],

        // Settings
        'accountingSettings' => ['view', 'edit'],
        'usersAndRoles' => ['view', 'edit'],
    ];

    /**
     * Default permissions based on role.
     */
    private function getDefaultPermissionsForRole(string $role): array
    {
        $permissions = [];

        switch (strtolower($role)) {
            case 'admin':
                // Admin gets all permissions
                foreach ($this->permissionModules as $module => $actions) {
                    $permissions[$module] = [];
                    foreach ($actions as $action) {
                        $permissions[$module][$action] = true;
                    }
                }
                break;

            case 'manager':
                // Manager gets most permissions except admin-specific ones
                $managerModules = [
                    'dashboard', 'pendingApprovals', 'customer', 'supplier', 'center',
                    'discountLevel', 'productType', 'product', 'productList',
                    'invoices', 'salesOrder', 'salesReturn', 'grn', 'purchaseReturn',
                    'purchaseOrder', 'stockTransfer', 'stockVerification', 'inventory',
                    'accounting', 'accountingDashboard', 'chartOfAccounts', 'accountList',
                    'supplierEnterBill', 'payment', 'advancePayment', 'makeDeposit',
                    'receipt', 'journalEntry', 'pettyCash', 'cheque', 'bankReconciliation',
                    'transactions', 'transactionsList', 'ledger', 'trialBalance',
                    'incomeStatement', 'balanceSheet', 'cashFlowStatement',
                    'financeReports', 'accountingReports'
                ];
                foreach ($managerModules as $module) {
                    if (isset($this->permissionModules[$module])) {
                        $permissions[$module] = [];
                        foreach ($this->permissionModules[$module] as $action) {
                            $permissions[$module][$action] = true;
                        }
                    }
                }
                break;

            case 'staff':
            case 'user':
            default:
                // Staff/User gets basic view permissions
                $staffModules = [
                    'dashboard', 'customer', 'supplier', 'product', 'productList',
                    'invoices', 'salesOrder', 'grn', 'inventory'
                ];
                foreach ($staffModules as $module) {
                    if (isset($this->permissionModules[$module])) {
                        $permissions[$module] = ['view' => true];
                    }
                }
                break;
        }

        return $permissions;
    }

    /**
     * Get all available permissions/modules.
     */
    public function getAllPermissions(): JsonResponse
    {
        return response()->json([
            'modules' => $this->permissionModules,
        ]);
    }

    /**
     * Get all users with their roles (for user list).
     */
    public function getAllUsers(): JsonResponse
    {
        $users = User::select('id', 'name', 'email', 'role', 'created_at')
            ->orderBy('name')
            ->get();

        return response()->json($users);
    }

    /**
     * Get permissions for a specific user.
     */
    public function getUserPermissions(int $userId): JsonResponse
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Get stored permissions for this user
        $storedPermissions = UserPermission::where('user_id', $userId)
            ->where('granted', true)
            ->get()
            ->groupBy('module')
            ->map(function ($permissions) {
                return $permissions->pluck('granted', 'action')->toArray();
            })
            ->toArray();

        // If user has no custom permissions, return role defaults
        if (empty($storedPermissions)) {
            $storedPermissions = $this->getDefaultPermissionsForRole($user->role ?? 'user');
        }

        return response()->json([
            'user_id' => $userId,
            'permissions' => $storedPermissions,
        ]);
    }

    /**
     * Update permissions for a specific user.
     */
    public function updateUserPermissions(Request $request, int $userId): JsonResponse
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([
            'permissions' => 'required|array',
        ]);

        $permissions = $request->input('permissions');

        try {
            DB::beginTransaction();

            // Delete existing permissions for this user
            UserPermission::where('user_id', $userId)->delete();

            // Insert new permissions
            $permissionsToInsert = [];
            $now = now();

            foreach ($permissions as $module => $actions) {
                // Validate module exists
                if (!isset($this->permissionModules[$module])) {
                    continue;
                }

                foreach ($actions as $action => $granted) {
                    // Validate action exists for this module
                    if (!in_array($action, $this->permissionModules[$module])) {
                        continue;
                    }

                    if ($granted) {
                        $permissionsToInsert[] = [
                            'user_id' => $userId,
                            'module' => $module,
                            'action' => $action,
                            'granted' => true,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }
            }

            if (!empty($permissionsToInsert)) {
                UserPermission::insert($permissionsToInsert);
            }

            DB::commit();

            return response()->json([
                'message' => 'Permissions updated successfully',
                'user_id' => $userId,
                'permissions' => $permissions,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update permissions',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get current authenticated user's permissions.
     */
    public function getMyPermissions(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Get stored permissions for this user
        $storedPermissions = UserPermission::where('user_id', $user->id)
            ->where('granted', true)
            ->get()
            ->groupBy('module')
            ->map(function ($permissions) {
                return $permissions->pluck('granted', 'action')->toArray();
            })
            ->toArray();

        // If user has no custom permissions, return role defaults
        if (empty($storedPermissions)) {
            $storedPermissions = $this->getDefaultPermissionsForRole($user->role ?? 'user');
        }

        return response()->json([
            'user_id' => $user->id,
            'role' => $user->role,
            'permissions' => $storedPermissions,
        ]);
    }

    /**
     * Check if user has a specific permission.
     */
    public function checkPermission(Request $request): JsonResponse
    {
        $request->validate([
            'module' => 'required|string',
            'action' => 'required|string',
        ]);

        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $module = $request->input('module');
        $action = $request->input('action');

        // Check if user has the specific permission
        $hasPermission = UserPermission::where('user_id', $user->id)
            ->where('module', $module)
            ->where('action', $action)
            ->where('granted', true)
            ->exists();

        // If no custom permission found, check role defaults
        if (!$hasPermission) {
            $defaultPermissions = $this->getDefaultPermissionsForRole($user->role ?? 'user');
            $hasPermission = isset($defaultPermissions[$module][$action]) && $defaultPermissions[$module][$action];
        }

        return response()->json([
            'has_permission' => $hasPermission,
            'module' => $module,
            'action' => $action,
        ]);
    }

    /**
     * Get all users with their permission counts (for admin overview).
     */
    public function getAllUsersWithPermissions(): JsonResponse
    {
        $users = User::select('id', 'name', 'email', 'role', 'created_at')
            ->withCount('permissions')
            ->orderBy('name')
            ->get();

        return response()->json($users);
    }
}
