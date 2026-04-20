<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\ProductUsage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function dashboard()
    {
        // Low stock products
        $lowStockProducts = Product::where('track_inventory', true)
            ->whereRaw('current_stock <= min_stock_level')
            ->with('supplier')
            ->orderBy('current_stock')
            ->take(10)
            ->get();

        // Out of stock products
        $outOfStockProducts = Product::where('track_inventory', true)
            ->where('current_stock', '<=', 0)
            ->with('supplier')
            ->get();

        // Inventory value calculations
        $totalInventoryValue = Product::where('track_inventory', true)
            ->sum(DB::raw('current_stock * cost_price'));

        $totalRetailValue = Product::where('track_inventory', true)
            ->where('product_type', 'retail')
            ->sum(DB::raw('current_stock * selling_price'));

        // Recent purchases
        $recentPurchases = Purchase::with('supplier')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Pending deliveries
        $pendingDeliveries = Purchase::whereIn('status', ['ordered', 'partially_received'])
            ->with('supplier')
            ->orderBy('expected_delivery_date')
            ->take(5)
            ->get();

        // Top used products this month
        $topUsedProducts = ProductUsage::select('product_id', DB::raw('SUM(quantity_used) as total_used'))
            ->whereMonth('usage_date', now()->month)
            ->whereYear('usage_date', now()->year)
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_used', 'desc')
            ->take(5)
            ->get();

        // Inventory turnover (simplified)
        $totalProducts = Product::where('track_inventory', true)->count();
        $activeSuppliers = Supplier::where('is_active', true)->count();

        return view('inventory.dashboard', compact(
            'lowStockProducts',
            'outOfStockProducts',
            'totalInventoryValue',
            'totalRetailValue',
            'recentPurchases',
            'pendingDeliveries',
            'topUsedProducts',
            'totalProducts',
            'activeSuppliers'
        ));
    }

    public function lowStockAlerts()
    {
        $lowStockProducts = Product::where('track_inventory', true)
            ->whereRaw('current_stock <= min_stock_level')
            ->with(['supplier', 'category'])
            ->orderByRaw('(min_stock_level - current_stock) DESC')
            ->paginate(20);

        return view('inventory.low-stock', compact('lowStockProducts'));
    }

    public function stockReport(Request $request)
    {
        $query = Product::where('track_inventory', true)->with(['supplier', 'category']);

        // Filter by product type
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('product_type', $request->type);
        }

        // Filter by stock status
        if ($request->has('status') && $request->status !== 'all') {
            switch ($request->status) {
                case 'low_stock':
                    $query->whereRaw('current_stock <= min_stock_level');
                    break;
                case 'out_of_stock':
                    $query->where('current_stock', '<=', 0);
                    break;
                case 'in_stock':
                    $query->whereRaw('current_stock > min_stock_level');
                    break;
            }
        }

        $products = $query->orderBy('name')->paginate(25);

        $totalValue = $query->sum(DB::raw('current_stock * cost_price'));
        $totalItems = $query->sum('current_stock');

        return view('inventory.stock-report', compact('products', 'totalValue', 'totalItems'));
    }

    public function usageReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        $usageData = ProductUsage::whereBetween('usage_date', [$startDate, $endDate])
            ->with(['product', 'service'])
            ->select('product_id', 'service_id', DB::raw('SUM(quantity_used) as total_used'))
            ->groupBy('product_id', 'service_id')
            ->orderBy('total_used', 'desc')
            ->get();

        return view('inventory.usage-report', compact('usageData', 'startDate', 'endDate'));
    }
}
