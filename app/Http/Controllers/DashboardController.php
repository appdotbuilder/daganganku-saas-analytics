<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $tenantId = $user->tenant_id;
        
        // Date range for filtering (default: last 30 days)
        $startDate = $request->get('start_date', now()->subDays(30)->startOfDay());
        $endDate = $request->get('end_date', now()->endOfDay());
        
        // Sales summary
        $salesData = Transaction::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                COUNT(*) as total_transactions,
                SUM(total_amount) as total_revenue,
                AVG(total_amount) as avg_transaction_value,
                SUM(subtotal - (
                    SELECT COALESCE(SUM(ti.quantity * p.cost_price), 0)
                    FROM transaction_items ti
                    JOIN products p ON ti.product_id = p.id
                    WHERE ti.transaction_id = transactions.id
                )) as gross_profit
            ')
            ->first();
        
        // Daily sales trend (last 7 days)
        $dailySalesRaw = DB::select("
            SELECT DATE(created_at) as date, 
                   SUM(total_amount) as total, 
                   COUNT(*) as count
            FROM transactions 
            WHERE tenant_id = ? 
              AND created_at BETWEEN ? AND ?
            GROUP BY DATE(created_at)
            ORDER BY date
        ", [$tenantId, now()->subDays(6)->startOfDay(), now()->endOfDay()]);
            
        $dailySales = collect($dailySalesRaw)->mapWithKeys(function($item) {
            return [
                Carbon::parse($item->date)->format('M d') => [
                    'revenue' => (float) $item->total,
                    'transactions' => (int) $item->count
                ]
            ];
        });
        
        // Top selling products
        $topProducts = TransactionItem::join('products', 'transaction_items.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transactions.tenant_id', $tenantId)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->selectRaw('
                products.name,
                SUM(transaction_items.quantity) as total_quantity,
                SUM(transaction_items.total_price) as total_revenue
            ')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();
        
        // Low stock products
        $lowStockProducts = Product::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->whereRaw('stock_quantity <= minimum_stock')
            ->with('category')
            ->orderBy('stock_quantity')
            ->limit(10)
            ->get();
        
        // Category performance
        $categoryPerformance = TransactionItem::join('products', 'transaction_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transactions.tenant_id', $tenantId)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->selectRaw('
                categories.name,
                SUM(transaction_items.quantity) as total_quantity,
                SUM(transaction_items.total_price) as total_revenue
            ')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();
        
        // Monthly comparison
        $thisMonth = Transaction::where('tenant_id', $tenantId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->selectRaw('SUM(total_amount) as revenue, COUNT(*) as transactions')
            ->first();
        
        $lastMonth = Transaction::where('tenant_id', $tenantId)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->selectRaw('SUM(total_amount) as revenue, COUNT(*) as transactions')
            ->first();
        
        // Customer insights
        $customerData = Transaction::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                COUNT(DISTINCT customer_name) as unique_customers,
                COUNT(*) as total_transactions
            ')
            ->whereNotNull('customer_name')
            ->first();
        
        return Inertia::render('dashboard', [
            'salesSummary' => [
                'totalTransactions' => $salesData->total_transactions ?? 0,
                'totalRevenue' => $salesData->total_revenue ?? 0,
                'avgTransactionValue' => $salesData->avg_transaction_value ?? 0,
                'grossProfit' => $salesData->gross_profit ?? 0,
            ],
            'dailySales' => $dailySales->toArray(),
            'topProducts' => $topProducts,
            'lowStockProducts' => $lowStockProducts,
            'categoryPerformance' => $categoryPerformance,
            'monthlyComparison' => [
                'thisMonth' => $thisMonth,
                'lastMonth' => $lastMonth,
            ],
            'customerInsights' => [
                'uniqueCustomers' => $customerData->unique_customers ?? 0,
                'totalTransactions' => $customerData->total_transactions ?? 0,
                'repeatCustomerRate' => ($customerData->total_transactions ?? 0) > 0 
                    ? round(((($customerData->total_transactions ?? 0) - ($customerData->unique_customers ?? 0)) / ($customerData->total_transactions ?? 1)) * 100, 1)
                    : 0,
            ],
            'dateRange' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
        ]);
    }
}