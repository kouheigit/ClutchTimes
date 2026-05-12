<nav x-data="{ open: false }" class="bg-gray-800 border-b border-gray-900">
    <!-- GLAMDAY STYLE Header -->
    <div class="bg-gray-800 text-white py-3 px-6">
        <div class="max-w-7xl mx-auto flex items-center">
            <span class="text-4xl font-serif font-bold mr-4" style="font-family: 'Times New Roman', serif; letter-spacing: -2px;">GS</span>
            <div>
                <div class="text-lg font-serif uppercase tracking-wide" style="font-family: 'Times New Roman', serif;">GLAMDAY STYLE</div>
                <div class="text-xs mt-0.5">オーナー様 専用サイト 総合ページ</div>
            </div>
        </div>
    </div>
    
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 bg-white">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('top') }}" class="text-xl font-serif font-bold text-gray-900 hover:text-gray-700" style="font-family: 'Times New Roman', serif;">
                        空ノ庭
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('top')" :active="request()->routeIs('top')">
                        トップ
                    </x-nav-link>
                    <x-nav-link :href="route('mypage.index')" :active="request()->routeIs('mypage.*')">
                        マイページ
                    </x-nav-link>
                    <x-nav-link :href="route('reservation.index')" :active="request()->routeIs('reservation.*')">
                        予約
                    </x-nav-link>
                    <x-nav-link :href="route('calendar.index')" :active="request()->routeIs('calendar.*')">
                        カレンダー
                    </x-nav-link>
                    <x-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">
                        サービス
                    </x-nav-link>
                    <x-nav-link :href="route('news.index')" :active="request()->routeIs('news.*')">
                        お知らせ
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- カートアイコン -->
                <a href="{{ route('cart.index') }}" class="relative mr-4 p-2 text-gray-500 hover:text-gray-700 focus:outline-none" style="display: inline-block; width: 40px; height: 40px; line-height: 40px; text-align: center;">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px; max-width: 20px; max-height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    @if(isset($cart_item_count) && $cart_item_count > 0)
                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full" style="min-width: 18px; height: 18px; font-size: 10px;">
                        {{ $cart_item_count }}
                    </span>
                    @endif
                </a>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('mypage.index')">
                            マイページ
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('mypage.edit')">
                            プロフィール編集
                        </x-dropdown-link>
                        
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('top')" :active="request()->routeIs('top')">
                トップ
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('mypage.index')" :active="request()->routeIs('mypage.*')">
                マイページ
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('reservation.index')" :active="request()->routeIs('reservation.*')">
                予約
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('calendar.index')" :active="request()->routeIs('calendar.*')">
                カレンダー
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">
                サービス
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('news.index')" :active="request()->routeIs('news.*')">
                お知らせ
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
                カート
                @if(isset($cart_item_count) && $cart_item_count > 0)
                <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                    {{ $cart_item_count }}
                </span>
                @endif
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('mypage.index')">
                    マイページ
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('mypage.edit')">
                    プロフィール編集
                </x-responsive-nav-link>
                
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
