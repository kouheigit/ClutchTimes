import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import axios from '../api/client';

const ReservationList = () => {
    const [reservations, setReservations] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        fetchReservations();
    }, []);

    const fetchReservations = async () => {
        try {
            setLoading(true);
            const response = await axios.get('/reservations');
            setReservations(response.data.data || []);
            setError(null);
        } catch (err) {
            console.error('Error fetching reservations:', err);
            setError('予約情報の取得に失敗しました');
        } finally {
            setLoading(false);
        }
    };

    const getStatusColor = (status) => {
        const colors = {
            1: 'bg-yellow-100 text-yellow-800',
            2: 'bg-blue-100 text-blue-800',
            3: 'bg-green-100 text-green-800',
            9: 'bg-red-100 text-red-800',
        };
        return colors[status] || 'bg-gray-100 text-gray-800';
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

    return (
        <div className="px-4 py-6 sm:px-0">
            <div className="mb-6">
                <h2 className="text-2xl font-bold text-gray-900">予約一覧</h2>
            </div>

            {reservations.length === 0 ? (
                <div className="bg-white shadow rounded-lg p-6 text-center text-gray-500">
                    予約がありません
                </div>
            ) : (
                <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    {reservations.map(reservation => (
                        <div
                            key={reservation.id}
                            className="bg-white shadow rounded-lg overflow-hidden hover:shadow-lg transition-shadow"
                        >
                            <div className="p-6">
                                <div className="flex justify-between items-start mb-4">
                                    <h3 className="text-lg font-semibold text-gray-900">
                                        {reservation.hotel?.name || 'ホテル名不明'}
                                    </h3>
                                    <span
                                        className={`px-2 py-1 text-xs font-semibold rounded-full ${getStatusColor(
                                            reservation.status
                                        )}`}
                                    >
                                        {reservation.status_text}
                                    </span>
                                </div>

                                <div className="space-y-2 text-sm text-gray-600">
                                    <p>
                                        <span className="font-medium">チェックイン:</span>{' '}
                                        {reservation.checkin_date}
                                    </p>
                                    <p>
                                        <span className="font-medium">チェックアウト:</span>{' '}
                                        {reservation.checkout_date}
                                    </p>
                                    <p>
                                        <span className="font-medium">宿泊日数:</span>{' '}
                                        {reservation.days}泊
                                    </p>
                                    <p>
                                        <span className="font-medium">宿泊人数:</span>{' '}
                                        大人{reservation.guests?.adult || 0}名
                                        {reservation.guests?.child > 0 && `、子供${reservation.guests.child}名`}
                                        {reservation.guests?.dog > 0 && `、犬${reservation.guests.dog}頭`}
                                    </p>
                                    <p>
                                        <span className="font-medium">支払い方法:</span>{' '}
                                        {reservation.payment_text}
                                    </p>
                                    {reservation.total_price > 0 && (
                                        <p>
                                            <span className="font-medium">合計金額:</span>{' '}
                                            ¥{reservation.total_price?.toLocaleString()}
                                        </p>
                                    )}
                                </div>

                                <div className="mt-4">
                                    <Link
                                        to={`/reservations/${reservation.id}`}
                                        className="inline-block w-full text-center bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition-colors"
                                    >
                                        詳細を見る
                                    </Link>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
};

export default ReservationList;

