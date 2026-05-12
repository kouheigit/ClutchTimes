import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import axios from '../api/client';

const ServiceList = () => {
    const [services, setServices] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        fetchServices();
    }, []);

    const fetchServices = async () => {
        try {
            setLoading(true);
            const response = await axios.get('/services');
            setServices(response.data.data || []);
            setError(null);
        } catch (err) {
            console.error('Error fetching services:', err);
            setError('サービス情報の取得に失敗しました');
        } finally {
            setLoading(false);
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

    return (
        <div className="px-4 py-6 sm:px-0">
            <div className="mb-6">
                <h2 className="text-2xl font-bold text-gray-900">サービス一覧</h2>
            </div>

            {services.length === 0 ? (
                <div className="bg-white shadow rounded-lg p-6 text-center text-gray-500">
                    サービスがありません
                </div>
            ) : (
                <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    {services.map(service => (
                        <div
                            key={service.id}
                            className="bg-white shadow rounded-lg overflow-hidden hover:shadow-lg transition-shadow"
                        >
                            {service.image && (
                                <img
                                    src={service.image}
                                    alt={service.title}
                                    className="w-full h-48 object-cover"
                                />
                            )}
                            <div className="p-6">
                                <h3 className="text-lg font-semibold text-gray-900 mb-2">
                                    {service.title}
                                </h3>
                                <p className="text-sm text-gray-600 mb-4 line-clamp-3">
                                    {service.body}
                                </p>
                                <div className="flex justify-between items-center">
                                    <span className="text-xl font-bold text-indigo-600">
                                        ¥{service.price?.toLocaleString()}
                                    </span>
                                    <span className="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded">
                                        {service.tab_text}
                                    </span>
                                </div>
                                <div className="mt-4">
                                    <Link
                                        to={`/services/${service.id}`}
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

export default ServiceList;

