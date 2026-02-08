<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\product;
use App\Models\centers;
use App\Models\inventory_product;
use App\Models\inventory_stock;
use App\Models\CustomerType;
use App\Models\CustomerCategory;
use App\Models\DiscountLevel;
use App\Models\ProductType;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * GRN Report - Get all GRN records with related data
     * GET /api/reports/grn
     */
    public function grnReport(Request $request)
    {
        $query = Inventory::with([
            'creator:id,name,email',
            'approver:id,name,email',
            'supplier:id,supplier_name,phone_number,email,address1,address2,credit_value,credit_period',
            'center:id,name,description',
            'items.product:id,name,code,barcode,cost,mrp,min_price,description',
            'items.product.productType:id,type,description',
            'items.product.discountLevel:id,name,value,description',
            'items.creator:id,name',
            'payments'
        ])
        ->where('voucherNumber', 'LIKE', 'GRN%')
        ->orderByDesc('created_at');

        // Apply filters
        $query = $this->applyCommonFilters($query, $request);

        // Apply supplier filter
        if ($request->has('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $data = $query->get();

        return response()->json([
            'success' => true,
            'data' => $data,
            'total' => $data->count(),
            'summary' => [
                'total_amount' => $data->sum('amount'),
                'total_paid' => $data->sum('paid_value'),
                'total_discount' => $data->sum('discountValue'),
            ]
        ]);
    }

    /**
     * Invoice Report - Get all Invoice records with related data
     * GET /api/reports/invoice
     */
    public function invoiceReport(Request $request)
    {
        $query = Inventory::with([
            'creator:id,name,email',
            'approver:id,name,email',
            'customer:id,name,email,phone,address,city,customer_type_id,customer_category_id',
            'customer.customerType:id,name,description',
            'customer.customerCategory:id,name,description',
            'center:id,name,description',
            'items.product:id,name,code,barcode,cost,mrp,min_price,description',
            'items.product.productType:id,type,description',
            'items.product.discountLevel:id,name,value,description',
            'items.creator:id,name',
            'payments'
        ])
        ->where('voucherNumber', 'LIKE', 'INV%')
        ->orderByDesc('created_at');

        // Apply filters
        $query = $this->applyCommonFilters($query, $request);

        // Apply customer filter
        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $data = $query->get();

        return response()->json([
            'success' => true,
            'data' => $data,
            'total' => $data->count(),
            'summary' => [
                'total_amount' => $data->sum('amount'),
                'total_paid' => $data->sum('paid_value'),
                'total_discount' => $data->sum('discountValue'),
            ]
        ]);
    }

    /**
     * Sales Order Report - Get all Sales Order records with related data
     * GET /api/reports/sales-order
     */
    public function salesOrderReport(Request $request)
    {
        $query = Inventory::with([
            'creator:id,name,email',
            'approver:id,name,email',
            'customer:id,name,email,phone,address,city,customer_type_id,customer_category_id',
            'customer.customerType:id,name,description',
            'customer.customerCategory:id,name,description',
            'center:id,name,description',
            'items.product:id,name,code,barcode,cost,mrp,min_price,description',
            'items.product.productType:id,type,description',
            'items.product.discountLevel:id,name,value,description',
            'items.creator:id,name',
            'payments'
        ])
        ->where('voucherNumber', 'LIKE', 'SO%')
        ->orderByDesc('created_at');

        // Apply filters
        $query = $this->applyCommonFilters($query, $request);

        // Apply customer filter
        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $data = $query->get();

        return response()->json([
            'success' => true,
            'data' => $data,
            'total' => $data->count(),
            'summary' => [
                'total_amount' => $data->sum('amount'),
                'total_paid' => $data->sum('paid_value'),
                'total_discount' => $data->sum('discountValue'),
            ]
        ]);
    }

    /**
     * Sales Return Report - Get all Sales Return records with related data
     * GET /api/reports/sales-return
     */
    public function salesReturnReport(Request $request)
    {
        $query = Inventory::with([
            'creator:id,name,email',
            'approver:id,name,email',
            'customer:id,name,email,phone,address,city,customer_type_id,customer_category_id',
            'customer.customerType:id,name,description',
            'customer.customerCategory:id,name,description',
            'center:id,name,description',
            'items.product:id,name,code,barcode,cost,mrp,min_price,description',
            'items.product.productType:id,type,description',
            'items.product.discountLevel:id,name,value,description',
            'items.creator:id,name',
            'payments'
        ])
        ->where('voucherNumber', 'LIKE', 'SR%')
        ->orderByDesc('created_at');

        // Apply filters
        $query = $this->applyCommonFilters($query, $request);

        // Apply customer filter
        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $data = $query->get();

        return response()->json([
            'success' => true,
            'data' => $data,
            'total' => $data->count(),
            'summary' => [
                'total_amount' => $data->sum('amount'),
                'total_paid' => $data->sum('paid_value'),
                'total_discount' => $data->sum('discountValue'),
            ]
        ]);
    }

    /**
     * Purchase Order Report - Get all Purchase Order records with related data
     * GET /api/reports/purchase-order
     */
    public function purchaseOrderReport(Request $request)
    {
        $query = Inventory::with([
            'creator:id,name,email',
            'approver:id,name,email',
            'supplier:id,supplier_name,phone_number,email,address1,address2,credit_value,credit_period',
            'center:id,name,description',
            'items.product:id,name,code,barcode,cost,mrp,min_price,description',
            'items.product.productType:id,type,description',
            'items.product.discountLevel:id,name,value,description',
            'items.creator:id,name',
            'payments'
        ])
        ->where('voucherNumber', 'LIKE', 'PO%')
        ->orderByDesc('created_at');

        // Apply filters
        $query = $this->applyCommonFilters($query, $request);

        // Apply supplier filter
        if ($request->has('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $data = $query->get();

        return response()->json([
            'success' => true,
            'data' => $data,
            'total' => $data->count(),
            'summary' => [
                'total_amount' => $data->sum('amount'),
                'total_paid' => $data->sum('paid_value'),
                'total_discount' => $data->sum('discountValue'),
            ]
        ]);
    }

    /**
     * Purchase Return Report - Get all Purchase Return records with related data
     * GET /api/reports/purchase-return
     */
    public function purchaseReturnReport(Request $request)
    {
        $query = Inventory::with([
            'creator:id,name,email',
            'approver:id,name,email',
            'supplier:id,supplier_name,phone_number,email,address1,address2,credit_value,credit_period',
            'center:id,name,description',
            'items.product:id,name,code,barcode,cost,mrp,min_price,description',
            'items.product.productType:id,type,description',
            'items.product.discountLevel:id,name,value,description',
            'items.creator:id,name',
            'payments'
        ])
        ->where('voucherNumber', 'LIKE', 'PR%')
        ->orderByDesc('created_at');

        // Apply filters
        $query = $this->applyCommonFilters($query, $request);

        // Apply supplier filter
        if ($request->has('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $data = $query->get();

        return response()->json([
            'success' => true,
            'data' => $data,
            'total' => $data->count(),
            'summary' => [
                'total_amount' => $data->sum('amount'),
                'total_paid' => $data->sum('paid_value'),
                'total_discount' => $data->sum('discountValue'),
            ]
        ]);
    }

    /**
     * Stock Transfer Report - Get all Stock Transfer records with related data
     * GET /api/reports/stock-transfer
     */
    public function stockTransferReport(Request $request)
    {
        $query = Inventory::with([
            'creator:id,name,email',
            'approver:id,name,email',
            'center:id,name,description',
            'items.product:id,name,code,barcode,cost,mrp,min_price,description',
            'items.product.productType:id,type,description',
            'items.product.discountLevel:id,name,value,description',
            'items.creator:id,name',
        ])
        ->where('voucherNumber', 'LIKE', 'ST-%')
        ->orderByDesc('created_at');

        // Apply filters
        $query = $this->applyCommonFilters($query, $request);

        // Apply from_center filter
        if ($request->has('from_center')) {
            $query->where('from_center', $request->from_center);
        }

        // Apply to_center filter
        if ($request->has('to_center')) {
            $query->where('to_center', $request->to_center);
        }

        $data = $query->get();

        // Load from_center and to_center relationships manually
        $data->each(function ($item) {
            if ($item->from_center) {
                $item->fromCenter = centers::find($item->from_center);
            }
            if ($item->to_center) {
                $item->toCenter = centers::find($item->to_center);
            }
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'total' => $data->count(),
            'summary' => [
                'total_amount' => $data->sum('amount'),
                'total_items' => $data->sum(function ($transfer) {
                    return $transfer->items->sum('quantity');
                }),
            ]
        ]);
    }

    /**
     * Stock Verification Report - Get all Stock Verification records with related data
     * GET /api/reports/stock-verification
     */
    public function stockVerificationReport(Request $request)
    {
        $query = Inventory::with([
            'creator:id,name,email',
            'approver:id,name,email',
            'center:id,name,description',
            'items.product:id,name,code,barcode,cost,mrp,min_price,description',
            'items.product.productType:id,type,description',
            'items.product.discountLevel:id,name,value,description',
            'items.creator:id,name',
        ])
        ->where('voucherNumber', 'LIKE', 'STV-%')
        ->orderByDesc('created_at');

        // Apply filters
        $query = $this->applyCommonFilters($query, $request);

        $data = $query->get();

        return response()->json([
            'success' => true,
            'data' => $data,
            'total' => $data->count(),
            'summary' => [
                'total_verifications' => $data->count(),
                'completed' => $data->where('status', 'completed')->count(),
                'pending' => $data->where('status', 'pending')->count(),
            ]
        ]);
    }

    /**
     * Customer Report - Get all Customers with related data
     * GET /api/reports/customer
     */
    public function customerReport(Request $request)
    {
        $query = Customer::with([
            'customerType:id,name,description',
            'customerCategory:id,name,description',
        ])
        ->withCount([
            'inventories as total_invoices' => function ($q) {
                $q->where('voucherNumber', 'LIKE', 'INV%');
            },
            'inventories as total_sales_orders' => function ($q) {
                $q->where('voucherNumber', 'LIKE', 'SO%');
            },
            'inventories as total_sales_returns' => function ($q) {
                $q->where('voucherNumber', 'LIKE', 'SR%');
            },
        ])
        ->orderByDesc('created_at');

        // Apply filters
        if ($request->has('customer_type_id')) {
            $query->where('customer_type_id', $request->customer_type_id);
        }

        if ($request->has('customer_category_id')) {
            $query->where('customer_category_id', $request->customer_category_id);
        }

        if ($request->has('city')) {
            $query->where('city', 'LIKE', '%' . $request->city . '%');
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%')
                  ->orWhere('phone', 'LIKE', '%' . $search . '%');
            });
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $data = $query->get();

        // Calculate totals for each customer
        $data->each(function ($customer) {
            $customer->total_purchase_amount = Inventory::where('customer_id', $customer->id)
                ->whereIn('status', ['completed'])
                ->sum('amount');
            $customer->total_paid_amount = Inventory::where('customer_id', $customer->id)
                ->whereIn('status', ['completed'])
                ->sum('paid_value');
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'total' => $data->count(),
            'summary' => [
                'total_customers' => $data->count(),
                'total_purchase_amount' => $data->sum('total_purchase_amount'),
                'total_paid_amount' => $data->sum('total_paid_amount'),
            ]
        ]);
    }

    /**
     * Supplier Report - Get all Suppliers with related data
     * GET /api/reports/supplier
     */
    public function supplierReport(Request $request)
    {
        $query = Supplier::withCount([
            'inventories as total_grns' => function ($q) {
                $q->where('voucherNumber', 'LIKE', 'GRN%');
            },
            'inventories as total_purchase_orders' => function ($q) {
                $q->where('voucherNumber', 'LIKE', 'PO%');
            },
            'inventories as total_purchase_returns' => function ($q) {
                $q->where('voucherNumber', 'LIKE', 'PR%');
            },
        ])
        ->orderByDesc('created_at');

        // Apply filters
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('supplier_name', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%')
                  ->orWhere('phone_number', 'LIKE', '%' . $search . '%');
            });
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $data = $query->get();

        // Calculate totals for each supplier
        $data->each(function ($supplier) {
            $supplier->total_supply_amount = Inventory::where('supplier_id', $supplier->id)
                ->whereIn('status', ['completed'])
                ->sum('amount');
            $supplier->total_paid_amount = Inventory::where('supplier_id', $supplier->id)
                ->whereIn('status', ['completed'])
                ->sum('paid_value');
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'total' => $data->count(),
            'summary' => [
                'total_suppliers' => $data->count(),
                'total_supply_amount' => $data->sum('total_supply_amount'),
                'total_paid_amount' => $data->sum('total_paid_amount'),
                'total_credit_value' => $data->sum('credit_value'),
            ]
        ]);
    }

    /**
     * Product Report - Get all Products with related data
     * GET /api/reports/product
     */
    public function productReport(Request $request)
    {
        $query = product::with([
            'productType:id,type,description',
            'discountLevel:id,name,value,description',
            'creator:id,name,email',
            'inventoryStocks.center:id,name,description',
        ])
        ->withCount([
            'inventoryStocks as total_stock_entries',
        ])
        ->orderByDesc('created_at');

        // Apply filters
        if ($request->has('product_type_id')) {
            $query->where('product_type_id', $request->product_type_id);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('code', 'LIKE', '%' . $search . '%')
                  ->orWhere('barcode', 'LIKE', '%' . $search . '%')
                  ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $data = $query->get();

        // Calculate stock quantities and sales data
        $data->each(function ($product) {
            $product->total_stock_quantity = inventory_stock::where('product_id', $product->id)->sum('quantity');
            $product->total_sold_quantity = inventory_product::whereHas('inventory', function ($q) {
                $q->where('voucherNumber', 'LIKE', 'INV%')
                  ->orWhere('voucherNumber', 'LIKE', 'SO%');
            })->where('product_id', $product->id)->sum('quantity');
            $product->total_sales_amount = inventory_product::whereHas('inventory', function ($q) {
                $q->where('voucherNumber', 'LIKE', 'INV%')
                  ->orWhere('voucherNumber', 'LIKE', 'SO%');
            })->where('product_id', $product->id)->sum('amount');
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'total' => $data->count(),
            'summary' => [
                'total_products' => $data->count(),
                'active_products' => $data->where('is_active', true)->count(),
                'total_stock_quantity' => $data->sum('total_stock_quantity'),
                'total_sales_amount' => $data->sum('total_sales_amount'),
            ]
        ]);
    }

    /**
     * Center Report - Get all Centers with related data
     * GET /api/reports/center
     */
    public function centerReport(Request $request)
    {
        $query = centers::with([
            'creator:id,name,email',
        ])
        ->withCount([
            'inventories as total_grns' => function ($q) {
                $q->where('voucherNumber', 'LIKE', 'GRN%');
            },
            'inventories as total_invoices' => function ($q) {
                $q->where('voucherNumber', 'LIKE', 'INV%');
            },
            'inventories as total_sales_orders' => function ($q) {
                $q->where('voucherNumber', 'LIKE', 'SO%');
            },
        ])
        ->orderByDesc('created_at');

        // Apply filters
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $data = $query->get();

        // Calculate stock and transaction data for each center
        $data->each(function ($center) {
            $center->total_stock_value = inventory_stock::where('center_id', $center->id)
                ->join('products', 'inventory_stocks.product_id', '=', 'products.id')
                ->selectRaw('SUM(inventory_stocks.quantity * products.cost) as total')
                ->value('total') ?? 0;

            $center->total_stock_quantity = inventory_stock::where('center_id', $center->id)->sum('quantity');

            $center->total_sales_amount = Inventory::where('center_id', $center->id)
                ->where(function ($q) {
                    $q->where('voucherNumber', 'LIKE', 'INV%')
                      ->orWhere('voucherNumber', 'LIKE', 'SO%');
                })
                ->sum('amount');

            $center->total_purchase_amount = Inventory::where('center_id', $center->id)
                ->where('voucherNumber', 'LIKE', 'GRN%')
                ->sum('amount');
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'total' => $data->count(),
            'summary' => [
                'total_centers' => $data->count(),
                'total_stock_value' => $data->sum('total_stock_value'),
                'total_stock_quantity' => $data->sum('total_stock_quantity'),
                'total_sales_amount' => $data->sum('total_sales_amount'),
                'total_purchase_amount' => $data->sum('total_purchase_amount'),
            ]
        ]);
    }

    /**
     * Export Report to PDF
     * GET /api/reports/{type}/export/pdf
     */
    public function exportToPdf(Request $request, $type)
    {
        // Check if barryvdh/laravel-dompdf package is installed
        if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            return response()->json([
                'success' => false,
                'message' => 'PDF export is not available. Please install barryvdh/laravel-dompdf package.',
            ], 501);
        }

        // Get report data based on type
        $data = $this->getReportData($type, $request);

        // Generate PDF using a PDF library (requires barryvdh/laravel-dompdf package)
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.' . $type, [
            'data' => $data['data'],
            'summary' => $data['summary'] ?? [],
            'title' => ucfirst(str_replace('-', ' ', $type)) . ' Report',
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        return $pdf->download($type . '_report_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export Report to Excel
     * GET /api/reports/{type}/export/excel
     */
    public function exportToExcel(Request $request, $type)
    {
        // Check if maatwebsite/excel package is properly configured
        if (!class_exists('Maatwebsite\Excel\Facades\Excel')) {
            return response()->json([
                'success' => false,
                'message' => 'Excel export is not available. Please install maatwebsite/excel package.',
            ], 501);
        }

        // Get report data based on type
        $data = $this->getReportData($type, $request);

        // Return as JSON for now - Excel export requires creating export classes
        // To enable Excel export, create App\Exports\ReportExport class
        return response()->json([
            'success' => true,
            'data' => $data['data'],
            'summary' => $data['summary'] ?? [],
            'message' => 'Data retrieved successfully. To enable Excel download, create the ReportExport class.',
        ]);
    }

    /**
     * Helper method to get report data based on type
     */
    private function getReportData($type, Request $request)
    {
        switch ($type) {
            case 'grn':
                return $this->grnReport($request)->getData(true);
            case 'invoice':
                return $this->invoiceReport($request)->getData(true);
            case 'sales-order':
                return $this->salesOrderReport($request)->getData(true);
            case 'sales-return':
                return $this->salesReturnReport($request)->getData(true);
            case 'purchase-order':
                return $this->purchaseOrderReport($request)->getData(true);
            case 'purchase-return':
                return $this->purchaseReturnReport($request)->getData(true);
            case 'stock-transfer':
                return $this->stockTransferReport($request)->getData(true);
            case 'stock-verification':
                return $this->stockVerificationReport($request)->getData(true);
            case 'customer':
                return $this->customerReport($request)->getData(true);
            case 'supplier':
                return $this->supplierReport($request)->getData(true);
            case 'product':
                return $this->productReport($request)->getData(true);
            case 'center':
                return $this->centerReport($request)->getData(true);
            default:
                return ['data' => [], 'summary' => []];
        }
    }

    /**
     * Helper method to apply common filters to inventory queries
     */
    private function applyCommonFilters($query, Request $request)
    {
        // Date range filter
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // Status filter
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Center filter
        if ($request->has('center_id')) {
            $query->where('center_id', $request->center_id);
        }

        // Created by filter
        if ($request->has('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        // Search by voucher number
        if ($request->has('voucher_number') || $request->has('voucherNumber')) {
            $voucherNumber = $request->voucher_number ?? $request->voucherNumber;
            $query->where('voucherNumber', 'LIKE', '%' . $voucherNumber . '%');
        }

        // Search keyword
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('voucherNumber', 'LIKE', '%' . $search . '%')
                  ->orWhere('referNumber', 'LIKE', '%' . $search . '%');
            });
        }

        return $query;
    }
}
