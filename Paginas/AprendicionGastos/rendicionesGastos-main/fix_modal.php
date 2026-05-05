<?php
$str = file_get_contents('resources/views/reports/show.blade.php');

// We use 'hidden' which is more robust than opacity-0 for initial hide
$oldModalStart = <<<PHP
    <!-- Document Preview Modal -->
    <div id="documentPreviewModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
PHP;

$newModalStart = <<<PHP
    <!-- Document Preview Modal -->
    <div id="documentPreviewModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300" style="opacity: 0; pointer-events: none; display: none;">
PHP;

$str = str_replace($oldModalStart, $newModalStart, $str);

$oldModalJS = <<<PHP
            // Show modal
            modal.classList.remove('opacity-0', 'pointer-events-none');
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
            document.body.style.overflow = 'hidden'; // prevent outside scroll
        }

        function closePreviewModal() {
            const modal = document.getElementById('documentPreviewModal');
            const content = document.getElementById('previewModalContent');
            const iframe = document.getElementById('previewModalFrame');
            
            // Hide container
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            modal.classList.add('opacity-0', 'pointer-events-none');
            
            // Reset state
            setTimeout(() => {
                iframe.src = 'about:blank';
                iframe.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }
PHP;

$newModalJS = <<<PHP
            // Show modal
            modal.style.display = 'flex';
            setTimeout(() => {
                modal.style.opacity = '1';
                modal.style.pointerEvents = 'auto';
                modal.classList.remove('opacity-0', 'pointer-events-none');
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
                document.body.style.overflow = 'hidden'; // prevent outside scroll
            }, 10);
        }

        function closePreviewModal() {
            const modal = document.getElementById('documentPreviewModal');
            const content = document.getElementById('previewModalContent');
            const iframe = document.getElementById('previewModalFrame');
            
            // Hide container
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            modal.style.opacity = '0';
            modal.style.pointerEvents = 'none';
            modal.classList.add('opacity-0', 'pointer-events-none');
            
            // Reset state
            setTimeout(() => {
                modal.style.display = 'none';
                iframe.src = 'about:blank';
                iframe.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }
PHP;

$str = str_replace($oldModalJS, $newModalJS, $str);

file_put_contents('resources/views/reports/show.blade.php', $str);
