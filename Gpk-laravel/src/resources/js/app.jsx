import React from 'react';
import { Routes, Route } from 'react-router-dom';
import Layout from './components/Layout';
import ReservationList from './components/ReservationList';
import ReservationDetail from './components/ReservationDetail';
import ServiceList from './components/ServiceList';
import ServiceDetail from './components/ServiceDetail';
import Cart from './components/Cart';
import CartCheckout from './components/CartCheckout';
import CartComplete from './components/CartComplete';
import Mypage from './components/Mypage';

function App() {
    return (
        <Layout>
            <Routes>
                <Route path="/" element={<ReservationList />} />
                <Route path="/reservations" element={<ReservationList />} />
                <Route path="/reservations/:id" element={<ReservationDetail />} />
                <Route path="/services" element={<ServiceList />} />
                <Route path="/services/:id" element={<ServiceDetail />} />
                <Route path="/cart" element={<Cart />} />
                <Route path="/cart/checkout" element={<CartCheckout />} />
                <Route path="/cart/complete" element={<CartComplete />} />
                <Route path="/mypage" element={<Mypage />} />
            </Routes>
        </Layout>
    );
}

export default App;
