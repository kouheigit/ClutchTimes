import React from 'react';
import { Link } from 'react-router-dom';

const CartComplete = () => {
    return (
        <div className="px-4 py-6 sm:px-0">
            <div className="bg-white shadow rounded-lg p-6 text-center">
                <div className="mb-4">
                    <svg
                        className="mx-auto h-16 w-16 text-green-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth={2}
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </div>
                <h2 className="text-2xl font-bold text-gray-900 mb-4">注文が完了しました</h2>
                <p className="text-gray-600 mb-6">
                    ご注文ありがとうございました。注文内容はマイページから確認できます。
                </p>
                <div className="flex justify-center space-x-4">
                    <Link
                        to="/reservations"
                        className="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition-colors"
                    >
                        予約一覧へ
                    </Link>
                    <Link
                        to="/services"
                        className="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400 transition-colors"
                    >
                        サービス一覧へ
                    </Link>
                </div>
            </div>
        </div>
    );
};

export default CartComplete;

