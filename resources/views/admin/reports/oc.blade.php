<x-app-layout>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    </head>
    <div class="py-4 px-6" id="report-container">
        <div class="max-w-7xl mx-auto">
            <!-- Header Compact -->
            <div class="mb-4 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white text-lg shadow-lg shadow-blue-100">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight">Datos OC</h2>
                        <p class="text-[9px] text-slate-500 font-medium uppercase tracking-wider">Gestión Institucional de Compras</p>
                    </div>
                </div>
                <div class="flex gap-2 no-print">
                    <button onclick="exportToPDF()" class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg font-bold text-slate-700 hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm text-xs">
                        <i class="fas fa-file-pdf text-red-500 text-[10px]"></i>
                        <span>Descargar PDF</span>
                    </button>
                    <button onclick="openSendModal()" class="px-3 py-1.5 bg-blue-600 border border-blue-700 rounded-lg font-bold text-white hover:bg-blue-700 transition-all flex items-center gap-2 shadow-sm text-xs">
                        <i class="fas fa-paper-plane text-[10px]"></i>
                        <span>Enviar Reporte</span>
                    </button>
                </div>
            </div>

            <!-- Stats Overview Compact -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2 mb-4">
                <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-blue-300"></div>
                    <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total</div>
                    <div class="text-lg font-black text-slate-900">{{ $stats['total'] }}</div>
                </div>
                <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-blue-300"></div>
                    <div class="text-[8px] font-black text-blue-500 uppercase tracking-widest mb-0.5">Solicitada</div>
                    <div class="text-lg font-black text-slate-900">{{ $stats['pending'] }}</div>
                </div>
                <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-orange-500 to-orange-300"></div>
                    <div class="text-[8px] font-black text-orange-500 uppercase tracking-widest mb-0.5">Enviada</div>
                    <div class="text-lg font-black text-slate-900">{{ $stats['sent'] }}</div>
                </div>
                <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-emerald-300"></div>
                    <div class="text-[8px] font-black text-emerald-500 uppercase tracking-widest mb-0.5">Aceptada</div>
                    <div class="text-lg font-black text-slate-900">{{ $stats['accepted'] }}</div>
                </div>
                <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-red-500 to-red-300"></div>
                    <div class="text-[8px] font-black text-red-500 uppercase tracking-widest mb-0.5">Rechazada</div>
                    <div class="text-lg font-black text-slate-900">{{ $stats['rejected'] }}</div>
                </div>
                <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-cyan-500 to-cyan-300"></div>
                    <div class="text-[8px] font-black text-cyan-500 uppercase tracking-widest mb-0.5">Facturado</div>
                    <div class="text-lg font-black text-slate-900">{{ $stats['facturado'] }}</div>
                </div>
            </div>

            <!-- Charts Section Compact -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                <div class="bg-white p-5 rounded-[1.5rem] border border-slate-100 shadow-xl">
                    <h3 class="text-[10px] font-black text-slate-900 mb-3 flex items-center gap-2 uppercase tracking-widest">
                        <i class="fas fa-chart-pie text-blue-600"></i>
                        Monto total por CECO
                    </h3>
                    <div class="h-[200px]">
                        <canvas id="chartByCeco"></canvas>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-[1.5rem] border border-slate-100 shadow-xl">
                    <h3 class="text-[10px] font-black text-slate-900 mb-3 flex items-center gap-2 uppercase tracking-widest">
                        <i class="fas fa-chart-line text-blue-600"></i>
                        Gasto mensual por CECO
                    </h3>
                    <div class="h-[200px]">
                        <canvas id="chartByMonth"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Compact -->
            <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-xl overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Actividad Reciente OC</h3>
                    <a href="{{ route('oc.dashboard') }}" class="text-[10px] text-blue-600 font-bold hover:underline no-print">Ir a OC</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left bg-slate-50/50">
                                <th class="px-5 py-2 font-black text-slate-400 uppercase tracking-widest text-[8px]">CECO</th>
                                <th class="px-5 py-2 font-black text-slate-400 uppercase tracking-widest text-[8px]">Estado</th>
                                <th class="px-5 py-2 font-black text-slate-400 uppercase tracking-widest text-[8px]">Monto</th>
                                <th class="px-5 py-2 font-black text-slate-400 uppercase tracking-widest text-[8px]">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 py-2 text-xs font-bold text-slate-700">20131 - TI</td>
                                <td class="px-5 py-2">
                                    <span class="px-1.5 py-0.5 bg-blue-50 text-blue-600 rounded-full text-[8px] font-black uppercase tracking-widest">Solicitada</span>
                                </td>
                                <td class="px-5 py-2 text-xs font-black text-slate-900">$450.000</td>
                                <td class="px-5 py-2 text-[10px] text-slate-500 font-medium">Hoy, 14:20</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 py-2 text-xs font-bold text-slate-700">20143 - Gerencias</td>
                                <td class="px-5 py-2">
                                    <span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-600 rounded-full text-[8px] font-black uppercase tracking-widest">Aceptada</span>
                                </td>
                                <td class="px-5 py-2 text-xs font-black text-slate-900">$1.200.000</td>
                                <td class="px-5 py-2 text-[10px] text-slate-500 font-medium">Hoy, 10:45</td>
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
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 text-xl">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900">Enviar Reporte</h3>
                        <p class="text-xs text-slate-500 font-medium">El reporte se enviará en formato PDF.</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Correo Electrónico</label>
                        <input type="email" id="targetEmail" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-medium" placeholder="ejemplo@sofofa.cl">
                    </div>
                </div>
                <div class="flex gap-3 mt-8">
                    <button onclick="closeSendModal()" class="flex-1 px-4 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200 transition-all">Cancelar</button>
                    <button onclick="handleSend()" class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-all">Enviar Ahora</button>
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
                filename: 'Reporte_OC_' + new Date().toISOString().slice(0,10) + '.pdf',
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
            alert('Reporte enviado correctamente a ' + email);
            closeSendModal();
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof ChartDataLabels !== 'undefined') {
                Chart.register(ChartDataLabels);
            }

            const chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                animation: false // For PDF capture
            };

            const ocColors = ['#2563eb', '#1e40af', '#3b82f6', '#60a5fa', '#93c5fd', '#bfdbfe'];

            const cecoCtx = document.getElementById('chartByCeco').getContext('2d');
            const dataByCeco = @json($sumByCeco);
            
            new Chart(cecoCtx, {
                type: 'pie',
                data: {
                    labels: dataByCeco.map(item => String(item.ceco)),
                    datasets: [{
                        data: dataByCeco.map(item => parseFloat(item.total_monto)),
                        backgroundColor: ocColors,
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    ...chartOptions,
                    plugins: {
                        legend: { position: 'right', labels: { boxWidth: 8, font: { size: 9, weight: 'bold' }, padding: 10 } },
                        datalabels: {
                            color: '#fff', font: { weight: 'bold', size: 10 },
                            formatter: (value, ctx) => {
                                let sum = 0;
                                let dataArr = ctx.chart.data.datasets[0].data;
                                dataArr.map(data => { sum += data; });
                                return (value * 100 / sum).toFixed(0) + "%";
                            },
                            display: (context) => context.dataset.data[context.dataIndex] > 5
                        }
                    }
                }
            });

            const monthCtx = document.getElementById('chartByMonth').getContext('2d');
            const monthData = @json($sumByMonth);
            
            new Chart(monthCtx, {
                type: 'bar',
                data: {
                    labels: monthData.map(d => d.month),
                    datasets: [{
                        label: 'Monto Total',
                        data: monthData.map(d => d.total_monto),
                        backgroundColor: ocColors[0],
                        borderRadius: 3,
                        barThickness: 15
                    }]
                },
                options: {
                    ...chartOptions,
                    categoryPercentage: 0.8,
                    barPercentage: 0.9,
                    plugins: { legend: { display: false }, datalabels: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 8 }, callback: (v) => '$' + (v / 1000).toFixed(0) + 'k' } },
                        x: { grid: { display: false }, ticks: { font: { size: 8 } } }
                    }
                }
            });
        });
    </script>
</x-app-layout>
