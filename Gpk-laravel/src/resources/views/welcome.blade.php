<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'GLAMDAY STYLE') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap">

        <!-- Scripts -->
        <link rel="stylesheet" href="{{ asset('build/assets/app-BmNPS28k.css') }}">
        <script type="module" src="{{ asset('build/assets/app-DLzHMEKX.js') }}"></script>

        <style>
            body {
                margin: 0;
                padding: 0;
            }
            .header-section {
                background-color: #4a5568;
                color: white;
                padding: 1.5rem 2rem;
            }
            .header-content {
                max-width: 1200px;
                margin: 0 auto;
            }
            .logo-section {
                display: flex;
                align-items: center;
            }
            .gs-logo {
                font-size: 3rem;
                font-weight: bold;
                color: white;
                margin-right: 1rem;
                font-family: 'Times New Roman', serif;
                letter-spacing: -2px;
            }
            .glamday-style {
                font-size: 1.25rem;
                color: white;
                font-weight: 500;
                letter-spacing: 0.1em;
                font-family: 'Times New Roman', serif;
                text-transform: uppercase;
            }
            .owner-site-text {
                font-size: 0.75rem;
                color: white;
                margin-top: 0.25rem;
            }
            .nav-panels {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 0;
                margin-top: 0;
            }
            .nav-panel {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 2rem 1rem;
                text-decoration: none;
                transition: all 0.3s ease;
                border-right: 1px solid rgba(0, 0, 0, 0.1);
                cursor: pointer;
                background-color: #e2e8f0;
            }
            .nav-panel:last-child {
                border-right: none;
            }
            /* 通常状態（すべてのボタン） */
            .nav-panel svg {
                stroke: #4a5568;
                fill: none;
                stroke-width: 2;
                width: 32px;
                height: 32px;
                margin-bottom: 0.5rem;
                transition: stroke 0.3s ease;
            }
            .nav-panel span {
                color: #4a5568;
                font-size: 0.875rem;
                transition: color 0.3s ease;
            }
            /* ホバー効果（マウスオーバー時） */
            .nav-panel:hover {
                background-color: #5a6578;
            }
            .nav-panel:hover svg {
                stroke: white;
            }
            .nav-panel:hover span {
                color: white;
            }
            .main-content {
                background: 
                    linear-gradient(135deg, rgba(245, 245, 245, 0.9) 0%, rgba(235, 235, 235, 0.9) 100%),
                    repeating-linear-gradient(
                        45deg,
                        transparent,
                        transparent 10px,
                        rgba(255, 255, 255, 0.1) 10px,
                        rgba(255, 255, 255, 0.1) 20px
                    );
                min-height: calc(100vh - 200px);
                padding: 3rem 2rem;
            }
            .content-wrapper {
                max-width: 1200px;
                margin: 0 auto;
            }
            .contact-section {
                margin-bottom: 4rem;
            }
            .contact-title {
                font-family: 'Playfair Display', serif;
                font-size: 3rem;
                font-weight: 700;
                text-align: center;
                color: #2d3748;
                margin-bottom: 0.5rem;
            }
            .contact-subtitle {
                font-size: 1.25rem;
                text-align: center;
                color: #4a5568;
                margin-bottom: 1rem;
            }
            .contact-divider {
                width: 100px;
                height: 2px;
                background-color: #2d3748;
                margin: 0 auto 2rem;
            }
            .contact-buttons {
                display: flex;
                justify-content: center;
                gap: 1.5rem;
                margin-bottom: 2rem;
            }
            .contact-button {
                width: 120px;
                height: 120px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                border-radius: 0.5rem;
                text-decoration: none;
                color: white;
                font-weight: bold;
                transition: transform 0.2s;
            }
            .contact-button:hover {
                transform: scale(1.05);
            }
            .contact-button.line {
                background-color: #06c755;
            }
            .contact-button.mail {
                background-color: #4a5568;
            }
            .contact-button svg {
                width: 40px;
                height: 40px;
                margin-bottom: 0.5rem;
            }
            .reservation-hours {
                text-align: center;
                font-size: 0.875rem;
                color: #4a5568;
                margin-bottom: 0.5rem;
                font-weight: 500;
            }
            .disclaimer {
                text-align: center;
                font-size: 0.75rem;
                color: #718096;
                line-height: 1.6;
            }
            .links-section {
                position: relative;
            }
            .links-title {
                font-family: 'Playfair Display', serif;
                font-size: 3rem;
                font-weight: 700;
                text-align: center;
                color: #2d3748;
                margin-bottom: 2rem;
            }
            .owner-site-button {
                position: absolute;
                top: 0;
                right: 0;
                width: 200px;
                height: 200px;
                border-radius: 50%;
                background-color: #4a5568;
                color: white;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                font-weight: bold;
                font-size: 0.875rem;
                line-height: 1.4;
                text-decoration: none;
                transition: all 0.3s ease;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
                font-family: 'Noto Sans JP', sans-serif;
            }
            .owner-site-button:hover {
                background-color: #2d3748;
                transform: scale(1.05);
                box-shadow: 0 6px 8px rgba(0, 0, 0, 0.3);
            }
            @media (max-width: 768px) {
                .nav-panels {
                    grid-template-columns: repeat(2, 1fr);
                }
                .nav-panel {
                    border-right: none;
                    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                }
                .nav-panel:nth-child(2n) {
                    border-right: 1px solid rgba(0, 0, 0, 0.1);
                }
                .contact-title,
                .links-title {
                    font-size: 2rem;
                }
                .owner-site-button {
                    position: relative;
                    margin: 2rem auto 0;
                }
                .contact-buttons {
                    flex-direction: column;
                    align-items: center;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <!-- Header Section (Dark Grey) - GLAMDAY STYLE -->
        <header class="header-section">
            <div class="header-content">
                <div class="logo-section">
                    <span class="gs-logo">GS</span>
                    <div>
                        <div class="glamday-style">GLAMDAY STYLE</div>
                        <div style="font-size: 0.875rem; color: white; margin-top: 0.5rem; font-style: italic; font-family: 'Times New Roman', serif;">OFFICIAL OWNER'S SITE</div>
                        <div class="owner-site-text">オーナー様 専用サイト 総合ページ</div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Navigation Panels -->
        <nav class="nav-panels">
            <a href="#" class="nav-panel">
                <svg viewBox="0 0 24 24">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
                <span>トップ</span>
            </a>
            <a href="#" class="nav-panel">
                <svg viewBox="0 0 24 24">
                    <path d="M12.01 6c2.61 0 4.89 1.86 5.4 4.43l.3 1.5 1.52.11c1.56.11 2.78 1.41 2.78 2.96 0 1.65-1.35 3-3 3h-13c-2.21 0-4-1.79-4-4 0-2.05 1.53-3.76 3.56-3.97l1.07-.11.5-.95C8.08 7.14 9.95 6 12.01 6m0-2C9.12 4 6.6 5.64 5.35 8.04 2.35 8.36.01 10.91.01 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.64-4.96C18.68 6.59 15.65 4 12.01 4z"/>
                </svg>
                <span>今日の天気</span>
            </a>
            <a href="#" class="nav-panel">
                <svg viewBox="0 0 24 24">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                </svg>
                <span>周辺施設</span>
            </a>
            <a href="#" class="nav-panel">
                <svg viewBox="0 0 24 24">
                    <path d="M20 6h-2.18c.11-.31.18-.65.18-1 0-1.66-1.34-3-3-3-1.05 0-1.96.54-2.5 1.35l-.5.67-.5-.68C10.96 2.54 10.05 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-5-2c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM9 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm11 15H4v-2h16v2zm0-5H4V8h5.08L7 10.83 8.62 12 11 8.76l1-1.36 1 1.36L15.38 12 17 10.83 14.92 8H20v6z"/>
                </svg>
                <span>ギフト</span>
            </a>
        </nav>

        <!-- Splash Background Section -->
        <div class="splash-background" style="position: relative; min-height: 60vh; background: linear-gradient(135deg, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.6) 100%), url('https://images.unsplash.com/photo-1564501049412-61c2a3083791?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80') center/cover; display: flex; align-items: center; justify-content: center;">
            <a href="{{ route('login') }}" class="login-button" style="background-color: rgba(255, 255, 255, 0.9); color: #000; padding: 1rem 3rem; border-radius: 0.5rem; text-decoration: none; font-weight: bold; font-size: 1.125rem; transition: all 0.3s ease; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);">
                ログイン
            </a>
        </div>

        <!-- Main Content Area (Light Marble Texture) -->
        <main class="main-content">
            <div class="content-wrapper">
                <!-- CONTACT Section -->
                <section class="contact-section">
                    <h2 class="contact-title">CONTACT</h2>
                    <h3 class="contact-subtitle">お問い合わせ</h3>
                    <div class="contact-divider"></div>
                    
                    <div class="contact-buttons">
                        <a href="#" class="contact-button line">
                            <img src="{{ asset('images/icons/icon-line.svg') }}" alt="LINE" style="width: 40px; height: 40px; margin-bottom: 0.5rem;">
                            LINE
                        </a>
                        <a href="#" class="contact-button mail">
                            <svg fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                            </svg>
                            MAIL
                            </a>
                    </div>

                    <p class="reservation-hours">【ご予約の受付時間】 10:00~18:00</p>
                    <p class="disclaimer">内容によっては、お返事にお時間をいただく場合がございます。<br>あらかじめご了承くださいませ。</p>
                </section>
                
                <!-- LINKS Section -->
                <section class="links-section">
                    <h2 class="links-title">LINKS</h2>
                    <h3 style="text-align: center; font-size: 1.25rem; color: #4a5568; margin-bottom: 2rem;">関連リンク</h3>
                    <div class="contact-divider" style="margin-bottom: 3rem;"></div>
                    
                    <!-- EOC Image -->
                    <div style="text-align: center; margin-bottom: 3rem;">
                        <div style="width: 300px; height: 200px; margin: 0 auto; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.5rem;">
                            EOC
                        </div>
                    </div>
                    
                    <a href="{{ route('login') }}" class="owner-site-button">
                        <span style="display: block; margin-bottom: 0.25rem;">オーナー様</span>
                        <span style="display: block;">専用サイト</span>
                    </a>
                </section>
            </div>
        </main>
        
        <!-- Footer -->
        @include('partials.footer')
    </body>
</html>
