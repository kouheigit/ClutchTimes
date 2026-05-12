import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import axios from '../api/client';

const ServiceDetail = () => {
    const { id } = useParams();
    const navigate = useNavigate();
    const [service, setService] = useState(null);
    const [selectedOption, setSelectedOption] = useState(null);
    const [quantity, setQuantity] = useState(1);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [adding, setAdding] = useState(false);

    useEffect(() => {
        fetchService();
    }, [id]);

    const fetchService = async () => {
        try {
            setLoading(true);
            const response = await axios.get(`/services/${id}`);
            setService(response.data.data || response.data);
            setError(null);
        } catch (err) {
            console.error('Error fetching service:', err);
            setError('サービス情報の取得に失敗しました');
        } finally {
            setLoading(false);
        }
    };

    const handleAddToCart = async () => {
        if (!service) return;

        if (quantity < service.minimum) {
            alert(`最小注文数は${service.minimum}${service.unit}です`);
            return;
        }

        if (service.stock > 0 && quantity > service.stock) {
            alert('在庫が不足しています');
            return;
        }

        try {
            setAdding(true);
            await axios.post('/cart/add', {
                service_id: service.id,
                service_option_id: selectedOption,
                quantity: quantity,
            });
            alert('カートに追加しました');
            navigate('/cart');
        } catch (err) {
            console.error('Error adding to cart:', err);
            alert('カートへの追加に失敗しました');
        } finally {
            setAdding(false);
        }
    };

    if (loading) {
        return (
            <div className="flex justify-center items-center h-64">
                <div className="text-gray-600">読み込み中...</div>
            </div>
        );
    }

    if (error || !service) {
        return (
            <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {error || 'サービス情報が見つかりません'}
            </div>
        );
    }

    const totalPrice = (service.price + (selectedOption ? service.service_options?.find(opt => opt.id === selectedOption)?.price || 0 : 0)) * quantity;

    return (
        <div className="px-4 py-6 sm:px-0">
            <div className="mb-6">
                <button
                    onClick={() => navigate('/services')}
                    className="text-indigo-600 hover:text-indigo-800 mb-4"
                >
                    ← サービス一覧に戻る
                </button>
                <h2 className="text-2xl font-bold text-gray-900">{service.title}</h2>
            </div>

            <div className="bg-white shadow rounded-lg overflow-hidden">
                {service.image && (
                    <img
                        src={service.image}
                        alt={service.title}
                        className="w-full h-64 object-cover"
                    />
                )}
                <div className="p-6">
                    <div className="mb-4">
                        <h3 className="text-lg font-semibold text-gray-900 mb-2">説明</h3>
                        <p className="text-gray-600 whitespace-pre-wrap">{service.body}</p>
                    </div>

                    <div className="mb-4">
                        <h3 className="text-lg font-semibold text-gray-900 mb-2">価格</h3>
                        <p className="text-2xl font-bold text-indigo-600">
                            ¥{service.price?.toLocaleString()}
                        </p>
                    </div>

                    {service.service_options && service.service_options.length > 0 && (
                        <div className="mb-4">
                            <h3 className="text-lg font-semibold text-gray-900 mb-2">オプション</h3>
                            <div className="space-y-2">
                                {service.service_options.map(option => (
                                    <label key={option.id} className="flex items-center">
                                        <input
                                            type="radio"
                                            name="option"
                                            value={option.id}
                                            checked={selectedOption === option.id}
                                            onChange={() => setSelectedOption(option.id)}
                                            className="mr-2"
                                        />
                                        <span className="text-gray-700">
                                            {option.title}
                                            {option.price > 0 && ` (+¥${option.price.toLocaleString()})`}
                                        </span>
                                    </label>
                                ))}
                            </div>
                        </div>
                    )}

                    <div className="mb-4">
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                            数量 ({service.minimum}{service.unit}以上)
                        </label>
                        <input
                            type="number"
                            min={service.minimum}
                            max={service.stock > 0 ? service.stock : undefined}
                            value={quantity}
                            onChange={(e) => setQuantity(parseInt(e.target.value) || service.minimum)}
                            className="border border-gray-300 rounded px-3 py-2 w-24"
                        />
                        {service.stock > 0 && (
                            <p className="text-sm text-gray-500 mt-1">
                                在庫: {service.stock}{service.unit}
                            </p>
                        )}
                    </div>

                    <div className="mb-4 p-4 bg-gray-50 rounded">
                        <div className="flex justify-between items-center">
                            <span className="text-lg font-semibold text-gray-900">合計</span>
                            <span className="text-2xl font-bold text-indigo-600">
                                ¥{totalPrice.toLocaleString()}
                            </span>
                        </div>
                    </div>

                    <button
                        onClick={handleAddToCart}
                        disabled={adding || (service.stock > 0 && quantity > service.stock)}
                        className="w-full bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
                    >
                        {adding ? '追加中...' : 'カートに追加'}
                    </button>
                </div>
            </div>
        </div>
    );
};

export default ServiceDetail;

