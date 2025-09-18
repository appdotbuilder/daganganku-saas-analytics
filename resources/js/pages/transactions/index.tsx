import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';

interface Transaction {
    id: number;
    transaction_number: string;
    total_amount: number;
    payment_method: string;
    customer_name: string | null;
    created_at: string;
    user: {
        name: string;
    };
    items: Array<{
        quantity: number;
        product: {
            name: string;
        };
    }>;
}

interface TransactionsProps {
    transactions: {
        data: Transaction[];
        links: Array<{ url?: string; label: string; active: boolean }>;
        total: number;
    };
    filters: {
        search?: string;
        date_from?: string;
        date_to?: string;
    };
    [key: string]: unknown;
}

export default function Transactions({ transactions }: TransactionsProps) {
    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(amount);
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        });
    };

    const getPaymentMethodBadge = (method: string) => {
        const variants: Record<string, 'default' | 'secondary' | 'outline' | 'destructive'> = {
            cash: 'default',
            transfer: 'secondary',
            card: 'outline',
            ewallet: 'destructive',
        };
        return variants[method] || 'default';
    };

    return (
        <AppShell>
            <Head title="Transaksi - DaganganKu" />
            
            <div className="space-y-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-3xl font-bold text-gray-900">💰 Transaksi Penjualan</h1>
                        <p className="text-gray-600 mt-1">Kelola dan monitor transaksi penjualan</p>
                    </div>
                    <Button asChild>
                        <Link href="/transactions/create">
                            ➕ Transaksi Baru
                        </Link>
                    </Button>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>Daftar Transaksi ({transactions.total})</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="space-y-4">
                            {transactions.data.length === 0 ? (
                                <div className="text-center py-8">
                                    <p className="text-gray-500">Belum ada transaksi</p>
                                    <Button asChild className="mt-4">
                                        <Link href="/transactions/create">
                                            💰 Buat Transaksi Pertama
                                        </Link>
                                    </Button>
                                </div>
                            ) : (
                                transactions.data.map((transaction) => (
                                    <div key={transaction.id} className="border rounded-lg p-4 hover:bg-gray-50">
                                        <div className="flex items-center justify-between">
                                            <div className="flex items-center space-x-4">
                                                <div>
                                                    <div className="font-semibold text-lg">
                                                        {transaction.transaction_number}
                                                    </div>
                                                    <div className="text-sm text-gray-600">
                                                        {formatDate(transaction.created_at)}
                                                    </div>
                                                </div>
                                                <div>
                                                    <Badge variant={getPaymentMethodBadge(transaction.payment_method)}>
                                                        {transaction.payment_method.toUpperCase()}
                                                    </Badge>
                                                </div>
                                            </div>
                                            
                                            <div className="text-right">
                                                <div className="text-xl font-bold text-green-600">
                                                    {formatCurrency(transaction.total_amount)}
                                                </div>
                                                <div className="text-sm text-gray-600">
                                                    {transaction.items.length} item • {transaction.user.name}
                                                </div>
                                                {transaction.customer_name && (
                                                    <div className="text-sm text-blue-600">
                                                        👤 {transaction.customer_name}
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                        
                                        <div className="mt-3 flex items-center justify-between">
                                            <div className="text-sm text-gray-600">
                                                Items: {transaction.items.map(item => 
                                                    `${item.product.name} (${item.quantity}x)`
                                                ).join(', ')}
                                            </div>
                                            <Button variant="outline" size="sm" asChild>
                                                <Link href={`/transactions/${transaction.id}`}>
                                                    Detail
                                                </Link>
                                            </Button>
                                        </div>
                                    </div>
                                ))
                            )}
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppShell>
    );
}