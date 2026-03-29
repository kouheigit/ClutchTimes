<!DOCTYPE html>
<html>
 <head>
    <meta charset="UTF-8">
    <!--SEO対策用タグを忘れるな-->
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>クリーニングWAVE　千歳台店</title>
    <meta name="description" content="千歳台のクリーニングならクリーニングWAVE 千歳台店にお任せ！あなたの衣服をもう一回輝かせるという二つの理念をモットーに、妥協をしないキレイな仕上げを心がけ開業から30年、千歳台のこだわりのあるクリーニング店として地域に愛されて営業してきました。">
    <meta name="keywords" content="クリーニング,千歳台">
    <link rel="alternate" media="only screen and (max-width: 640px)" href="https://cleaningwave.net/titosesmart" >
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('js/slick.min.js') }}"></script>
    <script src="{{ asset('js/slider.js') }}"></script><!--スライダー-->
     <script src="{{ asset('js/scroll.js') }}"></script>
    <script src="{{ asset('js/judge1.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/titosedai.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick-theme.css') }}">
    <script src="JavaScriptファイルのパス"></script>
 </head>
 <body>
  <div id="content">
   <h1 class="titles">クリーニングWAVE　千歳台店</h1>
  <!--メニューバー-->
    <nav>
     <ul class="menu">
       <li><a href="#content"><img src="{{ asset('img/menupic2.png') }}" width="60px" height="60px" alt="トップ">トップ</a></li>
       <li><a href="#content2"><img src="{{ asset('img/menupic3.png') }}" width="60px" height="60px" alt="当店について">当店について</a></li>
       <li><a href="#content3"><img src="{{ asset('img/menupic.png') }}" width="60px" height="50px" alt="4つのサービス">4つのサービス</a></li>
       <li><a href="#content4"><img src="{{ asset('img/menupic4.png') }}" width="60px" height="60px" alt="店舗情報">店舗情報</a></li>
       <li><a href="/"><img src="{{ asset('img/menupic5.png') }}" width="60px" height="50px" alt="弦巻店について">弦巻店について</a></li>
     </ul>
    </nav>
  </div>
  <!--スライドショー-->
    <ul class="slider">
      <img src="{{ asset('img/pcplate5.png') }}" alt="Yシャツセール176円">
      <img src="{{ asset('img/pcplate2.jpg') }}" alt="とことん染み抜き">
      <img src="{{ asset('img/pcplate1.jpg') }}" alt="Wクリーニング無料">
      <img src="{{ asset('img/pcplate3.jpg') }}" alt="毛玉取り無料">
    </ul>
   @if ($value == 4)
    <div class="tokuhos">
      <!--<h1 class="tokuho"> {{$value ?? ''}}</h1>-->
      <img src="{{ asset('img/salethu.jpg') }}" alt="木曜日Yシャツセールデー">
    </div>
    @elseif ($value == 5)
     <div class="tokuhos">
      <!--<h1 class="tokuho"> {{$value ?? ''}}</h1>-->
      <img src="{{ asset('img/salefri.jpg') }}" alt="金曜日Yシャツセールデー">
    </div>
    @else
    @endif
 <!--WAVEについて-->
  <div id="content2">
    <h1>クリーニングWAVE　千歳台店</h1>
    <h2>衣服の汚れお任せください、染み抜きのプロがいるお店</h2>
    <p>クリーニングWAVE 千歳台店ではプロの染み抜き職人が在籍し<span class="pic">とことんキレイに</span>、<span class="pic1">あなたの衣服をもう一回輝かせる</span>という二つの理念をモットーに妥協をしない<br>キレイな仕上げを心がけ開業から30年、千歳台のこだわりのあるクリーニング店として地域に愛されて営業してきました。</p>  
   <h2 class="subtitle">当店のサービスについて</h2>
    <dl class="store">
      <div class="storecontent">
        <img src="{{ asset('img/titose.jpg') }}" alt="キレイな仕上げを目指します">
        <h3>キレイな仕上げを目指します</h3>
        <p>シミ、汚れご相談下さい!シミの種類によって処理方法が違います。シミの原因を教えてください</p>
     </div> 
      <div class="storecontent">
        <img src="{{ asset('img/titose1.jpg') }}" alt="そのシミ、プロにお任せください">
        <h3>そのシミ、プロにお任せください</h3>
        <p>シミを付けてしまったらいじらずそのままの状態でお持ちこみください。</p><p>自己流で取ろうとすることでかえってシミを広げてしまったりシミを取りずらくしてしまいます。</p>
      </div>
      <div class="storecontent">
       <img src="{{ asset('img/titose2.jpg') }}" alt="修理、受付でご相談ください">
       <h3>修理、受付でご相談ください</h3>
       <p>キズ、破れ等の修理、丈詰め・寸法直し等のお直し、かけはぎ等幅広くお客様のご要望にお応えします</p>
      </div>
    </dl>
  </div>
  <!--４つのサービス-->
  <div id="content3">
     <p>４つのサービス</p>
     <h2 class="servicetitle">クリーニングWAVE 千歳台店4つのサービス</h2>
   <dl class="service">
    <div class="servicecontent">
      <h3 class="title" >サービスその1 Wクリーニング</h3>
      <img src="{{ asset('img/service.png') }}" alt="Wクリーニング">
      <p>当店は YシャツもWクリーニング無料、袖、袖口の皮脂汚れ水洗いだけでは落ちません。<br>油性と水溶性の汚れ、匂いをしっかりと落としてスッキリさっぱり仕上げます。</p>
    </div>
    <div class="servicecontent">
      <h3 class="title" >サービスその2 Yシャツセール</h3>
      <img src="{{ asset('img/titoseservice2.png') }}" alt="Yシャツセール">
      <p>Yシャツセール(立体)千歳台店は毎週木曜日、金曜日におこないます。<br>但し特殊な品物は除きます。</p>
    </div>
    </dl>

   <dl class="service1">
     <div class="servicecontent">
      <h3 class="title">サービスその3 とことん染み抜き</h3>
      <img src="{{ asset('img/service3.png') }}" alt="とことん染み抜き">
      <p>キレイな仕上げを目指します!シミ・汚れ当店にご相談ください。<br>シミを付けてしまったらいじらずそのままの状態でお持ちください。<br>自己流で取ろうとすることでかえってシミを広げてしまったりシミを取りずらくしてしまいます。</p>
    </div>
    <div class="servicecontent">
      <h3 class="title" >サービスその4 毛玉とり無料</h3>
      <img src="{{ asset('img/service4.png') }}" alt="毛玉とり無料">
      <p>オーバー類、セーター、カーディガン、マフラー等のニット類、<br>コート、ダウン、ジャンパー類の襟、袖口のニット部分<br>毛玉のふちなどの毛玉を無料でお取りします。</p>
