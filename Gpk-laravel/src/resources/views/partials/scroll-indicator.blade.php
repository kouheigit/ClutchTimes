{{-- スクロールインジケーターパーシャル --}}

<div class="fixed top-0 left-0 w-full h-1 bg-gray-200 z-50">
    <div class="h-full bg-blue-600 transition-all duration-300" 
         id="scroll-indicator"
         style="width: 0%"></div>
</div>
<script>
    window.addEventListener('scroll', function() {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        document.getElementById('scroll-indicator').style.width = scrolled + '%';
    });
</script>

