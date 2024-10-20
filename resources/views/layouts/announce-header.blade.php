<!-- お知らせヘッダー -->
<div class="bg-blue-300 text-black py-2 px-4">
    <div class="container mx-auto flex items-center justify-between">
        <div class="flex items-center">
            <!-- アナウンスアイコン -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3v8h-3l4 4 4-4h-3V3h-2zm7 11a5 5 0 11-10 0 5 5 0 0110 0z" />
            </svg>
            <!-- アナウンスメッセージ -->
            <span class="text-sm md:text-base font-semibold">重要なお知らせ：サイトメンテナンスが10月15日に実施されます。</span>
        </div>

        <!-- 閉じるボタン -->
        <button id="close-announcement" class="text-black focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

<script>
    // アナウンスヘッダーを閉じる
    document.getElementById('close-announcement').addEventListener('click', function() {
        this.parentElement.parentElement.style.display = 'none';
    });
</script>
