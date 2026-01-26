<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserPermission;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Default permissions based on role (fallback when no custom permissions set).
     */
    private function getDefaultPermissionsForRole(string $role): array
    {
        $permissions = [];

        switch (strtolower($role)) {
            case 'admin':
                // Admin has all permissions by default
                return ['*' => ['*' => true]];

            case 'manager':
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
                    $permissions[$module] = ['*' => true];
                }
                break;

            case 'staff':
            case 'user':
            default:
                $staffModules = [
                    'dashboard', 'customer', 'supplier', 'product', 'productList',
                    'invoices', 'salesOrder', 'grn', 'inventory'
                ];
                foreach ($staffModules as $module) {
                    $permissions[$module] = ['view' => true];
                }
                break;
        }

        return $permissions;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $module  The permission module to check
     * @param  string  $action  The permission action to check
     */
    public function handle(Request $request, Closure $next, string $module, string $action): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized',
                'error' => 'Authentication required'
            ], 401);
        }

        // Check if user has the specific permission in database
        $hasPermission = UserPermission::where('user_id', $user->id)
            ->where('module', $module)
            ->where('action', $action)
            ->where('granted', true)
            ->exists();

        // If no custom permission found, check role defaults
        if (!$hasPermission) {
            $defaultPermissions = $this->getDefaultPermissionsForRole($user->role ?? 'user');

            // Check for wildcard permissions (admin)
            if (isset($defaultPermissions['*']['*'])) {
                $hasPermission = true;
            }
            // Check for module wildcard
            elseif (isset($defaultPermissions[$module]['*'])) {
                $hasPermission = true;
            }
            // Check for specific permission
            elseif (isset($defaultPermissions[$module][$action])) {
                $hasPermission = $defaultPermissions[$module][$action];
            }
        }

        if (!$hasPermission) {
            return response()->json([
                'message' => 'Forbidden',
                'error' => "You don't have permission to perform this action",
                'required_permission' => [
                    'module' => $module,
                    'action' => $action
                ]
            ], 403);
        }

        return $next($request);
    }
}
