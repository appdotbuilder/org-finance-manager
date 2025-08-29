import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';

interface Invoice {
    id: number;
    invoice_number: string;
    customer: {
        id: number;
        name: string;
        email: string;
    };
    issue_date: string;
    due_date: string;
    status: string;
    total_amount: number;
    balance_due: number;
    created_at: string;
}

interface InvoiceStats {
    total_invoices: number;
    total_amount: number;
    outstanding_amount: number;
    overdue_count: number;
}

interface PaginatedInvoices {
    data: Invoice[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}

interface Props {
    invoices: PaginatedInvoices;
    stats: InvoiceStats;
    [key: string]: unknown;
}

export default function InvoicesIndex({ invoices, stats }: Props) {
    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
        }).format(amount);
    };

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'fully_paid':
                return 'bg-green-100 text-green-800';
            case 'overdue':
                return 'bg-red-100 text-red-800';
            case 'partially_paid':
                return 'bg-yellow-100 text-yellow-800';
            case 'issued':
                return 'bg-blue-100 text-blue-800';
            case 'draft':
                return 'bg-gray-100 text-gray-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    };

    const getStatusIcon = (status: string) => {
        switch (status) {
            case 'fully_paid':
                return '✅';
            case 'overdue':
                return '⚠️';
            case 'partially_paid':
                return '🟡';
            case 'issued':
                return '📤';
            case 'draft':
                return '📝';
            default:
                return '📄';
        }
    };

    return (
        <AppShell>
            <Head title="Invoices - InvoiceFlow" />

            <div className="space-y-8">
                {/* Header */}
                <div className="flex justify-between items-center">
                    <div>
                        <h1 className="text-3xl font-bold text-gray-900">📄 Invoices</h1>
                        <p className="text-gray-600 mt-2">Manage your invoices and track payments</p>
                    </div>
                    <Link href="/invoices/create">
                        <Button>
                            ➕ Create Invoice
                        </Button>
                    </Link>
                </div>

                {/* Stats Cards */}
                <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div className="bg-white rounded-lg shadow p-6">
                        <div className="flex items-center">
                            <div className="p-3 rounded-full bg-blue-100">
                                <span className="text-xl">📊</span>
                            </div>
                            <div className="ml-4">
                                <p className="text-sm font-medium text-gray-600">Total Invoices</p>
                                <p className="text-2xl font-semibold text-gray-900">{stats.total_invoices}</p>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white rounded-lg shadow p-6">
                        <div className="flex items-center">
                            <div className="p-3 rounded-full bg-green-100">
                                <span className="text-xl">💰</span>
                            </div>
                            <div className="ml-4">
                                <p className="text-sm font-medium text-gray-600">Total Value</p>
                                <p className="text-2xl font-semibold text-gray-900">{formatCurrency(stats.total_amount)}</p>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white rounded-lg shadow p-6">
                        <div className="flex items-center">
                            <div className="p-3 rounded-full bg-yellow-100">
                                <span className="text-xl">⏱️</span>
                            </div>
                            <div className="ml-4">
                                <p className="text-sm font-medium text-gray-600">Outstanding</p>
                                <p className="text-2xl font-semibold text-gray-900">{formatCurrency(stats.outstanding_amount)}</p>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white rounded-lg shadow p-6">
                        <div className="flex items-center">
                            <div className="p-3 rounded-full bg-red-100">
                                <span className="text-xl">⚠️</span>
                            </div>
                            <div className="ml-4">
                                <p className="text-sm font-medium text-gray-600">Overdue</p>
                                <p className="text-2xl font-semibold text-gray-900">{stats.overdue_count}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Invoices Table */}
                <div className="bg-white rounded-lg shadow">
                    <div className="px-6 py-4 border-b border-gray-200">
                        <h3 className="text-lg font-medium text-gray-900">Invoice List</h3>
                    </div>
                    
                    {invoices.data.length > 0 ? (
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Invoice
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Customer
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Due Date
                                        </th>
                                        <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {invoices.data.map((invoice) => (
                                        <tr key={invoice.id} className="hover:bg-gray-50">
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div className="text-sm font-medium text-gray-900">
                                                        {invoice.invoice_number}
                                                    </div>
                                                    <div className="text-sm text-gray-500">
                                                        {new Date(invoice.issue_date).toLocaleDateString()}
                                                    </div>
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div className="text-sm font-medium text-gray-900">
                                                        {invoice.customer.name}
                                                    </div>
                                                    <div className="text-sm text-gray-500">
                                                        {invoice.customer.email}
                                                    </div>
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusColor(invoice.status)}`}>
                                                    <span className="mr-1">{getStatusIcon(invoice.status)}</span>
                                                    {invoice.status.replace('_', ' ').toUpperCase()}
                                                </span>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div className="text-sm font-medium text-gray-900">
                                                        {formatCurrency(invoice.total_amount)}
                                                    </div>
                                                    {invoice.balance_due > 0 && (
                                                        <div className="text-sm text-red-600">
                                                            Balance: {formatCurrency(invoice.balance_due)}
                                                        </div>
                                                    )}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {new Date(invoice.due_date).toLocaleDateString()}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                                <Link href={`/invoices/${invoice.id}`}>
                                                    <Button variant="outline" size="sm">
                                                        View
                                                    </Button>
                                                </Link>
                                                {invoice.status === 'draft' && (
                                                    <Link href={`/invoices/${invoice.id}/edit`}>
                                                        <Button variant="outline" size="sm">
                                                            Edit
                                                        </Button>
                                                    </Link>
                                                )}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    ) : (
                        <div className="text-center py-12">
                            <span className="text-6xl mb-4 block">📄</span>
                            <h3 className="text-lg font-medium text-gray-900 mb-2">No invoices yet</h3>
                            <p className="text-gray-600 mb-6">Create your first invoice to get started</p>
                            <Link href="/invoices/create">
                                <Button>
                                    ➕ Create Your First Invoice
                                </Button>
                            </Link>
                        </div>
                    )}
                </div>
            </div>
        </AppShell>
    );
}