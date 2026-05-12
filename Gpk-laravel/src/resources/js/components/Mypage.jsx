import React, { useEffect, useState } from 'react';
import axios from '../api/client';

const Mypage = () => {
    const [user, setUser] = useState(null);
    const [points, setPoints] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        fetchUserData();
        fetchPoints();
    }, []);

    const fetchUserData = async () => {
        try {
            const response = await axios.get('/user');
            setUser(response.data.data || response.data);
        } catch (err) {
            console.error('Error fetching user:', err);
            setError('ユーザー情報の取得に失敗しました');
        } finally {
            setLoading(false);
        }
    };

    const fetchPoints = async () => {
        try {
            const response = await axios.get('/points');
            setPoints(response.data);
        } catch (err) {
            console.error('Error fetching points:', err);
        }
    };

    if (loading) {
        return (
            <div className="flex justify-center items-center h-64">
                <div className="text-gray-600">読み込み中...</div>
            </div>
        );
    }

    if (error || !user) {
        return (
            <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {error || 'ユーザー情報が見つかりません'}
            </div>
        );
    }

    return (
        <div className="px-4 py-6 sm:px-0">
            <div className="mb-6">
                <h2 className="text-2xl font-bold text-gray-900">マイページ</h2>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="bg-white shadow rounded-lg p-6">
                    <h3 className="text-lg font-semibold text-gray-900 mb-4">ユーザー情報</h3>
                    <dl className="space-y-2">
                        <div>
                            <dt className="text-sm font-medium text-gray-500">名前</dt>
                            <dd className="text-sm text-gray-900">{user.name}</dd>
                        </div>
                        <div>
                            <dt className="text-sm font-medium text-gray-500">メールアドレス</dt>
                            <dd className="text-sm text-gray-900">{user.email}</dd>
                        </div>
                        <div>
                            <dt className="text-sm font-medium text-gray-500">会員ID</dt>
                            <dd className="text-sm text-gray-900">{user.member_id || '-'}</dd>
                        </div>
                    </dl>
                </div>

                {points && (
                    <div className="bg-white shadow rounded-lg p-6">
                        <h3 className="text-lg font-semibold text-gray-900 mb-4">ポイント残高</h3>
                        <div className="text-3xl font-bold text-indigo-600 mb-4">
                            {points.total_points?.toLocaleString()}pt
                        </div>
                        {points.balance_by_expiry && points.balance_by_expiry.length > 0 && (
                            <div className="mt-4">
                                <h4 className="text-sm font-medium text-gray-700 mb-2">有効期限別</h4>
                                <div className="space-y-2">
                                    {points.balance_by_expiry.map((balance, index) => (
                                        <div key={index} className="text-sm text-gray-600">
                                            {balance.point}pt (期限: {balance.to})
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                )}
            </div>
        </div>
    );
};

export default Mypage;

