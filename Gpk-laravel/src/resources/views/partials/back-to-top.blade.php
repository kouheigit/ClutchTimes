{{-- トップに戻るボタンパーシャル --}}

<button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
        class="fixed bottom-8 right-8 p-3 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 opacity-0 transition-opacity duration-300"
        id="back-to-top"
        style="display: none;">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
    </svg>
</button>
<script>
    window.addEventListener('scroll', function() {
        const button = document.getElementById('back-to-top');
        if (window.pageYOffset > 300) {
            button.style.display = 'block';
            button.classList.remove('opacity-0');
            button.classList.add('opacity-100');
        } else {
            button.classList.remove('opacity-100');
            button.classList.add('opacity-0');
            setTimeout(() => {
                if (window.pageYOffset <= 300) {
                    button.style.display = 'none';
                }
            }, 300);
        }
    });
</script>

