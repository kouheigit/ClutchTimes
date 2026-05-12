import React from 'react';
import { Link, useLocation } from 'react-router-dom';

const Layout = ({ children }) => {
    const location = useLocation();

    return (
        <div className="min-h-screen bg-gray-100">
            <nav className="bg-white shadow-lg">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-16">
                        <div className="flex">
                            <Link
                                to="/"
                                className="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900"
                            >
                                空ノ庭 予約システム
                            </Link>
                            <Link
                                to="/reservations"
                                className={`ml-8 inline-flex items-center px-1 pt-1 text-sm font-medium ${
                                    location.pathname.startsWith('/reservations')
                                        ? 'border-b-2 border-indigo-500 text-gray-900'
                                        : 'text-gray-500 hover:text-gray-700'
                                }`}
                            >
                                予約一覧
                            </Link>
                            <Link
                                to="/services"
                                className={`ml-8 inline-flex items-center px-1 pt-1 text-sm font-medium ${
                                    location.pathname.startsWith('/services')
                                        ? 'border-b-2 border-indigo-500 text-gray-900'
                                        : 'text-gray-500 hover:text-gray-700'
                                }`}
                            >
                                サービス
                            </Link>
                            <Link
                                to="/cart"
                                className={`ml-8 inline-flex items-center px-1 pt-1 text-sm font-medium ${
                                    location.pathname.startsWith('/cart')
                                        ? 'border-b-2 border-indigo-500 text-gray-900'
                                        : 'text-gray-500 hover:text-gray-700'
                                }`}
                            >
                                カート
                            </Link>
                            <Link
                                to="/mypage"
                                className={`ml-8 inline-flex items-center px-1 pt-1 text-sm font-medium ${
                                    location.pathname.startsWith('/mypage')
                                        ? 'border-b-2 border-indigo-500 text-gray-900'
                                        : 'text-gray-500 hover:text-gray-700'
                                }`}
                            >
                                マイページ
                            </Link>
                        </div>
                    </div>
                </div>
            </nav>

            <main className="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                {children}
            </main>
        </div>
    );
};

export default Layout;

