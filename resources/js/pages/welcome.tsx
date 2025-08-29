import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';

export default function Welcome() {
    return (
        <>
            <Head title="Invoice Management System" />
            
            <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
                {/* Navigation */}
                <nav className="bg-white shadow-sm border-b">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex justify-between items-center h-16">
                            <div className="flex items-center space-x-2">
                                <span className="text-2xl">💰</span>
                                <span className="font-bold text-xl text-gray-900">InvoiceFlow</span>
                            </div>
                            <div className="flex items-center space-x-4">
                                <Link href="/login">
                                    <Button variant="outline" size="sm">
                                        Sign In
                                    </Button>
                                </Link>
                                <Link href="/register">
                                    <Button size="sm">
                                        Get Started
                                    </Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </nav>

                {/* Hero Section */}
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                    <div className="text-center">
                        <div className="mb-8">
                            <span className="text-6xl mb-4 block">📊</span>
                            <h1 className="text-5xl font-bold text-gray-900 mb-6">
                                Professional Invoice Management
                            </h1>
                            <p className="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                                Streamline your billing process with our comprehensive invoice management system. 
                                Create invoices, track payments, manage customers, and get paid faster with integrated ClickPesa payments.
                            </p>
                        </div>

                        <div className="flex flex-col sm:flex-row gap-4 justify-center mb-16">
                            <Link href="/register">
                                <Button size="lg" className="px-8 py-3">
                                    🚀 Start Free Trial
                                </Button>
                            </Link>
                            <Link href="/login">
                                <Button variant="outline" size="lg" className="px-8 py-3">
                                    👤 Sign In
                                </Button>
                            </Link>
                        </div>

                        {/* Feature Preview */}
                        <div className="bg-white rounded-lg shadow-xl p-8 mb-16">
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                                <div className="text-center">
                                    <div className="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span className="text-2xl">📄</span>
                                    </div>
                                    <h3 className="font-semibold text-lg mb-2">Smart Invoicing</h3>
                                    <p className="text-gray-600 text-sm">
                                        Professional invoices with automated calculations, tax handling, and recurring billing
                                    </p>
                                </div>
                                <div className="text-center">
                                    <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span className="text-2xl">💳</span>
                                    </div>
                                    <h3 className="font-semibold text-lg mb-2">ClickPesa Integration</h3>
                                    <p className="text-gray-600 text-sm">
                                        Accept mobile money and card payments with automatic reconciliation
                                    </p>
                                </div>
                                <div className="text-center">
                                    <div className="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span className="text-2xl">📈</span>
                                    </div>
                                    <h3 className="font-semibold text-lg mb-2">Advanced Reporting</h3>
                                    <p className="text-gray-600 text-sm">
                                        Comprehensive dashboards and reports to track your business performance
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Key Features */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                        <div className="bg-white rounded-lg shadow-md p-6">
                            <div className="flex items-center mb-4">
                                <span className="text-2xl mr-3">👥</span>
                                <h3 className="font-semibold text-lg">Customer Management</h3>
                            </div>
                            <ul className="text-sm text-gray-600 space-y-2">
                                <li>• Complete customer profiles</li>
                                <li>• Billing & shipping addresses</li>
                                <li>• Payment history tracking</li>
                                <li>• Account status management</li>
                            </ul>
                        </div>

                        <div className="bg-white rounded-lg shadow-md p-6">
                            <div className="flex items-center mb-4">
                                <span className="text-2xl mr-3">📦</span>
                                <h3 className="font-semibold text-lg">Product Catalog</h3>
                            </div>
                            <ul className="text-sm text-gray-600 space-y-2">
                                <li>• Products & services management</li>
                                <li>• Automated tax calculations</li>
                                <li>• SKU & inventory tracking</li>
                                <li>• Flexible pricing options</li>
                            </ul>
                        </div>

                        <div className="bg-white rounded-lg shadow-md p-6">
                            <div className="flex items-center mb-4">
                                <span className="text-2xl mr-3">🔄</span>
                                <h3 className="font-semibold text-lg">Recurring Billing</h3>
                            </div>
                            <ul className="text-sm text-gray-600 space-y-2">
                                <li>• Monthly, quarterly, yearly billing</li>
                                <li>• Automatic invoice generation</li>
                                <li>• Subscription management</li>
                                <li>• Payment reminders</li>
                            </ul>
                        </div>

                        <div className="bg-white rounded-lg shadow-md p-6">
                            <div className="flex items-center mb-4">
                                <span className="text-2xl mr-3">💰</span>
                                <h3 className="font-semibold text-lg">Payment Processing</h3>
                            </div>
                            <ul className="text-sm text-gray-600 space-y-2">
                                <li>• ClickPesa integration</li>
                                <li>• Mobile money & card payments</li>
                                <li>• Automatic reconciliation</li>
                                <li>• Payment status tracking</li>
                            </ul>
                        </div>

                        <div className="bg-white rounded-lg shadow-md p-6">
                            <div className="flex items-center mb-4">
                                <span className="text-2xl mr-3">⚠️</span>
                                <h3 className="font-semibold text-lg">Smart Reminders</h3>
                            </div>
                            <ul className="text-sm text-gray-600 space-y-2">
                                <li>• Automated dunning process</li>
                                <li>• SMS & email notifications</li>
                                <li>• Escalation workflows</li>
                                <li>• Collection history</li>
                            </ul>
                        </div>

                        <div className="bg-white rounded-lg shadow-md p-6">
                            <div className="flex items-center mb-4">
                                <span className="text-2xl mr-3">📊</span>
                                <h3 className="font-semibold text-lg">Financial Insights</h3>
                            </div>
                            <ul className="text-sm text-gray-600 space-y-2">
                                <li>• Revenue & cash flow reports</li>
                                <li>• Aging receivables analysis</li>
                                <li>• Customer payment behavior</li>
                                <li>• Multi-currency support</li>
                            </ul>
                        </div>
                    </div>

                    {/* Stats Section */}
                    <div className="bg-white rounded-lg shadow-xl p-8 mb-16">
                        <h2 className="text-3xl font-bold text-center mb-8">Trusted by Growing Businesses</h2>
                        <div className="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                            <div>
                                <div className="text-3xl font-bold text-blue-600 mb-2">99.9%</div>
                                <div className="text-sm text-gray-600">Uptime Guarantee</div>
                            </div>
                            <div>
                                <div className="text-3xl font-bold text-green-600 mb-2">24/7</div>
                                <div className="text-sm text-gray-600">Customer Support</div>
                            </div>
                            <div>
                                <div className="text-3xl font-bold text-purple-600 mb-2">256-bit</div>
                                <div className="text-sm text-gray-600">SSL Encryption</div>
                            </div>
                            <div>
                                <div className="text-3xl font-bold text-orange-600 mb-2">30-day</div>
                                <div className="text-sm text-gray-600">Free Trial</div>
                            </div>
                        </div>
                    </div>

                    {/* CTA Section */}
                    <div className="text-center bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg text-white p-12">
                        <h2 className="text-3xl font-bold mb-4">Ready to Transform Your Billing?</h2>
                        <p className="text-xl mb-8 opacity-90">
                            Join thousands of businesses that have streamlined their invoicing with InvoiceFlow
                        </p>
                        <div className="flex flex-col sm:flex-row gap-4 justify-center">
                            <Link href="/register">
                                <Button size="lg" variant="secondary" className="px-8 py-3">
                                    🎯 Start Your Free Trial
                                </Button>
                            </Link>
                            <Link href="/login">
                                <Button size="lg" variant="outline" className="px-8 py-3 text-white border-white hover:bg-white hover:text-purple-600">
                                    📋 View Demo
                                </Button>
                            </Link>
                        </div>
                    </div>
                </div>

                {/* Footer */}
                <footer className="bg-gray-900 text-white py-12">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center">
                            <div className="flex items-center justify-center space-x-2 mb-4">
                                <span className="text-2xl">💰</span>
                                <span className="font-bold text-xl">InvoiceFlow</span>
                            </div>
                            <p className="text-gray-400 mb-4">
                                Professional invoice management for growing businesses
                            </p>
                            <div className="flex justify-center space-x-6 text-sm">
                                <a href="#" className="hover:text-blue-400">Privacy Policy</a>
                                <a href="#" className="hover:text-blue-400">Terms of Service</a>
                                <a href="#" className="hover:text-blue-400">Support</a>
                                <a href="#" className="hover:text-blue-400">Contact</a>
                            </div>
                            <p className="text-gray-500 text-xs mt-6">
                                © 2024 InvoiceFlow. All rights reserved.
                            </p>
                        </div>
                    </div>
                </footer>
            </div>
        </>
    );
}