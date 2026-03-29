<!DOCTYPE>
 <html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>クリーニングWAVE 千歳台店</title>
    <meta name="description" content="千歳台のクリーニングならクリーニングWAVE 千歳台店にお任せ！あなたの衣服をもう一回輝かせるという二つの理念をモットーに、妥協をしないキレイな仕上げを心がけ開業から30年、千歳台のこだわりのあるクリーニング店として地域に愛されて営業してきました。">
    <meta name="keywords" content="クリーニング,千歳台">
    <link rel="canonical" href="https://cleaningwave.net/titosedai">
    <!--スライダー-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('js/slick.min.js') }}"></script>
    <script src="{{ asset('js/slider.js') }}"></script><!--スライダー-->
     <script src="{{ asset('js/scroll.js') }}"></script>
     <!--CSS-->
    <link rel="stylesheet" href="{{ asset('css/titosesmart.css') }}">
    <link rel="stylesheet" href="{{ asset('css/titosemenu.css') }}">
    <!--slickのテーマ-->
    <link rel="stylesheet" href="{{ asset('css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick-theme.css') }}">
    <script src="JavaScriptファイルのパス"></script>
  </head>
  <body>
 <div class="content">
  <div class="menubar">
   <dd class="interval">.</dd>
   <dd class="storename">クリーニングWAVE 千歳台店</dd>
   <dd class="storename">☎︎03-3483-5572</dd>
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
         <li><a href="turusmart">弦巻店について<br>Tsurumaki store</a></li>
       </ul>
     </div>
   </div>
   </nav>
   <!--スライドショー-->
  <div id ="content2">
    <ul class="slider">
      <img src="{{ asset('img/plate5.jpg') }}" alt="Yシャツセール176円">
      <img src="{{ asset('img/plate.jpg') }}" alt="Wクリーニング無料">
      <img src="{{ asset('img/plate2.jpg') }}" alt="とことん染み抜き">
      <img src="{{ asset('img/plate3.jpg') }}" alt="毛玉取り無料">
    </ul>
  </div>
  <div id="sale">
  @if ($value == 4)
    <img class="saleimg" src="{{ asset('img/smartthu.png') }}" alt="木曜日Yシャツセールデー">
  @elseif ($value == 5)
     <img class="saleimg" src="{{ asset('img/smartfri.png') }}" alt="金曜日Yシャツセールデー">
  @else
  @endif
  </div>
  <div id ="content3">
    <h1 class="contenttitle1">クリーニングWAVE千歳台店</h1>
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
      <img class="img1"src="{{ asset('img/titose.jpg') }}" alt="キレイな仕上げを目指します">
       <h3 class="fivetitle">キレイな仕上げを目指します</h3>
       <p>シミ、汚れご相談下さい!シミの種類によって処理方法が違います。シミの原因を教えてください</p>
    </div>
    <div class="fiveitem">
      <img class="img1"src="{{ asset('img/store3.jpg') }}" alt="そのシミ、プロにお任せください">
       <h3 class="fivetitle">そのシミ、プロにお任せください</h3>
       <p>シミを付けてしまったらいじらずそのままの状態でお持ちこみください。</p>
       <p>自己流で取ろうとすることでかえってシミを広げてしまったりシミを取りずらくしてしまいます。</p>
    </div>
    <div class="fiveitem">
      <img class="img1"src="{{ asset('img/titose2.jpg') }}" alt="修理、受付でご相談ください">
       <h3 class="fivetitle">修理、受付でご相談ください</h3> 
       <p>キズ、破れ等の修理、丈詰め・寸法直し等のお直し、かけはぎ等幅広くお客様のご要望にお応えします。</p>
    </div>
   </div>
  </div>
  <div id="content6">
   <h2 class="contenttitle1">🔹クリーニングWAVE4つのサービス</h2>
   <div class="sixitem">
    <h3 class="sixtitle">１．Wクリーニング無料</h3>
     <img src="{{ asset('img/smartplate.png') }}" alt="Wクリーニング無料">
     <p class="sixmain">当店は YシャツもWクリーニング無料、袖、袖口の皮脂汚れ水洗いだけでは落ちません。油性と水溶性の汚れ、匂いをしっかりと落としてスッキリさっぱり仕上げます。</p>
   </div>
     <div class="sixitem">
    <h3 class="sixtitle">２．Yシャツセール</h3>
     <img src="{{ asset('img/titosesmartplate2.png') }}" alt="Yシャツセール">
     <p class="sixmain">Yシャツセール(立体)千歳台店は毎週木曜日、金曜日におこないます。但し特殊な品物は除きます。</p>
   </div>
     <div class="sixitem">
    <h3 class="sixtitle">３．とことん染み抜き</h3>
     <img src="{{ asset('img/smartplate3.png') }}" alt="とことん染み抜き">
     <p class="sixmain">キレイな仕上げを目指します!シミ・汚れ当店にご相談ください。シミを付けてしまったらいじらずそのままの状態でお持ちください。自己流で取ろうとすることでかえってシミを広げてしまったりシミを取りずらくしてしまいます。</p>
   </div>
     <div class="sixitem">
     <h3 class="sixtitle">４．毛玉とり無料</h3>
     <img src="{{ asset('img/smartplate4.png') }}" alt="毛玉とり無料">
     <p class="sixmain">オーバー類、セーター、カーディガン、マフラー等のニット類、コート、ダウン、ジャンパー類の襟、袖口のニット部分毛玉のふちなどの毛玉を無料でお取りします。</p>
   </div>    
   </div> 
  </div>
  
  <div id="map" itemscope itemtype="https://schema.org/DryCleaningOrLaundry">
    <h2 class="maptitle">店舗情報</h2>
    <p class="subtitle" itemprop="name">クリーニングWAVE【千歳台店】</p>
    <p>【営業時間】</p>
    <p itemprop="openingHours">月曜〜日曜日AM9:00~PM7:00<br>日曜祝日AM10:00~PM6:00</p>
    <p class="restday">定休日なし</p>
    <p class="tel" itemprop="telephone">☎︎ 03-3483-5572</p>
    <p class="address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">〒<span itemprop="postalCode">157-0071</span><br><span itemprop="addressRegion">東京都</span><span itemprop="addressLocality">世田谷区</span><span itemprop="streetAddress">千歳台３丁目９−６<br>サングレース千歳台1階</span></p>
    <p class="warning">※当サイトに記載されている店舗写真等の無断転載、無断使用はご遠慮ください。<br>また当店の外観写真や店舗情報等を他サイト等に転載する行為などもかたくお断りしております。</p>
   <div class="gmap">
     <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3242.0711354790105!2d139.613178415258!3d35.65061898020153!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6018f3da32d47f5d%3A0x71124950d0ddd1ee!2z44CSMTU3LTAwNzEg5p2x5Lqs6YO95LiW55Sw6LC35Yy65Y2D5q2z5Y-w77yT5LiB55uu77yZ4oiS77yWIOOCteODs-OCsOODrOODvOOCueWNg-ats-WPsA!5e0!3m2!1sja!2sjp!4v1642254338270!5m2!1sja!2sjp" width="100" height="auto" frameborder="0" style="border:0;" allowfullscreen></iframe>
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
