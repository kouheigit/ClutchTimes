import axios from 'axios';

// CSRFトークンを取得
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

// Sanctumトークンを取得して設定
const sanctumToken = localStorage.getItem('sanctum_token');
if (sanctumToken) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${sanctumToken}`;
}

// ベースURL設定
axios.defaults.baseURL = '/api';

// レスポンスインターセプター（認証エラー時の処理）
axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            // 認証エラーの場合、ログインページにリダイレクト
            localStorage.removeItem('sanctum_token');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

export default axios;

