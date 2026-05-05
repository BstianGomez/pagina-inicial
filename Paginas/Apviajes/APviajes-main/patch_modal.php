<?php
$str = file_get_contents('resources/views/reports/show.blade.php');

$oldBtns = <<<PHP
                            <div class="flex flex-wrap items-center gap-3 pt-5 border-t border-slate-200">
                                @if(\$expense->attachment_path)
                                <a href="{{ Storage::url(\$expense->attachment_path) }}" target="_blank" class="btn-sofofa" onclick="event.stopPropagation()">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    <span>Ver Boleta</span>
                                </a>
                                @endif
                                
                                @if(\$expense->comanda_path)
                                <a href="{{ Storage::url(\$expense->comanda_path) }}" target="_blank" class="btn-comanda" onclick="event.stopPropagation()">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H7"/></svg>
                                    <span>Ver Comanda</span>
                                </a>
                                @endif
PHP;

$newBtns = <<<PHP
                            <div class="flex flex-wrap items-center gap-3 pt-5 border-t border-slate-200">
                                @if(\$expense->attachment_path)
                                <button type="button" class="btn-sofofa" onclick="event.stopPropagation(); openPreviewModal('{{ Storage::url(\$expense->attachment_path) }}', 'Boleta #{{ \$expense->id }}')">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Previsualizar Boleta</span>
                                </button>
                                <a href="{{ Storage::url(\$expense->attachment_path) }}" target="_blank" class="px-2 py-1.5 bg-slate-100 text-slate-500 rounded hover:bg-slate-200 transition-colors" title="Abrir en pestaña nueva" onclick="event.stopPropagation()">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                                @endif
                                
                                @if(\$expense->comanda_path)
                                <button type="button" class="btn-comanda" onclick="event.stopPropagation(); openPreviewModal('{{ Storage::url(\$expense->comanda_path) }}', 'Comanda #{{ \$expense->id }}')">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Previsualizar Comanda</span>
                                </button>
                                <a href="{{ Storage::url(\$expense->comanda_path) }}" target="_blank" class="px-2 py-1.5 bg-slate-100 text-slate-500 rounded hover:bg-slate-200 transition-colors" title="Abrir en pestaña nueva" onclick="event.stopPropagation()">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                                @endif
PHP;

$str = str_replace($oldBtns, $newBtns, $str);

$modalHtml = <<<PHP
</x-layouts.app>
PHP;

$newModalHtml = <<<PHP

    <!-- Document Preview Modal -->
    <div id="documentPreviewModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-[95vw] sm:w-[85vw] md:w-[75vw] lg:w-[65vw] max-w-6xl overflow-hidden flex flex-col scale-95 transition-transform duration-300" style="height: 85vh;" id="previewModalContent">
            
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="bg-sofofa-blue/10 p-2 rounded-lg text-sofofa-blue">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H7"/></svg>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-800 text-[15px]" id="previewModalTitle">Visualizador de Documento</h3>
                        <p class="text-[11px] text-slate-500 font-medium">Previsualización integrada en sistema</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-2">
                    <a href="#" id="previewModalDownloadBtn" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Abrir Nueva Pestaña
                    </a>
                    <button type="button" onclick="closePreviewModal()" class="p-2 bg-slate-100 hover:bg-red-50 hover:text-red-600 text-slate-500 rounded-lg transition-colors" title="Cerrar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            
            <div class="flex-1 bg-slate-100/50 p-4 shrink-0 overflow-hidden relative">
                <div class="absolute inset-0 flex items-center justify-center" id="previewModalLoading">
                    <svg class="animate-spin h-8 w-8 text-sofofa-blue" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>
                <!-- The iframe will take full space -->
                <iframe id="previewModalFrame" class="w-full h-full rounded-xl shadow-sm border border-slate-200 bg-white relative z-10 hidden" frameborder="0"></iframe>
            </div>
        </div>
    </div>

    <script>
        function openPreviewModal(url, titleText) {
            const modal = document.getElementById('documentPreviewModal');
            const content = document.getElementById('previewModalContent');
            const iframe = document.getElementById('previewModalFrame');
            const title = document.getElementById('previewModalTitle');
            const btn = document.getElementById('previewModalDownloadBtn');
            const loading = document.getElementById('previewModalLoading');
            
            // Set dynamic data
            title.textContent = titleText || 'Visualizador de Documento';
            btn.href = url;
            
            // Hide iframe until loaded
            iframe.onload = function() {
                loading.classList.add('hidden');
                iframe.classList.remove('hidden');
            };
            
            // Update iframe src
            iframe.src = url;
            iframe.classList.add('hidden');
            loading.classList.remove('hidden');
            
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
        
        // Clicks outside modal content
        document.getElementById('documentPreviewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePreviewModal();
            }
        });
        
        // Escape key to close
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape" && !document.getElementById('documentPreviewModal').classList.contains('opacity-0')) {
                closePreviewModal();
            }
        });
    </script>
</x-layouts.app>
PHP;

$str = str_replace($modalHtml, $newModalHtml, $str);

file_put_contents('resources/views/reports/show.blade.php', $str);
