import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import axios from '../api/client';

const Cart = () => {
    const [cart, setCart] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        fetchCart();
    }, []);

    const fetchCart = async () => {
        try {
            setLoading(true);
            const response = await axios.get('/cart');
            setCart(response.data);
            setError(null);
        } catch (err) {
            console.error('Error fetching cart:', err);
            setError('カート情報の取得に失敗しました');
        } finally {
            setLoading(false);
        }
    };

    const handleRemove = async (itemId) => {
        try {
            await axios.delete(`/cart/${itemId}`);
            fetchCart();
        } catch (err) {
            console.error('Error removing item:', err);
            alert('削除に失敗しました');
        }
    };

    if (loading) {
        return (
            <div className="flex justify-center items-center h-64">
                <div className="text-gray-600">読み込み中...</div>
            </div>
        );
    }

    if (error) {
        return (
            <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {error}
            </div>
        );
    }

    if (!cart || !cart.items || cart.items.length === 0) {
        return (
            <div className="px-4 py-6 sm:px-0">
                <div className="mb-6">
                    <h2 className="text-2xl font-bold text-gray-900">カート</h2>
                </div>
                <div className="bg-white shadow rounded-lg p-6 text-center text-gray-500">
                    カートが空です
                </div>
            </div>
        );
    }

    return (
        <div className="px-4 py-6 sm:px-0">
            <div className="mb-6">
                <h2 className="text-2xl font-bold text-gray-900">カート</h2>
            </div>

            <div className="bg-white shadow rounded-lg overflow-hidden">
                <div className="p-6">
                    <div className="space-y-4">
                        {cart.items.map(item => (
                            <div
                                key={item.id}
                                className="flex justify-between items-center border-b pb-4"
                            >
                                <div className="flex-1">
                                    <h3 className="font-medium text-gray-900">
                                        {item.service?.title || 'サービス名不明'}
                                    </h3>
                                    {item.service_option && (
                                        <p className="text-sm text-gray-500">
                                            オプション: {item.service_option.title}
                                        </p>
                                    )}
                                    <p className="text-sm text-gray-600">
                                        数量: {item.quantity} × ¥{item.price?.toLocaleString()}
                                    </p>
                                </div>
                                <div className="flex items-center space-x-4">
                                    <span className="text-lg font-semibold text-gray-900">
                                        ¥{item.total_price?.toLocaleString()}
                                    </span>
                                    <button
                                        onClick={() => handleRemove(item.id)}
                                        className="text-red-600 hover:text-red-800"
                                    >
                                        削除
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>

                    <div className="mt-6 pt-4 border-t">
                        <div className="flex justify-between items-center mb-4">
                            <span className="text-lg font-semibold text-gray-900">合計</span>
                            <span className="text-2xl font-bold text-indigo-600">
                                ¥{cart.total_price?.toLocaleString()}
                            </span>
                        </div>
                        <Link
                            to="/cart/checkout"
                            className="block w-full text-center bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition-colors"
                        >
                            注文に進む
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Cart;

