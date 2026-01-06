<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerCategoryController;
use App\Http\Controllers\CustomerTypeController;
use App\Http\Controllers\DiscountLevelController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CentersController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InventoryProductController;
use App\Http\Controllers\InventoryStockController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->noContent();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::apiResource('users', UserController::class);

// Customer routes
Route::apiResource('customers', CustomerController::class);
Route::get('/customer/email/{email}', [CustomerController::class, 'getByEmail']);
Route::get('/customer/type/{typeId}', [CustomerController::class, 'getByType']);
Route::get('/customer/name/{name}', [CustomerController::class, 'getByName']);

// Customer Types & Categories
Route::get('customer-categories', [CustomerCategoryController::class, 'index']);
Route::post('customer-categories', [CustomerCategoryController::class, 'store']);
Route::get('customer-types', [CustomerTypeController::class, 'index']);
Route::post('customer-types', [CustomerTypeController::class, 'store']);

// Supplier routes
Route::apiResource('suppliers', SupplierController::class);

// Discount levels
Route::get('/discount-levels', [DiscountLevelController::class, 'index']);
Route::get('/discount-levels/{id}', [DiscountLevelController::class, 'show']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/discount-levels', [DiscountLevelController::class, 'store']);
    Route::put('/discount-levels/{id}', [DiscountLevelController::class, 'update']);
    Route::delete('/discount-levels/{id}', [DiscountLevelController::class, 'destroy']);
});

// Product Type routes
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('product-types', ProductTypeController::class);
    Route::post('product-types/{id}/restore', [ProductTypeController::class, 'restore']);
    Route::post('product-types/{id}/status', [ProductTypeController::class, 'setStatus']);
    Route::get('product-types/trashed/list', [ProductTypeController::class, 'getTrashed']);
    Route::get('product-types/stats/overview', [ProductTypeController::class, 'getStats']);
    Route::delete('product-types/{id}/force', [ProductTypeController::class, 'forceDestroy']);

    // Product routes
    Route::apiResource('products', ProductController::class);
    Route::get('products/inventory/details', [ProductController::class, 'inventoryDetails']);

    // Centers routes
    Route::apiResource('centers', CentersController::class);

    // Inventory routes
    Route::apiResource('inventories', InventoryController::class);
    Route::get('/inventory-pending', [InventoryController::class, 'getPending']);
    Route::post('/inventory-approved', [InventoryController::class, 'getApproved']);

    // GRN routes
    Route::post('/grn', [InventoryController::class, 'store']);
    Route::get('/grn/next', [InventoryController::class, 'nextGrn']);
    Route::get('/grn', [InventoryController::class, 'listGrns']);

    // Invoice routes
    Route::get('/invoices/next', [InventoryController::class, 'nextInv']);
    Route::get('/invoices', [InventoryController::class, 'listInvoices']);
    Route::post('/invoices', [InventoryController::class, 'storeInvoice']);

    // Stock Transfer routes
    Route::get('/stock-transfer/next', [InventoryController::class, 'nextStockTransfer']);
    Route::post('/stock-transfer', [InventoryController::class, 'storeStockTransfer']);

    // Sales Order routes
    Route::post('/salesOrder', [InventoryController::class, 'storeSalesOrder']);
    Route::get('/salesOrder/next', [InventoryController::class, 'nextSalesOrder']);
    Route::get('/salesOrder', [InventoryController::class, 'listSalesOrders']);

    // Sales Return routes
    Route::get('/salesreturn/next', [InventoryController::class, 'nextSalesReturn']);
    Route::post('/salesreturn', [InventoryController::class, 'storeSalesReturn']);
    Route::get('/salesreturn', [InventoryController::class, 'listSalesReturns']);

    // Purchase Order routes
    Route::get('/purchaseOrder/next', [InventoryController::class, 'nextPurchaseOrder']);
    Route::post('/purchaseOrder', [InventoryController::class, 'storePurchaseOrder']);
    Route::get('/purchaseOrder', [InventoryController::class, 'listPurchaseOrders']);

    // Purchase Return routes
    Route::get('/purchaseReturn/next', [InventoryController::class, 'nextPurchaseReturn']);
    Route::post('/purchaseReturn', [InventoryController::class, 'storePurchaseReturn']);

    // Stock Verification routes
    Route::get('/stockVerification/next', [InventoryController::class, 'nextStockVerification']);
    Route::post('/stockVerification', [InventoryController::class, 'storeStockVerification']);

    // Inventory Products routes
    Route::apiResource('inventory-products', InventoryProductController::class);

    // Inventory Stock routes
    Route::get('/inventory-stocks/all', [InventoryStockController::class, 'all']);
});
