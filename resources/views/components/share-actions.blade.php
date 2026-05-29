<div x-data="{
    shareOpen: false,
    shareUrl: '{{ $shareUrl }}',
    shareText: '{{ $shareText }}'
}" class="relative inline-block text-left">
    <button type="button" @click="shareOpen = !shareOpen" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
        <i class="fas fa-share-alt mr-2"></i>
        Share
    </button>

    <div x-show="shareOpen" x-cloak @click.away="shareOpen = false" x-transition class="absolute right-0 z-20 mt-2 w-56 origin-top-right rounded-md border border-gray-200 bg-white shadow-lg ring-1 ring-black ring-opacity-5 dark:border-gray-700 dark:bg-gray-800">
        <div class="py-1">
            <a :href="`mailto:?subject=${encodeURIComponent(shareText + ' - Printable Version')}&body=${encodeURIComponent('Please find the printable version of: ' + shareText + '\n\n' + shareUrl)}`" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                <i class="fas fa-envelope mr-2"></i> Email
            </a>
            <a :href="`https://wa.me/?text=${encodeURIComponent('📄 ' + shareText + ' (Printable PDF)\n' + shareUrl)}`" target="_blank" rel="noopener" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                <i class="fab fa-whatsapp mr-2"></i> WhatsApp
            </a>
            <a :href="`skype:?chat&topic=${encodeURIComponent(shareText + ' - Printable Version')}&message=${encodeURIComponent('📄 ' + shareText + ' (Printable PDF)\n' + shareUrl)}`" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                <i class="fab fa-skype mr-2"></i> Skype
            </a>
            <button type="button" @click="
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(shareUrl).then(() => {
                        shareOpen = false;
                        alert('Printable PDF link copied to clipboard!');
                    });
                } else {
                    // Fallback for older browsers
                    const textArea = document.createElement('textarea');
                    textArea.value = shareUrl;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    shareOpen = false;
                    alert('Printable PDF link copied to clipboard!');
                }
            " class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                <i class="fas fa-link mr-2"></i> Copy Link
            </button>
        </div>
    </div>
</div>
