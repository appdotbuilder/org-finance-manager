import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';

interface Customer {
    id: number;
    name: string;
    email: string;
    phone?: string;
    company?: string;
    status: string;
    outstanding_balance: number;
    created_at: string;
    invoices: Array<{
        id: number;
        total_amount: number;
        balance_due: number;
        status: string;
    }>;
}

interface CustomerStats {
    total_customers: number;
    active_customers: number;
    total_outstanding: number;
}

interface PaginatedCustomers {
    data: Customer[];
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
    customers: PaginatedCustomers;
    stats: CustomerStats;
    [key: string]: unknown;
}

export default function CustomersIndex({ customers, stats }: Props) {
    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
        }).format(amount);
    };

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'active':
                return 'bg-green-100 text-green-800';
            case 'inactive':
                return 'bg-gray-100 text-gray-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    };

    return (
        <AppShell>
            <Head title="Customers - InvoiceFlow" />

            <div className="space-y-8">
                {/* Header */}
                <div className="flex justify-between items-center">
                    <div>
                        <h1 className="text-3xl font-bold text-gray-900">👥 Customers</h1>
                        <p className="text-gray-600 mt-2">Manage your customer relationships and accounts</p>
                    </div>
                    <Link href="/customers/create">
                        <Button>
                            ➕ Add Customer
                        </Button>
                    </Link>
                </div>

                {/* Stats Cards */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div className="bg-white rounded-lg shadow p-6">
                        <div className="flex items-center">
                            <div className="p-3 rounded-full bg-blue-100">
                                <span className="text-xl">👥</span>
                            </div>
                            <div className="ml-4">
                                <p className="text-sm font-medium text-gray-600">Total Customers</p>
                                <p className="text-2xl font-semibold text-gray-900">{stats.total_customers}</p>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white rounded-lg shadow p-6">
                        <div className="flex items-center">
                            <div className="p-3 rounded-full bg-green-100">
                                <span className="text-xl">✅</span>
                            </div>
                            <div className="ml-4">
                                <p className="text-sm font-medium text-gray-600">Active Customers</p>
                                <p className="text-2xl font-semibold text-gray-900">{stats.active_customers}</p>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white rounded-lg shadow p-6">
                        <div className="flex items-center">
                            <div className="p-3 rounded-full bg-yellow-100">
                                <span className="text-xl">💰</span>
                            </div>
                            <div className="ml-4">
                                <p className="text-sm font-medium text-gray-600">Total Outstanding</p>
                                <p className="text-2xl font-semibold text-gray-900">{formatCurrency(stats.total_outstanding)}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Customers Table */}
                <div className="bg-white rounded-lg shadow">
                    <div className="px-6 py-4 border-b border-gray-200">
                        <h3 className="text-lg font-medium text-gray-900">Customer List</h3>
                    </div>
                    
                    {customers.data.length > 0 ? (
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Customer
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Contact
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Outstanding
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Invoices
                                        </th>
                                        <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {customers.data.map((customer) => (
                                        <tr key={customer.id} className="hover:bg-gray-50">
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div className="text-sm font-medium text-gray-900">
                                                        {customer.name}
                                                    </div>
                                                    {customer.company && (
                                                        <div className="text-sm text-gray-500">
                                                            {customer.company}
                                                        </div>
                                                    )}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div className="text-sm text-gray-900">
                                                        {customer.email}
                                                    </div>
                                                    {customer.phone && (
                                                        <div className="text-sm text-gray-500">
                                                            {customer.phone}
                                                        </div>
                                                    )}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusColor(customer.status)}`}>
                                                    {customer.status.toUpperCase()}
                                                </span>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="text-sm font-medium text-gray-900">
                                                    {customer.outstanding_balance > 0 ? (
                                                        <span className="text-red-600">
                                                            {formatCurrency(customer.outstanding_balance)}
                                                        </span>
                                                    ) : (
                                                        <span className="text-green-600">
                                                            {formatCurrency(0)}
                                                        </span>
                                                    )}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="text-sm text-gray-900">
                                                    {customer.invoices.length} invoice{customer.invoices.length !== 1 ? 's' : ''}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                                <Link href={`/customers/${customer.id}`}>
                                                    <Button variant="outline" size="sm">
                                                        View
                                                    </Button>
                                                </Link>
                                                <Link href={`/customers/${customer.id}/edit`}>
                                                    <Button variant="outline" size="sm">
                                                        Edit
                                                    </Button>
                                                </Link>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    ) : (
                        <div className="text-center py-12">
                            <span className="text-6xl mb-4 block">👥</span>
                            <h3 className="text-lg font-medium text-gray-900 mb-2">No customers yet</h3>
                            <p className="text-gray-600 mb-6">Add your first customer to start creating invoices</p>
                            <Link href="/customers/create">
                                <Button>
                                    ➕ Add Your First Customer
                                </Button>
                            </Link>
                        </div>
                    )}
                </div>
            </div>
        </AppShell>
    );
}