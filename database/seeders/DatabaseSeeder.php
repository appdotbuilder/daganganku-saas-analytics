<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\StockMovement;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create sample tenant
        $tenant = Tenant::create([
            'name' => 'Demo UMKM',
            'business_name' => 'Toko Sembako Maju Jaya',
            'email' => 'demo@daganganku.com',
            'phone' => '08123456789',
            'address' => 'Jl. Merdeka No. 123, Jakarta',
            'status' => 'active',
            'tax_rate' => 10.00,
        ]);

        // Create users with different roles
        $admin = User::create([
            'name' => 'Admin Demo',
            'email' => 'admin@daganganku.com',
            'password' => Hash::make('password'),
            'tenant_id' => $tenant->id,
            'role' => 'admin',
            'is_active' => true,
        ]);

        $staff = User::create([
            'name' => 'Staff Demo',
            'email' => 'staff@daganganku.com',
            'password' => Hash::make('password'),
            'tenant_id' => $tenant->id,
            'role' => 'staff',
            'is_active' => true,
        ]);

        $kasir = User::create([
            'name' => 'Kasir Demo',
            'email' => 'kasir@daganganku.com',
            'password' => Hash::make('password'),
            'tenant_id' => $tenant->id,
            'role' => 'kasir',
            'is_active' => true,
        ]);

        // Create categories
        $categories = [
            ['name' => 'Makanan', 'description' => 'Produk makanan siap saji'],
            ['name' => 'Minuman', 'description' => 'Minuman segar dan sehat'],
            ['name' => 'Snack', 'description' => 'Cemilan dan snack ringan'],
            ['name' => 'Sembako', 'description' => 'Kebutuhan pokok sehari-hari'],
            ['name' => 'Peralatan', 'description' => 'Peralatan rumah tangga'],
        ];

        foreach ($categories as $categoryData) {
            Category::create([
                'tenant_id' => $tenant->id,
                'name' => $categoryData['name'],
                'description' => $categoryData['description'],
                'is_active' => true,
            ]);
        }

        // Get created categories
        $makananCategory = Category::where('name', 'Makanan')->first();
        $minumanCategory = Category::where('name', 'Minuman')->first();
        $snackCategory = Category::where('name', 'Snack')->first();
        $sembakoCategory = Category::where('name', 'Sembako')->first();

        // Create products
        $products = [
            // Makanan
            ['category_id' => $makananCategory->id, 'name' => 'Nasi Goreng', 'sku' => 'MKN-001', 'cost_price' => 8000, 'selling_price' => 15000, 'stock_quantity' => 50, 'minimum_stock' => 10, 'unit' => 'porsi'],
            ['category_id' => $makananCategory->id, 'name' => 'Mie Ayam', 'sku' => 'MKN-002', 'cost_price' => 7000, 'selling_price' => 12000, 'stock_quantity' => 30, 'minimum_stock' => 5, 'unit' => 'porsi'],
            ['category_id' => $makananCategory->id, 'name' => 'Gado-gado', 'sku' => 'MKN-003', 'cost_price' => 6000, 'selling_price' => 10000, 'stock_quantity' => 25, 'minimum_stock' => 5, 'unit' => 'porsi'],
            
            // Minuman
            ['category_id' => $minumanCategory->id, 'name' => 'Es Teh Manis', 'sku' => 'MNM-001', 'cost_price' => 2000, 'selling_price' => 5000, 'stock_quantity' => 100, 'minimum_stock' => 20, 'unit' => 'gelas'],
            ['category_id' => $minumanCategory->id, 'name' => 'Kopi Hitam', 'sku' => 'MNM-002', 'cost_price' => 3000, 'selling_price' => 7000, 'stock_quantity' => 80, 'minimum_stock' => 15, 'unit' => 'gelas'],
            ['category_id' => $minumanCategory->id, 'name' => 'Jus Jeruk', 'sku' => 'MNM-003', 'cost_price' => 4000, 'selling_price' => 8000, 'stock_quantity' => 60, 'minimum_stock' => 10, 'unit' => 'gelas'],
            
            // Snack
            ['category_id' => $snackCategory->id, 'name' => 'Kerupuk Udang', 'sku' => 'SNK-001', 'cost_price' => 3000, 'selling_price' => 5000, 'stock_quantity' => 45, 'minimum_stock' => 10, 'unit' => 'pack'],
            ['category_id' => $snackCategory->id, 'name' => 'Kacang Tanah', 'sku' => 'SNK-002', 'cost_price' => 8000, 'selling_price' => 12000, 'stock_quantity' => 35, 'minimum_stock' => 8, 'unit' => 'pack'],
            
            // Sembako
            ['category_id' => $sembakoCategory->id, 'name' => 'Beras Premium', 'sku' => 'SMB-001', 'cost_price' => 12000, 'selling_price' => 15000, 'stock_quantity' => 8, 'minimum_stock' => 10, 'unit' => 'kg'], // Low stock
            ['category_id' => $sembakoCategory->id, 'name' => 'Minyak Goreng', 'sku' => 'SMB-002', 'cost_price' => 14000, 'selling_price' => 18000, 'stock_quantity' => 20, 'minimum_stock' => 5, 'unit' => 'liter'],
        ];

        foreach ($products as $productData) {
            Product::create([
                'tenant_id' => $tenant->id,
                ...$productData,
                'is_active' => true,
            ]);
        }

        // Create sample transactions for the last 30 days
        $products = Product::where('tenant_id', $tenant->id)->get();
        
        for ($i = 0; $i < 50; $i++) {
            $createdAt = now()->subDays(random_int(0, 30))->subHours(random_int(8, 20));
            
            // Random transaction items (1-4 items per transaction)
            $itemCount = random_int(1, 4);
            $selectedProducts = $products->random($itemCount);
            
            $subtotal = 0;
            $transactionItems = [];
            
            foreach ($selectedProducts as $product) {
                $quantity = random_int(1, 3);
                $unitPrice = $product->selling_price;
                $totalPrice = $quantity * $unitPrice;
                $subtotal += $totalPrice;
                
                $transactionItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ];
            }
            
            $taxAmount = $subtotal * ($tenant->tax_rate / 100);
            $discountAmount = random_int(0, 1) ? random_int(1000, 5000) : 0;
            $totalAmount = $subtotal + $taxAmount - $discountAmount;
            
            $transaction = Transaction::create([
                'tenant_id' => $tenant->id,
                'user_id' => $kasir->id,
                'transaction_number' => 'TRX' . now()->format('Ymd') . str_pad((string)($i + 1), 4, '0', STR_PAD_LEFT),
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'payment_method' => fake()->randomElement(['cash', 'transfer', 'card', 'ewallet']),
                'customer_name' => fake()->boolean(70) ? fake()->name() : null,
                'notes' => fake()->boolean(20) ? fake()->sentence() : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
            
            // Create transaction items
            foreach ($transactionItems as $itemData) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    ...$itemData,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
                
                // Create stock movement
                $product = Product::find($itemData['product_id']);
                $stockBefore = $product->stock_quantity + $itemData['quantity']; // Simulate stock before sale
                
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $kasir->id,
                    'type' => 'out',
                    'quantity' => $itemData['quantity'],
                    'stock_before' => $stockBefore,
                    'stock_after' => $product->stock_quantity,
                    'reference_type' => 'transaction',
                    'reference_id' => $transaction->id,
                    'notes' => "Penjualan #{$transaction->transaction_number}",
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }
        
        echo "✅ Demo data created successfully!\n";
        echo "👤 Admin: admin@daganganku.com / password\n";
        echo "👤 Staff: staff@daganganku.com / password\n";
        echo "👤 Kasir: kasir@daganganku.com / password\n";
    }
}