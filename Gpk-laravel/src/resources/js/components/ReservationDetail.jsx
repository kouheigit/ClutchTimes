import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import axios from '../api/client';

const ReservationDetail = () => {
    const { id } = useParams();
    const navigate = useNavigate();
    const [reservation, setReservation] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        fetchReservation();
    }, [id]);

    const fetchReservation = async () => {
        try {
            setLoading(true);
            const response = await axios.get(`/reservations/${id}`);
            setReservation(response.data.data || response.data);
            setError(null);
        } catch (err) {
            console.error('Error fetching reservation:', err);
            setError('予約情報の取得に失敗しました');
        } finally {
            setLoading(false);
        }
    };

    const handleCancel = async () => {
        if (!window.confirm('本当に予約をキャンセルしますか？')) {
            return;
        }

        try {
            await axios.post(`/reservations/${id}/cancel`);
            alert('予約をキャンセルしました');
            navigate('/reservations');
        } catch (err) {
            console.error('Error canceling reservation:', err);
            alert('キャンセルに失敗しました');
        }
    };

    if (loading) {
        return (
            <div className="flex justify-center items-center h-64">
                <div className="text-gray-600">読み込み中...</div>
            </div>
        );
    }

    if (error || !reservation) {
        return (
            <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {error || '予約情報が見つかりません'}
            </div>
        );
    }

    return (
        <div className="px-4 py-6 sm:px-0">
            <div className="mb-6">
                <button
                    onClick={() => navigate('/reservations')}
                    className="text-indigo-600 hover:text-indigo-800 mb-4"
                >
                    ← 予約一覧に戻る
                </button>
                <h2 className="text-2xl font-bold text-gray-900">予約詳細</h2>
            </div>

            <div className="bg-white shadow rounded-lg overflow-hidden">
                <div className="p-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 className="text-lg font-semibold text-gray-900 mb-4">基本情報</h3>
                            <dl className="space-y-2">
                                <div>
                                    <dt className="text-sm font-medium text-gray-500">ホテル名</dt>
                                    <dd className="text-sm text-gray-900">{reservation.hotel?.name || '不明'}</dd>
                                </div>
                                <div>
                                    <dt className="text-sm font-medium text-gray-500">チェックイン</dt>
                                    <dd className="text-sm text-gray-900">{reservation.checkin_date}</dd>
                                </div>
                                <div>
                                    <dt className="text-sm font-medium text-gray-500">チェックアウト</dt>
                                    <dd className="text-sm text-gray-900">{reservation.checkout_date}</dd>
                                </div>
                                <div>
                                    <dt className="text-sm font-medium text-gray-500">宿泊日数</dt>
                                    <dd className="text-sm text-gray-900">{reservation.days}泊</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 className="text-lg font-semibold text-gray-900 mb-4">宿泊者情報</h3>
                            <dl className="space-y-2">
                                <div>
                                    <dt className="text-sm font-medium text-gray-500">大人</dt>
                                    <dd className="text-sm text-gray-900">{reservation.guests?.adult || 0}名</dd>
                                </div>
                                <div>
                                    <dt className="text-sm font-medium text-gray-500">子供</dt>
                                    <dd className="text-sm text-gray-900">{reservation.guests?.child || 0}名</dd>
                                </div>
                                <div>
                                    <dt className="text-sm font-medium text-gray-500">犬</dt>
                                    <dd className="text-sm text-gray-900">{reservation.guests?.dog || 0}頭</dd>
                                </div>
                                <div>
                                    <dt className="text-sm font-medium text-gray-500">支払い方法</dt>
                                    <dd className="text-sm text-gray-900">{reservation.payment_text}</dd>
                                </div>
                                <div>
                                    <dt className="text-sm font-medium text-gray-500">ステータス</dt>
                                    <dd className="text-sm text-gray-900">{reservation.status_text}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    {reservation.orders && reservation.orders.length > 0 && (
                        <div className="mt-6">
                            <h3 className="text-lg font-semibold text-gray-900 mb-4">注文情報</h3>
                            <div className="space-y-4">
                                {reservation.orders.map(order => (
                                    <div key={order.id} className="border rounded p-4">
                                        <div className="flex justify-between">
                                            <span className="font-medium">{order.service?.title || 'サービス名不明'}</span>
                                            <span className="text-gray-600">
                                                ¥{order.total_price?.toLocaleString()}
                                            </span>
                                        </div>
                                        <div className="text-sm text-gray-500 mt-1">
                                            数量: {order.quantity} × ¥{order.price?.toLocaleString()}
                                        </div>
                                    </div>
                                ))}
                            </div>
                            {reservation.total_price > 0 && (
                                <div className="mt-4 text-right">
                                    <span className="text-lg font-bold text-gray-900">
                                        合計: ¥{reservation.total_price?.toLocaleString()}
                                    </span>
                                </div>
                            )}
                        </div>
                    )}

                    {reservation.status !== 9 && (
                        <div className="mt-6">
                            <button
                                onClick={handleCancel}
                                className="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition-colors"
                            >
                                予約をキャンセル
                            </button>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
};

export default ReservationDetail;

