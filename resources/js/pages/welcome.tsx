import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';

export default function Welcome() {
    return (
        <>
            <Head title="DaganganKu - SaaS Dashboard Analytics untuk UMKM" />
            
            <div className="min-h-screen bg-gradient-to-br from-blue-50 via-white to-emerald-50">
                {/* Navigation */}
                <nav className="bg-white/80 backdrop-blur-md border-b border-gray-200">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex justify-between items-center h-16">
                            <div className="flex items-center space-x-2">
                                <div className="w-8 h-8 bg-gradient-to-br from-blue-600 to-emerald-600 rounded-lg flex items-center justify-center">
                                    <span className="text-white font-bold text-sm">📊</span>
                                </div>
                                <span className="font-bold text-xl text-gray-900">DaganganKu</span>
                            </div>
                            
                            <div className="flex items-center space-x-4">
                                <Link 
                                    href="/login"
                                    className="text-gray-600 hover:text-gray-900 font-medium"
                                >
                                    Masuk
                                </Link>
                                <Button asChild>
                                    <Link href="/register">
                                        Daftar Gratis
                                    </Link>
                                </Button>
                            </div>
                        </div>
                    </div>
                </nav>

                {/* Hero Section */}
                <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-16">
                    <div className="text-center">
                        <h1 className="text-5xl font-extrabold text-gray-900 sm:text-6xl">
                            📊 <span className="bg-gradient-to-r from-blue-600 to-emerald-600 bg-clip-text text-transparent">DaganganKu</span>
                        </h1>
                        <p className="mt-6 text-xl text-gray-600 max-w-3xl mx-auto">
                            Sistem Pencatatan Transaksi & Dashboard Analytics Berbasis Cloud 
                            untuk UMKM Indonesia 🇮🇩
                        </p>
                        <p className="mt-4 text-lg text-gray-500">
                            Kelola penjualan, stok, dan laporan bisnis Anda dalam satu platform terintegrasi
                        </p>
                        
                        <div className="mt-10 flex justify-center space-x-4">
                            <Button size="lg" asChild>
                                <Link href="/register">
                                    🚀 Mulai Gratis Sekarang
                                </Link>
                            </Button>
                            <Button variant="outline" size="lg" asChild>
                                <Link href="/login">
                                    📱 Demo Dashboard
                                </Link>
                            </Button>
                        </div>
                    </div>
                </section>

                {/* Features Section */}
                <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    <div className="text-center mb-16">
                        <h2 className="text-3xl font-bold text-gray-900">Fitur Utama DaganganKu</h2>
                        <p className="mt-4 text-lg text-gray-600">Solusi lengkap untuk mengelola bisnis UMKM Anda</p>
                    </div>
                    
                    <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {/* Multi-tenant & RBAC */}
                        <div className="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                            <div className="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mb-4">
                                <span className="text-2xl">👥</span>
                            </div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-3">Multi-user Access</h3>
                            <p className="text-gray-600 mb-4">
                                Kelola pengguna dengan Role-Based Access Control (Admin, Staff, Kasir)
                            </p>
                            <ul className="text-sm text-gray-500 space-y-1">
                                <li>• Admin: Kelola semua data & pengguna</li>
                                <li>• Staff: Kelola produk & stok</li>
                                <li>• Kasir: Input transaksi penjualan</li>
                            </ul>
                        </div>

                        {/* POS System */}
                        <div className="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                            <div className="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center mb-4">
                                <span className="text-2xl">💰</span>
                            </div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-3">Sistem POS</h3>
                            <p className="text-gray-600 mb-4">
                                Input transaksi penjualan dengan mudah dan cepat
                            </p>
                            <ul className="text-sm text-gray-500 space-y-1">
                                <li>• Pencatatan transaksi real-time</li>
                                <li>• Multiple payment methods</li>
                                <li>• Struk otomatis & riwayat</li>
                            </ul>
                        </div>

                        {/* Analytics Dashboard */}
                        <div className="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                            <div className="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mb-4">
                                <span className="text-2xl">📈</span>
                            </div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-3">Dashboard Interaktif</h3>
                            <p className="text-gray-600 mb-4">
                                Visualisasi data penjualan dengan grafik & chart
                            </p>
                            <ul className="text-sm text-gray-500 space-y-1">
                                <li>• Tren penjualan harian/bulanan</li>
                                <li>• Top selling products</li>
                                <li>• Performance kategori</li>
                            </ul>
                        </div>

                        {/* Inventory Management */}
                        <div className="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                            <div className="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center mb-4">
                                <span className="text-2xl">📦</span>
                            </div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-3">Manajemen Stok</h3>
                            <p className="text-gray-600 mb-4">
                                Kelola inventaris dengan sistem tracking otomatis
                            </p>
                            <ul className="text-sm text-gray-500 space-y-1">
                                <li>• Real-time stock monitoring</li>
                                <li>• Low stock notifications</li>
                                <li>• Stock movement history</li>
                            </ul>
                        </div>

                        {/* Reports */}
                        <div className="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                            <div className="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center mb-4">
                                <span className="text-2xl">📄</span>
                            </div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-3">Laporan Otomatis</h3>
                            <p className="text-gray-600 mb-4">
                                Export laporan dalam format PDF & Excel
                            </p>
                            <ul className="text-sm text-gray-500 space-y-1">
                                <li>• Laporan penjualan harian/bulanan</li>
                                <li>• Laporan inventaris & stok</li>
                                <li>• Customer insights</li>
                            </ul>
                        </div>

                        {/* Customer Insights */}
                        <div className="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                            <div className="w-12 h-12 bg-gradient-to-br from-teal-500 to-teal-600 rounded-lg flex items-center justify-center mb-4">
                                <span className="text-2xl">🎯</span>
                            </div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-3">Customer Insights</h3>
                            <p className="text-gray-600 mb-4">
                                Analisis pelanggan untuk strategi bisnis
                            </p>
                            <ul className="text-sm text-gray-500 space-y-1">
                                <li>• Jumlah pelanggan unik</li>
                                <li>• Repeat customer rate</li>
                                <li>• Average transaction value</li>
                            </ul>
                        </div>
                    </div>
                </section>

                {/* CTA Section */}
                <section className="bg-gradient-to-r from-blue-600 to-emerald-600 py-16">
                    <div className="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                        <h2 className="text-3xl font-bold text-white mb-4">
                            Siap Mengembangkan Bisnis UMKM Anda? 🚀
                        </h2>
                        <p className="text-xl text-blue-100 mb-8">
                            Bergabunglah dengan ribuan UMKM yang sudah menggunakan DaganganKu 
                            untuk mengelola bisnis mereka dengan lebih efisien
                        </p>
                        
                        <div className="flex flex-col sm:flex-row gap-4 justify-center">
                            <Button size="lg" variant="secondary" asChild>
                                <Link href="/register">
                                    📊 Coba Dashboard Gratis
                                </Link>
                            </Button>
                            <Button size="lg" variant="outline" className="border-white text-white hover:bg-white hover:text-blue-600" asChild>
                                <Link href="/login">
                                    🔐 Masuk ke Akun
                                </Link>
                            </Button>
                        </div>
                        
                        <div className="mt-8 text-blue-100 text-sm">
                            💡 <strong>Gratis 30 hari pertama</strong> - Tidak perlu kartu kredit
                        </div>
                    </div>
                </section>

                {/* Footer */}
                <footer className="bg-gray-900 text-gray-400 py-12">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center">
                            <div className="flex items-center justify-center space-x-2 mb-4">
                                <div className="w-8 h-8 bg-gradient-to-br from-blue-600 to-emerald-600 rounded-lg flex items-center justify-center">
                                    <span className="text-white font-bold text-sm">📊</span>
                                </div>
                                <span className="font-bold text-xl text-white">DaganganKu</span>
                            </div>
                            <p className="text-gray-400">
                                © 2024 DaganganKu. Platform SaaS untuk UMKM Indonesia 🇮🇩
                            </p>
                            <p className="text-sm text-gray-500 mt-2">
                                Membantu UMKM berkembang dengan teknologi terdepan
                            </p>
                        </div>
                    </div>
                </footer>
            </div>
        </>
    );
}