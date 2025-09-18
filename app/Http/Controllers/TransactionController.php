<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $tenantId = $user->tenant_id;
        
        $query = Transaction::with(['user', 'items.product'])
            ->where('tenant_id', $tenantId);
        
        // Filter by kasir for kasir role
        if ($user->isKasir()) {
            $query->where('user_id', $user->id);
        }
        
        // Date filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Search filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('transaction_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%');
            });
        }
        
        $transactions = $query->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return Inertia::render('transactions/index', [
            'transactions' => $transactions,
            'filters' => $request->only(['search', 'date_from', 'date_to']),
        ]);
    }

    /**
     * Show the form for creating a new transaction.
     */
    public function create(Request $request)
    {
        $user = $request->user();
        $tenantId = $user->tenant_id;
        
        $products = Product::with('category')
            ->where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->get();
        
        return Inertia::render('transactions/create', [
            'products' => $products,
            'taxRate' => $user->tenant->tax_rate ?? 0,
        ]);
    }

    /**
     * Store a newly created transaction.
     */
    public function store(StoreTransactionRequest $request)
    {
        $user = $request->user();
        $tenantId = $user->tenant_id;
        
        return DB::transaction(function() use ($request, $user, $tenantId) {
            // Create transaction
            $transaction = Transaction::create([
                'tenant_id' => $tenantId,
                'user_id' => $user->id,
                'transaction_number' => Transaction::generateTransactionNumber(),
                'subtotal' => $request->subtotal,
                'tax_amount' => $request->tax_amount,
                'discount_amount' => $request->discount_amount,
                'total_amount' => $request->total_amount,
                'payment_method' => $request->payment_method,
                'customer_name' => $request->customer_name,
                'notes' => $request->notes,
            ]);
            
            // Create transaction items and update stock
            foreach ($request->items as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                
                // Check stock availability
                if ($product->stock_quantity < $itemData['quantity']) {
                    throw new \Exception("Stok tidak mencukupi untuk produk: {$product->name}");
                }
                
                // Create transaction item
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'total_price' => $itemData['total_price'],
                ]);
                
                // Update product stock
                $stockBefore = $product->stock_quantity;
                $product->decrement('stock_quantity', $itemData['quantity']);
                $product->refresh();
                
                // Create stock movement record
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'type' => 'out',
                    'quantity' => $itemData['quantity'],
                    'stock_before' => $stockBefore,
                    'stock_after' => $product->stock_quantity,
                    'reference_type' => 'transaction',
                    'reference_id' => $transaction->id,
                    'notes' => "Penjualan #{$transaction->transaction_number}",
                ]);
            }
            
            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Transaksi berhasil disimpan');
        });
    }

    /**
     * Display the specified transaction.
     */
    public function show(Request $request, Transaction $transaction)
    {
        $user = $request->user();
        
        // Check access permission
        if ($user->isKasir() && $transaction->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini');
        }
        
        $transaction->load(['user', 'items.product.category']);
        
        return Inertia::render('transactions/show', [
            'transaction' => $transaction,
        ]);
    }
}