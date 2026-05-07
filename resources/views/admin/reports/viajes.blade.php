<x-app-layout>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    </head>

    <div class="py-4 px-6" id="report-container">
        <div class="max-w-7xl mx-auto">

            <!-- Header Compact - same structure as OC/Rendicion -->
            <div class="mb-4 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white text-lg shadow-lg shadow-indigo-100">
                        <i class="fas fa-plane"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight">Datos Viajes</h2>
                        <p class="text-[9px] text-slate-500 font-medium uppercase tracking-wider">Control de Movilidad y Viáticos</p>
                    </div>
                </div>
                <div class="flex gap-2 no-print">
                    <button onclick="exportToPDF()" class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg font-bold text-slate-700 hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm text-xs">
                        <i class="fas fa-file-pdf text-red-500 text-[10px]"></i>
                        <span>Descargar PDF</span>
                    </button>
                    <button onclick="openSendModal()" class="px-3 py-1.5 bg-indigo-600 border border-indigo-700 rounded-lg font-bold text-white hover:bg-indigo-700 transition-all flex items-center gap-2 shadow-sm text-xs">
                        <i class="fas fa-paper-plane text-[10px]"></i>
                        <span>Enviar Reporte</span>
                    </button>
                </div>
            </div>

            <!-- KPIs Compact -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2 mb-4">
                <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 to-indigo-300"></div>
                    <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total</div>
                    <div class="text-lg font-black text-slate-900">{{ $stats['total'] }}</div>
                </div>
                <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-amber-300"></div>
                    <div class="text-[8px] font-black text-amber-500 uppercase tracking-widest mb-0.5">Pendientes</div>
                    <div class="text-lg font-black text-slate-900">{{ $stats['pending'] }}</div>
                </div>
                <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-emerald-300"></div>
                    <div class="text-[8px] font-black text-emerald-500 uppercase tracking-widest mb-0.5">Aprobadas</div>
                    <div class="text-lg font-black text-slate-900">{{ $stats['approved'] }}</div>
                </div>
                <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-red-500 to-red-300"></div>
                    <div class="text-[8px] font-black text-red-500 uppercase tracking-widest mb-0.5">Rechazadas</div>
                    <div class="text-lg font-black text-slate-900">{{ $stats['rejected'] }}</div>
                </div>
                <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-teal-500 to-teal-300"></div>
                    <div class="text-[8px] font-black text-teal-500 uppercase tracking-widest mb-0.5">Gasto Total</div>
                    <div class="text-lg font-black text-slate-900">${{ number_format($stats['gasto_total'],0,',','.') }}</div>
                </div>
            </div>

            <!-- Charts grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

                <!-- Estado de Solicitudes -->
                <div class="bg-white p-5 rounded-[1.5rem] border border-slate-100 shadow-xl">
                    <div class="mb-3">
                        <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Estado de Solicitudes</h3>
                        <p class="text-[8px] text-slate-400 font-bold uppercase">Distribución porcentual</p>
                    </div>
                    <div class="flex items-center gap-4" style="height:150px">
                        <div class="relative flex-shrink-0" style="width:120px;height:120px">
                            <canvas id="canvas-status"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <span class="text-xl font-black text-slate-900">{{ $stats['total'] }}</span>
                                <span class="text-[7px] font-bold text-slate-400 uppercase tracking-tighter">Total</span>
                            </div>
                        </div>
                        <div id="status-legend" class="flex flex-col gap-1.5 flex-1 text-[10px] font-bold"></div>
                    </div>
                </div>

                <!-- Solicitudes por Mes -->
                <div class="bg-white p-5 rounded-[1.5rem] border border-slate-100 shadow-xl">
                    <div class="mb-3">
                        <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Solicitudes por Mes</h3>
                        <p class="text-[8px] text-slate-400 font-bold uppercase">Tendencia últimos 6 meses</p>
                    </div>
                    <div style="height:150px">
                        <canvas id="canvas-monthly"></canvas>
                    </div>
                </div>

                <!-- Top Destinos -->
                <div class="bg-white p-5 rounded-[1.5rem] border border-slate-100 shadow-xl">
                    <div class="mb-3">
                        <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Top Destinos</h3>
                        <p class="text-[8px] text-slate-400 font-bold uppercase">Lugares más visitados</p>
                    </div>
                    <div style="height:150px">
                        <canvas id="canvas-destinations"></canvas>
                    </div>
                </div>

                <!-- Distribución por Tipo -->
                <div class="bg-white p-5 rounded-[1.5rem] border border-slate-100 shadow-xl">
                    <div class="mb-3">
                        <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Distribución por Tipo</h3>
                        <p class="text-[8px] text-slate-400 font-bold uppercase">Internos vs Externos</p>
                    </div>
                    <div style="height:150px">
                        <canvas id="canvas-type"></canvas>
                    </div>
                </div>

                <!-- Distribución por CECO -->
                <div class="bg-white p-5 rounded-[1.5rem] border border-slate-100 shadow-xl">
                    <div class="mb-3">
                        <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Distribución por CECO</h3>
                        <p class="text-[8px] text-slate-400 font-bold uppercase">Cant. por centro de costos</p>
                    </div>
                    <div style="height:150px">
                        <canvas id="canvas-ceco"></canvas>
                    </div>
                </div>

                <!-- Gasto por CECO -->
                <div class="bg-white p-5 rounded-[1.5rem] border border-slate-100 shadow-xl">
                    <div class="mb-3">
                        <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Gasto Total por CECO</h3>
                        <p class="text-[8px] text-slate-400 font-bold uppercase">Pasajes, hoteles y traslados</p>
                    </div>
                    <div style="height:150px">
                        <canvas id="canvas-ceco-spending"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Enviar -->
    <div id="sendModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 text-xl">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900">Enviar Reporte</h3>
                        <p class="text-xs text-slate-500 font-medium">Se enviará un PDF con los datos de viajes.</p>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Correo Electrónico</label>
                    <input type="email" id="targetEmail" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm font-medium" placeholder="ejemplo@sofofa.cl">
                </div>
                <div class="flex gap-3 mt-6">
                    <button onclick="closeSendModal()" class="flex-1 px-4 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200 transition-all">Cancelar</button>
                    <button onclick="handleSend()" class="flex-1 px-4 py-3 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 transition-all">Enviar Ahora</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openSendModal()  { document.getElementById('sendModal').classList.remove('hidden'); }
        function closeSendModal() { document.getElementById('sendModal').classList.add('hidden'); }
        function handleSend() {
            const email = document.getElementById('targetEmail').value;
            if (!email) { alert('Ingrese un correo'); return; }
            alert('Reporte enviado a ' + email);
            closeSendModal();
        }

        async function exportToPDF() {
            const element = document.getElementById('report-container');
            document.querySelectorAll('.no-print').forEach(el => el.style.display = 'none');

            const canvases = element.querySelectorAll('canvas');
            const replacements = [];
            canvases.forEach(canvas => {
                const img = document.createElement('img');
                img.src = canvas.toDataURL('image/png', 1.0);
                img.style.width  = canvas.offsetWidth  + 'px';
                img.style.height = canvas.offsetHeight + 'px';
                img.style.display = 'block';
                canvas.parentNode.insertBefore(img, canvas);
                canvas.style.display = 'none';
                replacements.push({ canvas, img });
            });

            await html2pdf().set({
                margin: [8, 8, 8, 8],
                filename: 'Reporte_Viajes_' + new Date().toISOString().slice(0,10) + '.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true, scrollX: 0, scrollY: 0 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            }).from(element).save();

            replacements.forEach(({ canvas, img }) => { canvas.style.display = ''; img.remove(); });
            document.querySelectorAll('.no-print').forEach(el => el.style.display = '');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const C = { primary:'#6366f1', secondary:'#818cf8', accent:'#4f46e5', ok:'#10b981', warn:'#f59e0b', err:'#ef4444' };
            const base = { responsive:true, maintainAspectRatio:false, animation:false, plugins:{ legend:{ display:false } } };

            // Status donut
            const t = @json($stats);
            const leg = document.getElementById('status-legend');
            [['Aprobadas', t.approved, C.ok], ['Pendientes', t.pending, C.warn], ['Rechazadas', t.rejected, C.err]].forEach(([l,v,c]) => {
                leg.innerHTML += `<div class="flex justify-between items-center border-b border-slate-50 pb-0.5"><div class="flex items-center gap-1.5"><span class="inline-block w-2 h-2 rounded-full" style="background:${c}"></span>${l}</div><b class="text-slate-900">${v}</b></div>`;
            });
            new Chart(document.getElementById('canvas-status'), { type:'doughnut', data:{ labels:['Aprobadas','Pendientes','Rechazadas'], datasets:[{ data:[t.approved,t.pending,t.rejected], backgroundColor:[C.ok,C.warn,C.err], borderWidth:0, cutout:'78%' }] }, options:base });

            // Monthly bar
            const m = @json($mensual);
            new Chart(document.getElementById('canvas-monthly'), { type:'bar', data:{ labels:m.map(d=>d.mes), datasets:[{ data:m.map(d=>d.cantidad), backgroundColor:C.primary+'bb', borderRadius:5, maxBarThickness:35 }] }, options:{...base, scales:{ y:{beginAtZero:true,grid:{color:'#f1f5f9'},ticks:{font:{size:9}}}, x:{grid:{display:false},ticks:{font:{size:9}}} }} });

            // Destinations H-bar
            const dest = @json($destinos);
            new Chart(document.getElementById('canvas-destinations'), { type:'bar', data:{ labels:dest.map(d=>d.destino), datasets:[{ data:dest.map(d=>d.cantidad), backgroundColor:C.secondary+'bb', borderRadius:5, maxBarThickness:20 }] }, options:{...base, indexAxis:'y', scales:{ x:{beginAtZero:true,grid:{color:'#f1f5f9'},ticks:{font:{size:9}}}, y:{grid:{display:false},ticks:{font:{size:9}}} }} });

            // Type H-bar
            const tp = @json($tipos);
            new Chart(document.getElementById('canvas-type'), { type:'bar', data:{ labels:['Internos','Externos'], datasets:[{ data:[tp.internos,tp.externos], backgroundColor:[C.primary+'cc',C.secondary+'cc'], borderRadius:5, maxBarThickness:35 }] }, options:{...base, indexAxis:'y', scales:{ x:{beginAtZero:true,ticks:{font:{size:9}}}, y:{grid:{display:false},ticks:{font:{size:9}}} }} });

            // CECO pie
            const ce = @json($cecos);
            new Chart(document.getElementById('canvas-ceco'), { type:'pie', data:{ labels:ce.map(c=>c.ceco), datasets:[{ data:ce.map(c=>c.cantidad), backgroundColor:[C.primary,C.secondary,C.accent,'#f59e0b','#10b981','#64748b'], borderWidth:2, borderColor:'#fff' }] }, options:{...base, plugins:{ legend:{ display:true, position:'right', labels:{ boxWidth:8, font:{size:8}, padding:6 } } }} });

            // CECO spending bar
            const gs = @json($gastos_cecos);
            new Chart(document.getElementById('canvas-ceco-spending'), { type:'bar', data:{ labels:gs.map(g=>g.ceco), datasets:[{ data:gs.map(g=>g.total), backgroundColor:C.accent+'bb', borderRadius:5, maxBarThickness:35 }] }, options:{...base, scales:{ y:{beginAtZero:true,ticks:{font:{size:9},callback:v=>'$'+(v/1000).toFixed(0)+'k'}}, x:{ticks:{font:{size:9}}} }} });
        });
    </script>
</x-app-layout>
