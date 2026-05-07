<x-app-layout>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    </head>
    <div class="py-4 px-6" id="report-container">
        <div class="max-w-7xl mx-auto">
            <!-- Header Compact -->
            <div class="mb-4 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white text-lg shadow-lg shadow-emerald-100">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight">Datos Rendiciones</h2>
                        <p class="text-[9px] text-slate-500 font-medium uppercase tracking-wider">Control Financiero y Gastos</p>
                    </div>
                </div>
                <div class="flex gap-2 no-print">
                    <button onclick="exportToPDF()" class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg font-bold text-slate-700 hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm text-xs">
                        <i class="fas fa-file-pdf text-red-500 text-[10px]"></i>
                        <span>PDF</span>
                    </button>
                    <button onclick="openSendModal()" style="background-color:#059669; color:#fff; border:1px solid #047857;" class="px-3 py-1.5 rounded-lg font-bold hover:opacity-90 transition-all flex items-center gap-2 shadow-sm text-xs">
                        <i class="fas fa-paper-plane text-[10px]"></i>
                        <span>Enviar</span>
                    </button>
                </div>
            </div>

            <!-- Stats Overview Compact - Matching Rendicion Styles -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-2 mb-4">
                <div class="p-3 rounded-xl border border-slate-100 shadow-sm border-l-4 border-l-[#a855f7] bg-gradient-to-br from-[#faf5ff] to-white">
                    <div class="text-[8px] font-black text-[#9333ea] uppercase tracking-widest mb-0.5">Total</div>
                    <div class="text-lg font-black text-[#6b21a8]">{{ $stats['total'] }}</div>
                </div>
                <div class="p-3 rounded-xl border border-slate-100 shadow-sm border-l-4 border-l-[#eab308] bg-gradient-to-br from-[#fffbeb] to-white">
                    <div class="text-[8px] font-black text-[#d97706] uppercase tracking-widest mb-0.5">Borrador</div>
                    <div class="text-lg font-black text-[#92400e]">{{ $stats['borrador'] }}</div>
                </div>
                <div class="p-3 rounded-xl border border-slate-100 shadow-sm border-l-4 border-l-[#3b82f6] bg-gradient-to-br from-[#eff6ff] to-white">
                    <div class="text-[8px] font-black text-[#2563eb] uppercase tracking-widest mb-0.5">Enviadas</div>
                    <div class="text-lg font-black text-[#1d4ed8]">{{ $stats['enviados'] }}</div>
                </div>
                <div class="p-3 rounded-xl border border-slate-100 shadow-sm border-l-4 border-l-[#6366f1] bg-gradient-to-br from-[#eef2ff] to-white">
                    <div class="text-[8px] font-black text-[#4f46e5] uppercase tracking-widest mb-0.5">En Proceso</div>
                    <div class="text-lg font-black text-[#3730a3]">{{ $stats['en_proceso'] }}</div>
                </div>
                <div class="p-3 rounded-xl border border-slate-100 shadow-sm border-l-4 border-l-[#ef4444] bg-gradient-to-br from-[#fff5f5] to-white">
                    <div class="text-[8px] font-black text-[#dc2626] uppercase tracking-widest mb-0.5">Rechazadas</div>
                    <div class="text-lg font-black text-[#b91c1c]">{{ $stats['rechazadas'] }}</div>
                </div>
                <div class="p-3 rounded-xl border border-slate-100 shadow-sm border-l-4 border-l-[#22c55e] bg-gradient-to-br from-[#f0fdf4] to-white">
                    <div class="text-[8px] font-black text-[#16a34a] uppercase tracking-widest mb-0.5">Reembolsadas</div>
                    <div class="text-lg font-black text-[#15803d]">{{ $stats['reembolsadas'] }}</div>
                </div>
                <div class="p-3 rounded-xl border border-slate-100 shadow-sm border-l-4 border-l-[#9ca3af] bg-gradient-to-br from-[#f9fafb] to-white">
                    <div class="text-[8px] font-black text-[#6b7280] uppercase tracking-widest mb-0.5">Anuladas</div>
                    <div class="text-lg font-black text-[#4b5563]">0</div>
                </div>
            </div>

            <!-- Charts Section Compact -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                <div class="bg-white p-5 rounded-[1.5rem] border border-slate-100 shadow-xl">
                    <p class="text-[10px] font-black text-slate-900 mb-3 flex items-center gap-2 uppercase tracking-widest">Distribución por Estado</p>
                    <div class="h-[200px]">
                        <canvas id="chartDonut"></canvas>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-[1.5rem] border border-slate-100 shadow-xl">
                    <p class="text-[10px] font-black text-slate-900 mb-3 flex items-center gap-2 uppercase tracking-widest">Gasto por Categoría</p>
                    <div class="h-[200px]">
                        <canvas id="chartCat"></canvas>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-[1.5rem] border border-slate-100 shadow-xl lg:col-span-2">
                    <p class="text-[10px] font-black text-slate-900 mb-3 flex items-center gap-2 uppercase tracking-widest">Tendencia últimos 6 meses</p>
                    <div class="h-[200px]">
                        <canvas id="chartTrendLine"></canvas>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-xl overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Listado de Rendiciones</h3>
                    <a href="{{ route('rendicion.reports.index') }}" class="text-[10px] text-emerald-600 font-bold hover:underline no-print">Ver Todo</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left bg-slate-50/50">
                                <th class="px-5 py-2 font-black text-slate-400 uppercase tracking-widest text-[8px]">Informe</th>
                                <th class="px-5 py-2 font-black text-slate-400 uppercase tracking-widest text-[8px]">Monto</th>
                                <th class="px-5 py-2 font-black text-slate-400 uppercase tracking-widest text-[8px]">Estado</th>
                                <th class="px-5 py-2 font-black text-slate-400 uppercase tracking-widest text-[8px]">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 py-2 text-xs font-bold text-slate-700">Alimentación Terreno</td>
                                <td class="px-5 py-2 text-xs font-black text-slate-900">$25.500</td>
                                <td class="px-5 py-2">
                                    <span class="px-1.5 py-0.5 bg-orange-50 text-orange-600 rounded-full text-[8px] font-black uppercase tracking-widest">Pendiente</span>
                                </td>
                                <td class="px-5 py-2 text-[10px] text-slate-500 font-medium">Hoy, 09:15</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Send Modal -->
    <div id="sendModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 text-xl">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900">Enviar Reporte</h3>
                        <p class="text-xs text-slate-500 font-medium">Se enviará el reporte de rendiciones en formato PDF.</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Correo Electrónico</label>
                        <input type="email" id="targetEmail" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none transition-all text-sm font-medium" placeholder="ejemplo@sofofa.cl">
                    </div>
                </div>
                <div class="flex gap-3 mt-8">
                    <button onclick="closeSendModal()" style="flex:1; padding:12px 16px; background:#f1f5f9; color:#475569; border-radius:12px; font-weight:700; font-size:14px; border:none; cursor:pointer;">Cancelar</button>
                    <button onclick="handleSend()" style="flex:1; padding:12px 16px; background:#059669; color:#fff; border-radius:12px; font-weight:700; font-size:14px; border:none; cursor:pointer;">Enviar Ahora</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        async function exportToPDF() {
            const element = document.getElementById('report-container');
            document.querySelectorAll('.no-print').forEach(el => el.style.display = 'none');

            // Convert all canvases to static images before capture
            const canvases = element.querySelectorAll('canvas');
            const replacements = [];
            canvases.forEach(canvas => {
                const dataURL = canvas.toDataURL('image/png', 1.0);
                const img = document.createElement('img');
                img.src = dataURL;
                img.style.width = canvas.offsetWidth + 'px';
                img.style.height = canvas.offsetHeight + 'px';
                img.style.display = 'block';
                canvas.parentNode.insertBefore(img, canvas);
                canvas.style.display = 'none';
                replacements.push({ canvas, img });
            });

            const opt = {
                margin: [8, 8, 8, 8],
                filename: 'Reporte_Rendiciones_' + new Date().toISOString().slice(0,10) + '.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true, scrollX: 0, scrollY: 0 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            await html2pdf().set(opt).from(element).save();

            replacements.forEach(({ canvas, img }) => {
                canvas.style.display = '';
                img.remove();
            });
            document.querySelectorAll('.no-print').forEach(el => el.style.display = '');
        }

        function openSendModal() { document.getElementById('sendModal').classList.remove('hidden'); }
        function closeSendModal() { document.getElementById('sendModal').classList.add('hidden'); }
        function handleSend() {
            const email = document.getElementById('targetEmail').value;
            if(!email) { alert('Por favor ingrese un correo electrónico'); return; }
            alert('Reporte de rendiciones enviado correctamente a ' + email);
            closeSendModal();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const counts = @json($stats);
            const categoryData = @json($categoryStats);
            const trendData = @json($monthly);

            const chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                animation: false // For PDF
            };

            new Chart(document.getElementById('chartDonut'), {
                type: 'doughnut',
                data: {
                    labels: ['Borrador', 'Enviadas', 'En Proceso', 'Rechazadas', 'Reembolsadas'],
                    datasets: [{
                        data: [counts.borrador, counts.enviados, counts.en_proceso, counts.rechazadas, counts.reembolsadas],
                        backgroundColor: ['#eab308','#3b82f6','#f59e0b','#ef4444','#22c55e'],
                        borderWidth: 3, borderColor: '#fff',
                    }]
                },
                options: { ...chartOptions, cutout: '68%', plugins: { legend: { position: 'right', labels: { boxWidth: 8, font: { size: 9, weight: '700' }, padding: 8 } } } }
            });

            new Chart(document.getElementById('chartCat'), {
                type: 'bar',
                data: { labels: Object.keys(categoryData), datasets: [{ label: 'Monto ($)', data: Object.values(categoryData), backgroundColor: ['#3b82f6','#8b5cf6','#f59e0b','#22c55e','#ef4444','#06b6d4'], borderRadius: 4, barThickness: 10 }] },
                options: { ...chartOptions, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 8 }, callback: v => '$' + (v / 1000).toFixed(0) + 'k' } }, y: { grid: { display: false }, ticks: { font: { size: 8 } } } } }
            });

            new Chart(document.getElementById('chartTrendLine'), {
                type: 'line',
                data: { labels: trendData.map(d => d.label), datasets: [{ label: 'Monto ($)', data: trendData.map(d => d.amount), borderColor: '#22c55e', backgroundColor: 'rgba(34,197,94,.05)', fill: true, tension: 0.4, pointBackgroundColor: '#22c55e', pointRadius: 3, borderWidth: 2 }] },
                options: { ...chartOptions, plugins: { legend: { display: false } }, scales: { y: { position: 'right', grid: { color: '#f1f5f9' }, ticks: { font: { size: 8 }, callback: v => '$' + (v/1000).toFixed(0) + 'k' } }, x: { grid: { display: false }, ticks: { font: { size: 8 } } } } }
            });
        });
    </script>
</x-app-layout>