</p>
    </div>
    </dl>
  </div>
 <!--店舗情報-->
  <div id="content4">
   <div class="map">
     <h2>クリーニングWAVE 千歳台店 店舗情報</h2>
     <dl class="mapall">
     <div class="mapcontent">
     <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3242.0711354790105!2d139.613178415258!3d35.65061898020153!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6018f3da32d47f5d%3A0x71124950d0ddd1ee!2z44CSMTU3LTAwNzEg5p2x5Lqs6YO95LiW55Sw6LC35Yy65Y2D5q2z5Y-w77yT5LiB55uu77yZ4oiS77yWIOOCteODs-OCsOODrOODvOOCueWNg-ats-WPsA!5e0!3m2!1sja!2sjp!4v1642254338270!5m2!1sja!2sjp" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
     </div>
    <div class="mapcontent" itemscope itemtype="https://schema.org/DryCleaningOrLaundry">
      <ul>
        <li class="first" itemprop="name">クリーニングWAVE【千歳台店】</li>
	      <li itemprop="openingHours">【営業時間】<br>月曜〜日曜日AM9:00~PM7:00<br>日曜祝日AM10:00~PM6:00<br><span class="dayoff">定休日なし</span></li>
        <li itemprop="telephone">☎︎03-3483-5572</li>
	      <li itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">〒<span itemprop="postalCode">157-0071</span><span itemprop="addressRegion">東京都</span><span itemprop="addressLocality">世田谷区</span><span itemprop="streetAddress">千歳台３丁目９−６<br>サングレース千歳台1階</span></li>
	      <li class="last">※当サイトに記載されている店舗写真等の無断転載、無断使用はご遠慮ください。<br>また当店の外観写真や店舗情報等を他サイト等に転載する行為などもかたくお断りしております。</li>
      </ul>
      <!--
        <h2 class="tel">☎︎03-5477-1613</h2>
        <h2>〒154-0016東京都世田谷区弦巻２丁目9-13駒電ビル１階</h2>-->
     </div>
    </div>
   </dl>
  </div>
 </div>
 <footer>
   <dd class="point">.</dd>
   <dd>Copyright © 2022 CLEANING WAVE All Rights Reserved.</dd>
   <dd class="point">.</dd>
 <footer>
 </body>
</html>
