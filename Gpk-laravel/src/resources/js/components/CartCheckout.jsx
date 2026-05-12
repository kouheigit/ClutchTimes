import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from '../api/client';

const CartCheckout = () => {
    const navigate = useNavigate();
    const [cart, setCart] = useState(null);
    const [reservationId, setReservationId] = useState('');
    const [payment, setPayment] = useState(0);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [checkingOut, setCheckingOut] = useState(false);

    useEffect(() => {
        fetchCart();
    }, []);

    const fetchCart = async () => {
        try {
            setLoading(true);
            const response = await axios.get('/cart');
            setCart(response.data.data || response.data);
            setError(null);
        } catch (err) {
            console.error('Error fetching cart:', err);
            setError('カート情報の取得に失敗しました');
        } finally {
            setLoading(false);
        }
    };

    const handleCheckout = async () => {
        if (!cart || !cart.cart_details || cart.cart_details.length === 0) {
            alert('カートが空です');
            return;
        }

        try {
            setCheckingOut(true);
            await axios.post('/cart/checkout', {
                reservation_id: reservationId || null,
                payment: payment,
            });
            alert('注文が完了しました');
            navigate('/cart/complete');
        } catch (err) {
            console.error('Error checking out:', err);
            alert('注文に失敗しました');
        } finally {
            setCheckingOut(false);
        }
    };

    if (loading) {
        return (
            <div className="flex justify-center items-center h-64">
                <div className="text-gray-600">読み込み中...</div>
            </div>
        );
    }

    if (error || !cart || !cart.cart_details || cart.cart_details.length === 0) {
        return (
            <div className="px-4 py-6 sm:px-0">
                <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {error || 'カートが空です'}
                </div>
            </div>
        );
    }

    return (
        <div className="px-4 py-6 sm:px-0">
            <div className="mb-6">
                <h2 className="text-2xl font-bold text-gray-900">注文確認</h2>
            </div>

            <div className="bg-white shadow rounded-lg overflow-hidden">
                <div className="p-6">
                    <div className="mb-6">
                        <h3 className="text-lg font-semibold text-gray-900 mb-4">注文内容</h3>
                        <div className="space-y-4">
                            {cart.cart_details.map(item => (
                                <div key={item.id} className="border-b pb-4">
                                    <div className="flex justify-between">
                                        <div>
                                            <p className="font-medium text-gray-900">
                                                {item.service?.title || 'サービス名不明'}
                                            </p>
                                            {item.service_option && (
                                                <p className="text-sm text-gray-500">
                                                    オプション: {item.service_option.title}
                                                </p>
                                            )}
                                            <p className="text-sm text-gray-600">
                                                数量: {item.quantity} × ¥{item.price?.toLocaleString()}
                                            </p>
                                        </div>
                                        <span className="text-lg font-semibold text-gray-900">
                                            ¥{item.total_price?.toLocaleString()}
                                        </span>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    <div className="mb-6">
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                            予約ID（任意）
                        </label>
                        <input
                            type="text"
                            value={reservationId}
                            onChange={(e) => setReservationId(e.target.value)}
                            placeholder="予約IDを入力"
                            className="border border-gray-300 rounded px-3 py-2 w-full"
                        />
                    </div>

                    <div className="mb-6">
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                            支払い方法
                        </label>
                        <div className="space-y-2">
                            <label className="flex items-center">
                                <input
                                    type="radio"
                                    name="payment"
                                    value="0"
                                    checked={payment === 0}
                                    onChange={() => setPayment(0)}
                                    className="mr-2"
                                />
                                <span>現地払い</span>
                            </label>
                            <label className="flex items-center">
                                <input
                                    type="radio"
                                    name="payment"
                                    value="1"
                                    checked={payment === 1}
                                    onChange={() => setPayment(1)}
                                    className="mr-2"
                                />
                                <span>クレジットカード</span>
                            </label>
                        </div>
                    </div>

                    <div className="mb-6 p-4 bg-gray-50 rounded">
                        <div className="flex justify-between items-center">
                            <span className="text-lg font-semibold text-gray-900">合計金額</span>
                            <span className="text-2xl font-bold text-indigo-600">
                                ¥{cart.total_price?.toLocaleString()}
                            </span>
                        </div>
                    </div>

                    <div className="flex space-x-4">
                        <button
                            onClick={() => navigate('/cart')}
                            className="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 transition-colors"
                        >
                            カートに戻る
                        </button>
                        <button
                            onClick={handleCheckout}
                            disabled={checkingOut}
                            className="flex-1 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
                        >
                            {checkingOut ? '注文中...' : '注文を確定'}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default CartCheckout;

