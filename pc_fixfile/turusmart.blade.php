<!DOCTYPE>
 <html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>クリーニングWAVE 弦巻店</title>
    <meta name="description" content="弦巻のクリーニングならクリーニングWAVE 弦巻店にお任せ！あなたの衣服をもう一回輝かせるという二つの理念をモットーに、妥協をしないキレイな仕上げを心がけ開業から30年、弦巻のこだわりのあるクリーニング店として地域に愛されて営業してきました。">
    <meta name="keywords" content="クリーニング,弦巻">
    <link rel="canonical" href="https://cleaningwave.net/">
    <!--スライダー-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('js/slick.min.js') }}"></script>
    <script src="{{ asset('js/slider.js') }}"></script><!--スライダー-->
     <script src="{{ asset('js/scroll.js') }}"></script>
     <!--CSS-->
    <link rel="stylesheet" href="{{ asset('css/turusmart.css') }}">
    <link rel="stylesheet" href="{{ asset('css/smartmenu.css') }}">
    <!--slickのテーマ-->
    <link rel="stylesheet" href="{{ asset('css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick-theme.css') }}">
    <script src="JavaScriptファイルのパス"></script>
  </head>
  <body>
 <div class="content">
  <div class="menubar">
   <dd class="interval">.</dd>
   <dd class="storename">クリーニングWAVE 弦巻店</dd>
   <dd class="storename">☎︎03-5477-1613</dd>
   <dd class="interval">.</dd>
  </div>
  </div>
   <nav>
    <input id="gnav-input" type="checkbox" class="gnav-hidden">
     <label id="gnav-btn" for="gnav-input"></label>
     <label id="gnav-black" for="gnav-input"></label>
     <div id="gnav-content">
       <ul class="gnav-list">
         <li><a href="#content2">トップ<br>TOP</a></li>
         <li><a href="#content5">当店について<br>About us</a></li>
         <li><a href="#content6">4つのサービス<br>Service</a></li>
	       <li><a href="#map">店舗情報<br>Store infomation</a></li>
         <li><a href="titosesmart">千歳台本店について<br>Titosedai store</a></li>
       </ul>
     </div>
   </div>
   </nav>
   <!--スライドショー-->
  <div id ="content2">
    <ul class="slider">
      <img src="{{ asset('img/plate4.jpg') }}" alt="Yシャツセール">
      <img src="{{ asset('img/plate.jpg') }}" alt="Wクリーニング無料">
      <img src="{{ asset('img/plate2.jpg') }}" alt="とことん染み抜き">
      <img src="{{ asset('img/plate3.jpg') }}" alt="毛玉とり無料">
    </ul>
  </div>
  <div id="sale">
  @if ($value == 2)
    <img class="saleimg" src="{{ asset('img/smartthues.png') }}" alt="火曜日Yシャツセールデー">
  @elseif ($value == 3)
     <img class="saleimg" src="{{ asset('img/smartwed.png') }}" alt="水曜日Yシャツセールデー">
  @else
  @endif
  </div>
  <div id ="content3">
    <h1 class="contenttitle1">クリーニングWAVE弦巻店</h1>
    <p class="text">衣服の汚れお任せください、染み抜きのプロがいるお店</p>
  </div>
  <div id="content4">
    <p class="text1">当店ではプロの染み抜き職人が在籍しとことんキレイに、あなたの衣服をもう一回輝かせるという二つの理念をモットーに営業してきました。</p>
   <p class="text2">妥協をしないキレイな仕上げを心がけ開業から30年弦巻のこだわりあるクリーニング店として地域に愛されてきました。</p>
  </div>
  <div id="content5">
    <h2 class="contenttitle1">🔹当店のサービスについて</h2>
   <div class="fivecontent">
    <div class="fiveitem">
      <img class="img1"src="{{ asset('img/store.jpg') }}" alt="キレイな仕上げを目指します">
       <h3 class="fivetitle">キレイな仕上げを目指します</h3>
       <p>シミ、汚れご相談下さい!シミの種類によって処理方法が違います。シミの原因を教えてください</p>
    </div>
    <div class="fiveitem">
      <img class="img1"src="{{ asset('img/store2.jpg') }}" alt="そのシミ、プロにお任せください">
       <h3 class="fivetitle">そのシミ、プロにお任せください</h3>
       <p>シミを付けてしまったらいじらずそのままの状態でお持ちこみください。</p>
       <p>自己流で取ろうとすることでかえってシミを広げてしまったりシミを取りずらくしてしまいます。</p>
    </div>
    <div class="fiveitem">
      <img class="img1"src="{{ asset('img/store3.jpg') }}" alt="修理、受付でご相談ください">
       <h3 class="fivetitle">修理、受付でご相談ください</h3> 
       <p>キズ、破れ等の修理、丈詰め・寸法直し等のお直し、かけはぎ等幅広くお客様のご要望にお応えします。</p>
    </div>
   </div>
  </div>

  <div id="content6">
   <h2 class="contenttitle1">🔹クリーニングWAVE4つのサービス</h2>
   <div class="sixitem">
     <h3 class="sixtitle">1.Wクリーニング無料</h3>
     <img src="{{ asset('img/smartplate.png') }}" alt="Wクリーニング無料">
     <p class="sixmain">当店は YシャツもWクリーニング無料、袖、袖口の皮脂汚れ水洗いだけでは落ちません。油性と水溶性の汚れ、匂いをしっかりと落としてスッキリさっぱり仕上げます。</p>
   </div>
   <div class="sixitem">
     <h3 class="sixtitle">2.Yシャツセール</h3>
     <img src="{{ asset('img/smartplate2.png') }}" alt="Yシャツセール">
     <p class="sixmain">Yシャツセール(立体)弦巻店は毎週火曜日、水曜日におこないます。但し特殊な品物は除きます。</p>
   </div>
   <div class="sixitem">
     <h3 class="sixtitle">3.とことん染み抜き</h3>
     <img src="{{ asset('img/smartplate3.png') }}" alt="とことん染み抜き">
     <p class="sixmain">キレイな仕上げを目指します!シミ・汚れ当店にご相談ください。シミを付けてしまったらいじらずそのままの状態でお持ちください。自己流で取ろうとすることでかえってシミを広げてしまったりシミを取りずらくしてしまいます。</p>
   </div>
   <div class="sixitem">
     <h3 class="sixtitle">4.毛玉とり無料</h3>
     <img src="{{ asset('img/smartplate4.png') }}" alt="毛玉とり無料">
     <p class="sixmain">オーバー類、セーター、カーディガン、マフラー等のニット類、コート、ダウン、ジャンパー類の襟、袖口のニット部分毛玉のふちなどの毛玉を無料でお取りします。</p>
   </div>    
   </div> 
  </div>
  
  <div id="map" itemscope itemtype="https://schema.org/DryCleaningOrLaundry">
    <h2 class="maptitle">店舗情報</h2>
    <p class="subtitle" itemprop="name">クリーニングWAVE【弦巻店】</p>
    <p>【営業時間】</p>
    <p itemprop="openingHours">月曜〜土曜日AM10:00~PM7:00<br>日曜祝日AM10:00~PM6:00</p>
    <p class="restday">定休日金曜日</p>
    <p class="tel" itemprop="telephone">☎︎ 03-5477-1613</p>
    <p class="address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">〒<span itemprop="postalCode">154-0016</span><br><span itemprop="addressRegion">東京都</span><span itemprop="addressLocality">世田谷区</span><span itemprop="streetAddress">弦巻２丁目9-13駒電ビル１階</span></p>
    <p class="warning">※当サイトに記載されている店舗写真等の無断転載、無断使用はご遠慮ください。<br>また当店の外観写真や店舗情報等を他サイト等に転載する行為などもかたくお断りしております。</p>
    <div class="gmap">
     <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3242.6399381790043!2d139.6517688152576!3d35.636601980204624!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6018f4813430063f%3A0x45c516be21df7ad6!2z44CSMTU0LTAwMTYg5p2x5Lqs6YO95LiW55Sw6LC35Yy65bym5be777yS5LiB55uu77yZ4oiS77yR77yTIOmnkumbu-ODk-ODqw!5e0!3m2!1sja!2sjp!4v1642133983133!5m2!1sja!2sjp" width="100" height="auto" frameborder="0" style="border:0;" allowfullscreen></iframe>
    </div> 
   </div>
  </div>
  </body>
   <footer>
   <dd class="point">.</dd>
   <dd>Copyright © 2022 CLEANING WAVE All Rights Reserved.</dd>
   <dd class="point">.</dd>
 <footer>
 </html>
