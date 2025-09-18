import React from 'react';
import { Head } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';

interface DashboardProps {
    salesSummary: {
        totalTransactions: number;
        totalRevenue: number;
        avgTransactionValue: number;
        grossProfit: number;
    };
    dailySales: Record<string, { revenue: number; transactions: number }>;
    topProducts: Array<{
        name: string;
        total_quantity: number;
        total_revenue: number;
    }>;
    lowStockProducts: Array<{
        id: number;
        name: string;
        stock_quantity: number;
        minimum_stock: number;
        category: { name: string };
    }>;
    categoryPerformance: Array<{
        name: string;
        total_quantity: number;
        total_revenue: number;
    }>;
    monthlyComparison: {
        thisMonth: { revenue: number; transactions: number } | null;
        lastMonth: { revenue: number; transactions: number } | null;
    };
    customerInsights: {
        uniqueCustomers: number;
        totalTransactions: number;
        repeatCustomerRate: number;
    };
    [key: string]: unknown;
}

export default function Dashboard({
    salesSummary,
    dailySales,
    topProducts,
    lowStockProducts,
    categoryPerformance,
    monthlyComparison,
    customerInsights,
}: DashboardProps) {
    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(amount);
    };

    const formatNumber = (num: number) => {
        return new Intl.NumberFormat('id-ID').format(num);
    };

    // Calculate growth percentage
    const revenueGrowth = monthlyComparison.lastMonth?.revenue 
        ? ((monthlyComparison.thisMonth?.revenue || 0) - monthlyComparison.lastMonth.revenue) / monthlyComparison.lastMonth.revenue * 100
        : 0;

    return (
        <AppShell>
            <Head title="Dashboard Analytics - DaganganKu" />
            
            <div className="space-y-6">
                {/* Header */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-3xl font-bold text-gray-900">📊 Dashboard Analytics</h1>
                        <p className="text-gray-600 mt-1">Selamat datang di DaganganKu - Monitor performa bisnis Anda</p>
                    </div>
                    <div className="flex space-x-3">
                        <Button variant="outline">
                            📄 Export Laporan
                        </Button>
                        <Button>
                            💰 Input Transaksi
                        </Button>
                    </div>
                </div>

                {/* Key Metrics */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <Card>
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium">Total Penjualan</CardTitle>
                            <span className="text-2xl">💰</span>
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold">{formatCurrency(salesSummary.totalRevenue)}</div>
                            <p className="text-xs text-muted-foreground">
                                {revenueGrowth > 0 ? '+' : ''}{revenueGrowth.toFixed(1)}% dari bulan lalu
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium">Total Transaksi</CardTitle>
                            <span className="text-2xl">🛒</span>
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold">{formatNumber(salesSummary.totalTransactions)}</div>
                            <p className="text-xs text-muted-foreground">
                                Rata-rata {formatCurrency(salesSummary.avgTransactionValue)} per transaksi
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium">Gross Profit</CardTitle>
                            <span className="text-2xl">📈</span>
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold">{formatCurrency(salesSummary.grossProfit)}</div>
                            <p className="text-xs text-muted-foreground">
                                Margin: {salesSummary.totalRevenue > 0 ? ((salesSummary.grossProfit / salesSummary.totalRevenue) * 100).toFixed(1) : 0}%
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium">Pelanggan Unik</CardTitle>
                            <span className="text-2xl">👥</span>
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold">{formatNumber(customerInsights.uniqueCustomers)}</div>
                            <p className="text-xs text-muted-foreground">
                                {customerInsights.repeatCustomerRate}% repeat customers
                            </p>
                        </CardContent>
                    </Card>
                </div>

                {/* Charts and Analytics */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {/* Daily Sales Trend */}
                    <Card>
                        <CardHeader>
                            <CardTitle className="flex items-center space-x-2">
                                <span>📈</span>
                                <span>Tren Penjualan 7 Hari</span>
                            </CardTitle>
                            <CardDescription>Penjualan harian dalam rupiah</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="space-y-2">
                                {Object.entries(dailySales).map(([date, data]) => (
                                    <div key={date} className="flex items-center justify-between py-2 border-b">
                                        <span className="text-sm font-medium">{date}</span>
                                        <div className="text-right">
                                            <div className="font-semibold">{formatCurrency(data.revenue)}</div>
                                            <div className="text-xs text-gray-500">{data.transactions} transaksi</div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </CardContent>
                    </Card>

                    {/* Top Products */}
                    <Card>
                        <CardHeader>
                            <CardTitle className="flex items-center space-x-2">
                                <span>🏆</span>
                                <span>Produk Terlaris</span>
                            </CardTitle>
                            <CardDescription>Berdasarkan jumlah terjual</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="space-y-3">
                                {topProducts.slice(0, 5).map((product, index) => (
                                    <div key={product.name} className="flex items-center justify-between">
                                        <div className="flex items-center space-x-2">
                                            <Badge variant={index === 0 ? 'default' : 'secondary'}>
                                                #{index + 1}
                                            </Badge>
                                            <span className="font-medium">{product.name}</span>
                                        </div>
                                        <div className="text-right">
                                            <div className="font-semibold">{formatNumber(product.total_quantity)} pcs</div>
                                            <div className="text-xs text-gray-500">{formatCurrency(product.total_revenue)}</div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </CardContent>
                    </Card>
                </div>

                {/* Stock Alerts & Category Performance */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {/* Low Stock Alert */}
                    <Card>
                        <CardHeader>
                            <CardTitle className="flex items-center space-x-2">
                                <span>⚠️</span>
                                <span>Stok Menipis</span>
                            </CardTitle>
                            <CardDescription>Produk yang perlu restock segera</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="space-y-3">
                                {lowStockProducts.length === 0 ? (
                                    <p className="text-center text-gray-500 py-4">✅ Semua produk stoknya aman!</p>
                                ) : (
                                    lowStockProducts.slice(0, 5).map((product) => (
                                        <div key={product.id} className="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-200">
                                            <div>
                                                <div className="font-medium text-red-900">{product.name}</div>
                                                <div className="text-sm text-red-700">{product.category.name}</div>
                                            </div>
                                            <div className="text-right">
                                                <Badge variant="destructive">
                                                    {product.stock_quantity} / {product.minimum_stock}
                                                </Badge>
                                                <div className="text-xs text-red-600 mt-1">Sisa stok</div>
                                            </div>
                                        </div>
                                    ))
                                )}
                            </div>
                        </CardContent>
                    </Card>

                    {/* Category Performance */}
                    <Card>
                        <CardHeader>
                            <CardTitle className="flex items-center space-x-2">
                                <span>🏷️</span>
                                <span>Performa Kategori</span>
                            </CardTitle>
                            <CardDescription>Revenue berdasarkan kategori produk</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="space-y-3">
                                {categoryPerformance.slice(0, 5).map((category, index) => (
                                    <div key={category.name} className="flex items-center justify-between">
                                        <div className="flex items-center space-x-2">
                                            <div className={`w-3 h-3 rounded-full ${
                                                index === 0 ? 'bg-blue-500' :
                                                index === 1 ? 'bg-green-500' :
                                                index === 2 ? 'bg-yellow-500' :
                                                index === 3 ? 'bg-purple-500' : 'bg-gray-400'
                                            }`}></div>
                                            <span className="font-medium">{category.name}</span>
                                        </div>
                                        <div className="text-right">
                                            <div className="font-semibold">{formatCurrency(category.total_revenue)}</div>
                                            <div className="text-xs text-gray-500">{formatNumber(category.total_quantity)} item</div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </CardContent>
                    </Card>
                </div>

                {/* Quick Actions */}
                <Card>
                    <CardHeader>
                        <CardTitle className="flex items-center space-x-2">
                            <span>⚡</span>
                            <span>Quick Actions</span>
                        </CardTitle>
                        <CardDescription>Aksi cepat untuk mengelola bisnis Anda</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <Button className="h-20 flex-col space-y-2" variant="outline">
                                <span className="text-2xl">💰</span>
                                <span>Transaksi Baru</span>
                            </Button>
                            <Button className="h-20 flex-col space-y-2" variant="outline">
                                <span className="text-2xl">📦</span>
                                <span>Tambah Produk</span>
                            </Button>
                            <Button className="h-20 flex-col space-y-2" variant="outline">
                                <span className="text-2xl">📊</span>
                                <span>Lihat Laporan</span>
                            </Button>
                            <Button className="h-20 flex-col space-y-2" variant="outline">
                                <span className="text-2xl">⚙️</span>
                                <span>Pengaturan</span>
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppShell>
    );
}