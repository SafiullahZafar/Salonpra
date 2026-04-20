<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\ProductUsage;
use App\Models\Service;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $last30Days = Carbon::today()->subDays(29)->startOfDay();

        $totalSalesToday = Invoice::whereDate('created_at', $today)->sum('payable_amount');
        $transactionCountToday = Invoice::whereDate('created_at', $today)->count();
        $yesterdaySales = Invoice::whereDate('created_at', $yesterday)->sum('payable_amount');
        $salesTrend = $yesterdaySales > 0
            ? round((($totalSalesToday - $yesterdaySales) / $yesterdaySales) * 100, 1)
            : null;

        $serviceRevenue = InvoiceItem::where('itemizable_type', Service::class)->sum('subtotal');
        $productRevenue = InvoiceItem::where('itemizable_type', Product::class)->sum('subtotal');
        $itemRevenueTotal = $serviceRevenue + $productRevenue;

        $serviceRevenueShare = $itemRevenueTotal > 0
            ? round(($serviceRevenue / $itemRevenueTotal) * 100, 1)
            : 0;
        $productRevenueShare = $itemRevenueTotal > 0
            ? round(($productRevenue / $itemRevenueTotal) * 100, 1)
            : 0;

        $topServicesRaw = InvoiceItem::selectRaw('itemizable_id, SUM(quantity) as total_quantity, SUM(subtotal) as total_revenue')
            ->where('itemizable_type', Service::class)
            ->groupBy('itemizable_id')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        $serviceIds = $topServicesRaw->pluck('itemizable_id')->all();
        $services = Service::whereIn('id', $serviceIds)->get()->keyBy('id');
        $topServices = $topServicesRaw->map(function ($row) use ($services) {
            return [
                'service' => $services->get($row->itemizable_id),
                'quantity' => (int) $row->total_quantity,
                'revenue' => (float) $row->total_revenue,
            ];
        })->filter(fn ($item) => $item['service'] !== null);

        $busyHours = Invoice::selectRaw('HOUR(created_at) as hour, COUNT(*) as invoice_count, SUM(payable_amount) as revenue')
            ->groupBy('hour')
            ->orderByDesc('invoice_count')
            ->limit(6)
            ->get()
            ->sortBy('hour');

        $staffPerformance = Staff::with('upsellPerformance')
            ->get()
            ->sortByDesc(fn ($staff) => $staff->upsellPerformance?->upsell_revenue ?? 0)
            ->take(5);

        $activeCustomers = Customer::whereHas('invoices', function ($query) use ($last30Days, $now) {
            $query->whereBetween('created_at', [$last30Days, $now]);
        })->count();

        $newCustomers = Customer::whereHas('invoices', function ($query) use ($last30Days, $now) {
            $query->whereBetween('created_at', [$last30Days, $now]);
        })->whereDoesntHave('invoices', function ($query) use ($last30Days) {
            $query->whereDate('created_at', '<', $last30Days);
        })->count();

        $returningCustomers = Customer::whereHas('invoices', function ($query) use ($last30Days, $now) {
            $query->whereBetween('created_at', [$last30Days, $now]);
        })->whereHas('invoices', function ($query) use ($last30Days) {
            $query->whereDate('created_at', '<', $last30Days);
        })->count();

        $retentionRate = $activeCustomers > 0
            ? round(($returningCustomers / $activeCustomers) * 100, 1)
            : 0;

        $directProductCost = InvoiceItem::where('itemizable_type', Product::class)
            ->join('products', 'invoice_items.itemizable_id', '=', 'products.id')
            ->selectRaw('SUM(invoice_items.quantity * COALESCE(products.cost_price, 0)) as total_cost')
            ->value('total_cost') ?? 0;

        $serviceSupplyCost = DB::table('product_usages')
            ->join('products', 'product_usages.product_id', '=', 'products.id')
            ->selectRaw('SUM(product_usages.quantity_used * COALESCE(products.cost_price, 0)) as total_cost')
            ->value('total_cost') ?? 0;

        $totalCost = $directProductCost + $serviceSupplyCost;
        $totalRevenue = Invoice::sum('payable_amount');
        $grossProfit = $totalRevenue - $totalCost;
        $profitMargin = $totalRevenue > 0
            ? round(($grossProfit / $totalRevenue) * 100, 1)
            : 0;

        return view('reports.index', compact(
            'totalSalesToday',
            'transactionCountToday',
            'salesTrend',
            'serviceRevenue',
            'productRevenue',
            'serviceRevenueShare',
            'productRevenueShare',
            'topServices',
            'busyHours',
            'staffPerformance',
            'activeCustomers',
            'newCustomers',
            'returningCustomers',
            'retentionRate',
            'directProductCost',
            'serviceSupplyCost',
            'totalCost',
            'totalRevenue',
            'grossProfit',
            'profitMargin'
        ));
    }
}
