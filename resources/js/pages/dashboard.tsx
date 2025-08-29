import React from 'react';
import { Head } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/react';

interface DashboardStats {
    total_customers: number;
    active_customers: number;
    total_products: number;
    total_invoices: number;
    draft_invoices: number;
    outstanding_invoices: number;
    overdue_invoices: number;
    total_revenue: number;
    outstanding_amount: number;
    payments_received: number;
}

interface Invoice {
    id: number;
    invoice_number: string;
    customer: {
        name: string;
    };
    total_amount: number;
    status: string;
    due_date: string;
}

interface Payment {
    id: number;
    payment_reference: string;
    customer: {
        name: string;
    };
    amount: number;
    payment_method: string;
    payment_date: string;
}

interface Props {
    stats: DashboardStats;
    recent_invoices: Invoice[];
    recent_payments: Payment[];
    overdue_invoices: Invoice[];
    monthly_revenue: Record<string, number>;
    payment_methods: Array<{
        payment_method: string;
        count: number;
        total: number;
    }>;
    [key: string]: unknown;
}

export default function Dashboard({
    stats,
    recent_invoices,
    recent_payments,
    overdue_invoices,

}: Props) {
    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
        }).format(amount);
    };

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'paid':
            case 'fully_paid':
                return 'text-green-600 bg-green-50';
            case 'overdue':
                return 'text-red-600 bg-red-50';
            case 'partially_paid':
                return 'text-yellow-600 bg-yellow-50';
            default:
                return 'text-gray-600 bg-gray-50';
        }
    };

    return (
        <AppShell>
            <Head title="Dashboard - InvoiceFlow" />

            <div className="space-y-8">
                {/* Header */}
                <div className="flex justify-between items-center">
                    <div>
                        <h1 className="text-3xl font-bold text-gray-900">📊 Dashboard</h1>
                        <p className="text-gray-600 mt-2">Welcome to your invoice management overview</p>
                    </div>
                    <div className="flex space-x-3">
                        <Link href="/invoices/create">
                            <Button>
                                ➕ New Invoice
                            </Button>
                        </Link>
                        <Link href="/customers/create">
                            <Button variant="outline">
                                👤 Add Customer
                            </Button>
                        </Link>
                    </div>
                </div>

                {/* Stats Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div className="bg-white rounded-lg shadow p-6">
                        <div className="flex items-center">
                            <div className="p-3 rounded-full bg-blue-100 text-blue-600">
                                <span className="text-xl">💰</span>
                            </div>
                            <div className="ml-4">
                                <p className="text-sm font-medium text-gray-600">Total Revenue</p>
                                <p className="text-2xl font-semibold text-gray-900">
                                    {formatCurrency(stats.total_revenue)}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white rounded-lg shadow p-6">
                        <div className="flex items-center">
                            <div className="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <span className="text-xl">⏱️</span>
                            </div>
                            <div className="ml-4">
                                <p className="text-sm font-medium text-gray-600">Outstanding</p>
                                <p className="text-2xl font-semibold text-gray-900">
                                    {formatCurrency(stats.outstanding_amount)}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white rounded-lg shadow p-6">
                        <div className="flex items-center">
                            <div className="p-3 rounded-full bg-green-100 text-green-600">
                                <span className="text-xl">👥</span>
                            </div>
                            <div className="ml-4">
                                <p className="text-sm font-medium text-gray-600">Active Customers</p>
                                <p className="text-2xl font-semibold text-gray-900">
                                    {stats.active_customers}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white rounded-lg shadow p-6">
                        <div className="flex items-center">
                            <div className="p-3 rounded-full bg-red-100 text-red-600">
                                <span className="text-xl">⚠️</span>
                            </div>
                            <div className="ml-4">
                                <p className="text-sm font-medium text-gray-600">Overdue</p>
                                <p className="text-2xl font-semibold text-gray-900">
                                    {stats.overdue_invoices}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Main Content Grid */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {/* Recent Invoices */}
                    <div className="bg-white rounded-lg shadow">
                        <div className="p-6 border-b border-gray-200">
                            <div className="flex justify-between items-center">
                                <h3 className="text-lg font-medium text-gray-900">📄 Recent Invoices</h3>
                                <Link href="/invoices">
                                    <Button variant="outline" size="sm">View All</Button>
                                </Link>
                            </div>
                        </div>
                        <div className="p-6">
                            <div className="space-y-4">
                                {recent_invoices.map((invoice) => (
                                    <div key={invoice.id} className="flex items-center justify-between">
                                        <div>
                                            <p className="font-medium text-gray-900">{invoice.invoice_number}</p>
                                            <p className="text-sm text-gray-600">{invoice.customer.name}</p>
                                        </div>
                                        <div className="text-right">
                                            <p className="font-medium text-gray-900">
                                                {formatCurrency(invoice.total_amount)}
                                            </p>
                                            <span className={`inline-flex px-2 py-1 text-xs font-medium rounded-full ${getStatusColor(invoice.status)}`}>
                                                {invoice.status.replace('_', ' ')}
                                            </span>
                                        </div>
                                    </div>
                                ))}
                                {recent_invoices.length === 0 && (
                                    <div className="text-center py-8 text-gray-500">
                                        <span className="text-4xl mb-4 block">📝</span>
                                        <p>No invoices yet</p>
                                        <Link href="/invoices/create" className="text-blue-600 hover:text-blue-800">
                                            Create your first invoice
                                        </Link>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Recent Payments */}
                    <div className="bg-white rounded-lg shadow">
                        <div className="p-6 border-b border-gray-200">
                            <div className="flex justify-between items-center">
                                <h3 className="text-lg font-medium text-gray-900">💳 Recent Payments</h3>
                                <Button variant="outline" size="sm">View All</Button>
                            </div>
                        </div>
                        <div className="p-6">
                            <div className="space-y-4">
                                {recent_payments.map((payment) => (
                                    <div key={payment.id} className="flex items-center justify-between">
                                        <div>
                                            <p className="font-medium text-gray-900">{payment.payment_reference}</p>
                                            <p className="text-sm text-gray-600">{payment.customer.name}</p>
                                        </div>
                                        <div className="text-right">
                                            <p className="font-medium text-green-600">
                                                {formatCurrency(payment.amount)}
                                            </p>
                                            <p className="text-sm text-gray-600 capitalize">
                                                {payment.payment_method.replace('_', ' ')}
                                            </p>
                                        </div>
                                    </div>
                                ))}
                                {recent_payments.length === 0 && (
                                    <div className="text-center py-8 text-gray-500">
                                        <span className="text-4xl mb-4 block">💰</span>
                                        <p>No payments yet</p>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                {/* Overdue Invoices */}
                {overdue_invoices.length > 0 && (
                    <div className="bg-white rounded-lg shadow">
                        <div className="p-6 border-b border-gray-200">
                            <h3 className="text-lg font-medium text-red-600">⚠️ Overdue Invoices</h3>
                        </div>
                        <div className="p-6">
                            <div className="space-y-4">
                                {overdue_invoices.slice(0, 5).map((invoice) => (
                                    <div key={invoice.id} className="flex items-center justify-between bg-red-50 p-4 rounded-lg">
                                        <div>
                                            <p className="font-medium text-gray-900">{invoice.invoice_number}</p>
                                            <p className="text-sm text-gray-600">{invoice.customer.name}</p>
                                            <p className="text-sm text-red-600">
                                                Due: {new Date(invoice.due_date).toLocaleDateString()}
                                            </p>
                                        </div>
                                        <div className="text-right">
                                            <p className="font-medium text-gray-900">
                                                {formatCurrency(invoice.total_amount)}
                                            </p>
                                            <Link href={`/invoices/${invoice.id}`}>
                                                <Button size="sm" variant="outline">
                                                    View Invoice
                                                </Button>
                                            </Link>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                )}

                {/* Quick Actions */}
                <div className="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg text-white p-8">
                    <h3 className="text-xl font-bold mb-4">🚀 Quick Actions</h3>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <Link href="/invoices/create">
                            <Button variant="secondary" className="w-full justify-start">
                                ➕ Create New Invoice
                            </Button>
                        </Link>
                        <Link href="/customers/create">
                            <Button variant="secondary" className="w-full justify-start">
                                👤 Add New Customer
                            </Button>
                        </Link>
                        <Link href="/products/create">
                            <Button variant="secondary" className="w-full justify-start">
                                📦 Add New Product
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>
        </AppShell>
    );
}