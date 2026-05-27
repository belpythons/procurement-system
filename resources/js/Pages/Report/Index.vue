<script setup>
import { ref, computed, onUnmounted, getCurrentInstance } from 'vue';
import { useForm, usePage, router } from '@inertiajs/vue3';
import ReportLayout from '@/Layouts/ReportLayout.vue';

const props = defineProps({
    procurements: { type: Array, default: () => [] },
    importLogs:   { type: Array, default: () => [] },
    stats:        { type: Object, default: () => ({}) },
});

const page = usePage();
const echoInstance = getCurrentInstance()?.appContext.config.globalProperties.$echo;

const isImporting   = ref(false);
const importLogId   = ref(null);
const progressPct   = ref(0);
const progressRows  = ref(0);
const progressTotal = ref(0);
const importResult  = ref(null);
const dragOver      = ref(false);
const importForm    = useForm({ file: null });
const fileInputRef  = ref(null);
const selectedFileName = ref('');
const searchQuery   = ref('');

const filteredProcurements = computed(() => {
    if (!searchQuery.value) return props.procurements;
    const q = searchQuery.value.toLowerCase();
    return props.procurements.filter(p =>
        p.kode_pengadaan.toLowerCase().includes(q) ||
        p.nama_barang.toLowerCase().includes(q) ||
        p.vendor.toLowerCase().includes(q) ||
        p.status.toLowerCase().includes(q)
    );
});

function onFileSelect(event) {
    const file = event.target.files?.[0];
    if (file) { importForm.file = file; selectedFileName.value = file.name; }
}
function onDrop(event) {
    dragOver.value = false;
    const file = event.dataTransfer?.files?.[0];
    if (file) { importForm.file = file; selectedFileName.value = file.name; }
}
function clearFile() {
    importForm.file = null; selectedFileName.value = '';
    if (fileInputRef.value) fileInputRef.value.value = '';
}

function submitImport() {
    if (!importForm.file) return;
    importForm.post('/report/import', {
        forceFormData: true, preserveScroll: true,
        onSuccess: () => {
            const flash = page.props.flash;
            if (flash?.import_log_id) {
                importLogId.value = flash.import_log_id;
                isImporting.value = true; progressPct.value = 0; importResult.value = null;
                listenToImportChannel(flash.import_log_id);
            }
            clearFile();
        },
    });
}

let echoChannel = null;
function listenToImportChannel(logId) {
    if (!echoInstance) { pollImportStatus(logId); return; }
    echoChannel = echoInstance.private(`import.${logId}`)
        .listen('.import.progress', (data) => {
            progressPct.value = data.percentage; progressRows.value = data.processed_rows; progressTotal.value = data.total_rows;
        })
        .listen('.import.completed', (data) => {
            progressPct.value = 100; isImporting.value = false;
            importResult.value = { success_count: data.success_count, failure_count: data.failure_count, error_file_url: data.error_file_url };
            setTimeout(() => router.reload({ only: ['procurements', 'importLogs', 'stats'] }), 1000);
        });
}
function pollImportStatus(logId) {
    const interval = setInterval(() => {
        router.reload({ only: ['importLogs', 'procurements', 'stats'], onSuccess: () => {
            const log = props.importLogs.find(l => l.id === logId);
            if (log && log.status !== 'processing') {
                clearInterval(interval); isImporting.value = false; progressPct.value = 100;
                importResult.value = { success_count: log.success_count, failure_count: log.failure_count, error_file_url: log.has_error_log ? `/report/error-log/${logId}` : null };
            }
        }});
    }, 3000);
}
function stopListening() { if (echoChannel && importLogId.value) { echoInstance?.leave(`import.${importLogId.value}`); echoChannel = null; } }
onUnmounted(() => stopListening());

const statusConfig = {
    'RP':    { cls: 'bg-[#214da0] text-white',      label: 'Request Purchasing' },
    'TE':    { cls: 'bg-[#3fb92f] text-white',      label: 'Technical Evaluation' },
    'RE-TE': { cls: 'bg-[#ff961f] text-white',      label: 'Re-Technical Evaluation' },
    'PO':    { cls: 'bg-[#ff0095] text-white',      label: 'Purchase Order' },
};
function getStatus(status) { return statusConfig[status] || { cls: 'bg-gray-500 text-white', label: status }; }
</script>

<template>
<ReportLayout>
<div class="space-y-6">

    <!-- STATS CARDS — Matches dashboard.blade.php layout -->
    <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-[15px]">
        <div class="bg-[#1f2937] text-white rounded-[18px] p-[15px] flex items-center gap-[12px] shadow-[0_5px_15px_rgba(0,0,0,0.15)] min-h-[95px] transition duration-300 hover:translate-y-[-5px] hover:scale-[1.02]">
            <i class="bi bi-database-fill text-[40px]"></i>
            <div>
                <div class="text-[14px] opacity-90">Semua Data</div>
                <div class="text-[23px] font-extrabold">{{ stats.total || 0 }}</div>
            </div>
        </div>
        <div class="bg-[#214da0] text-white rounded-[18px] p-[15px] flex items-center gap-[12px] shadow-[0_5px_15px_rgba(0,0,0,0.15)] min-h-[95px] transition duration-300 hover:translate-y-[-5px] hover:scale-[1.02]">
            <i class="bi bi-file-earmark-text text-[40px]"></i>
            <div>
                <div class="text-[14px] opacity-90">Total RP</div>
                <div class="text-[23px] font-extrabold">{{ stats.rp || 0 }}</div>
            </div>
        </div>
        <div class="bg-[#3fb92f] text-white rounded-[18px] p-[15px] flex items-center gap-[12px] shadow-[0_5px_15px_rgba(0,0,0,0.15)] min-h-[95px] transition duration-300 hover:translate-y-[-5px] hover:scale-[1.02]">
            <i class="bi bi-clipboard-check text-[40px]"></i>
            <div>
                <div class="text-[14px] opacity-90">Total TE</div>
                <div class="text-[23px] font-extrabold">{{ stats.te || 0 }}</div>
            </div>
        </div>
        <div class="bg-[#ff961f] text-white rounded-[18px] p-[15px] flex items-center gap-[12px] shadow-[0_5px_15px_rgba(0,0,0,0.15)] min-h-[95px] transition duration-300 hover:translate-y-[-5px] hover:scale-[1.02]">
            <i class="bi bi-arrow-repeat text-[40px]"></i>
            <div>
                <div class="text-[14px] opacity-90">Total Re-TE</div>
                <div class="text-[23px] font-extrabold">{{ stats.rete || 0 }}</div>
            </div>
        </div>
        <div class="bg-[#ff0095] text-white rounded-[18px] p-[15px] flex items-center gap-[12px] shadow-[0_5px_15px_rgba(0,0,0,0.15)] min-h-[95px] transition duration-300 hover:translate-y-[-5px] hover:scale-[1.02]">
            <i class="bi bi-bag-check text-[40px]"></i>
            <div>
                <div class="text-[14px] opacity-90">Total PO</div>
                <div class="text-[23px] font-extrabold">{{ stats.po || 0 }}</div>
            </div>
        </div>
    </section>

    <!-- IMPORT SECTION — styled like .card-box -->
    <section class="bg-white/95 backdrop-blur-[6px] rounded-[20px] p-[25px] shadow-[0_5px_15px_rgba(0,0,0,0.10)]">
        <h3 class="text-[22px] font-bold text-[#2f3f52] mb-4">Import Data</h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Dropzone -->
            <div @dragover.prevent="dragOver = true" @dragleave.prevent="dragOver = false" @drop.prevent="onDrop" @click="fileInputRef?.click()"
                :class="['border-2 border-dashed rounded-[12px] p-8 text-center cursor-pointer transition ease-in-out duration-150', dragOver ? 'border-indigo-400 bg-indigo-50/50' : 'border-gray-300 bg-gray-50/80 hover:border-gray-400 hover:bg-gray-100/80']">
                <input ref="fileInputRef" type="file" accept=".xlsx,.xls,.csv" class="hidden" @change="onFileSelect">
                <i class="bi bi-cloud-arrow-up text-[40px] text-gray-400"></i>
                <p v-if="!selectedFileName" class="mt-2 text-sm font-medium text-gray-600">Drag & drop atau klik untuk upload</p>
                <p v-if="!selectedFileName" class="text-xs text-gray-500 mt-1">Format: .xlsx, .xls, .csv (Maks 50MB)</p>
                <div v-if="selectedFileName" class="mt-2">
                    <p class="text-sm font-semibold text-gray-800">{{ selectedFileName }}</p>
                    <button @click.stop="clearFile" class="text-xs text-red-500 font-semibold mt-1 hover:underline">Hapus file</button>
                </div>
            </div>
            <!-- Action Buttons -->
            <div class="flex flex-col gap-3 justify-center">
                <button @click="submitImport" :disabled="!importForm.file || importForm.processing"
                    :class="['inline-flex items-center justify-center px-4 py-3 rounded-[10px] font-bold text-sm uppercase tracking-wider transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2',
                        importForm.file && !importForm.processing ? 'bg-[#0d6efd] text-white hover:bg-[#0b5ed7] active:bg-[#0a58ca]' : 'bg-gray-200 text-gray-400 cursor-not-allowed']">
                    <span v-if="importForm.processing">Mengupload...</span>
                    <span v-else><i class="bi bi-upload mr-2"></i>Upload & Import</span>
                </button>
                <a href="/report/template" class="inline-flex items-center justify-center px-4 py-3 bg-white border border-gray-300 rounded-[10px] font-bold text-sm text-gray-700 uppercase tracking-wider shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 text-center">
                    <i class="bi bi-file-earmark-excel mr-2"></i>Download Template
                </a>
                <div v-if="importForm.errors.file" class="bg-red-50 border border-red-200 rounded-[10px] p-3">
                    <p class="text-red-600 text-xs font-semibold">{{ importForm.errors.file }}</p>
                </div>
            </div>
        </div>

        <!-- PROGRESS BAR -->
        <div v-if="isImporting" class="mt-6 bg-gray-50/80 border border-gray-200 rounded-[10px] p-4">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-semibold text-gray-700">Importing...</span>
                <span class="text-sm font-bold text-gray-800">{{ progressPct }}%</span>
            </div>
            <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full bg-indigo-500 rounded-full transition-all duration-500 ease-out" :style="{ width: progressPct + '%' }"></div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Proses berjalan di background. Jangan tutup halaman ini.</p>
        </div>

        <!-- IMPORT RESULT -->
        <div v-if="importResult" class="mt-6 border rounded-[10px] p-4" :class="importResult.failure_count > 0 ? 'bg-amber-50 border-amber-200' : 'bg-green-50 border-green-200'">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-gray-800">Import Selesai</h4>
                    <p class="text-sm mt-1">
                        <span class="font-semibold text-green-700">{{ importResult.success_count }} baris berhasil</span>
                        <span v-if="importResult.failure_count > 0" class="font-semibold text-red-600 ml-2">· {{ importResult.failure_count }} baris gagal</span>
                    </p>
                </div>
                <a v-if="importResult.error_file_url" :href="'/report/error-log/' + importLogId"
                    class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-xs font-bold rounded-md hover:bg-red-500 transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    Download Error Log
                </a>
                <button @click="importResult = null; progressPct = 0;"
                    class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 text-xs font-bold text-gray-700 rounded-md hover:bg-gray-50 transition ease-in-out duration-150">
                    Tutup
                </button>
            </div>
        </div>
    </section>

    <!-- EXPORT SECTION — styled like .card-box -->
    <section class="bg-white/95 backdrop-blur-[6px] rounded-[20px] p-[25px] shadow-[0_5px_15px_rgba(0,0,0,0.10)]">
        <h3 class="text-[22px] font-bold text-[#2f3f52] mb-4">Export Laporan</h3>
        <div class="flex flex-wrap gap-3">
            <a href="/report/export/excel" class="inline-flex items-center px-4 py-3 bg-[#198754] hover:bg-[#157347] text-white text-sm font-bold uppercase tracking-wider rounded-[10px] transition ease-in-out duration-150">
                <i class="bi bi-file-earmark-excel mr-2"></i>Export Excel
            </a>
            <a href="/report/export/pdf" class="inline-flex items-center px-4 py-3 bg-[#dc3545] hover:bg-[#bb2d3b] text-white text-sm font-bold uppercase tracking-wider rounded-[10px] transition ease-in-out duration-150">
                <i class="bi bi-file-earmark-pdf mr-2"></i>Export PDF
            </a>
        </div>
    </section>

    <!-- DATA TABLE — styled like .card-box -->
    <section class="bg-white/95 backdrop-blur-[6px] rounded-[20px] shadow-[0_5px_15px_rgba(0,0,0,0.10)] overflow-hidden">
        <div class="p-[25px] pb-4 flex flex-wrap items-center justify-between gap-4">
            <h3 class="text-[26px] font-bold text-[#2f3f52]">
                Data Procurement
                <span class="text-sm font-normal text-gray-500 ml-1">({{ filteredProcurements.length }} records)</span>
            </h3>
            <input v-model="searchQuery" type="text" placeholder="Cari kode / barang / status..."
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-[10px] shadow-sm text-sm w-full md:w-72 px-3 py-2" />
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#2f3f52] text-white">
                    <tr>
                        <th class="px-4 py-[14px] text-center text-sm font-bold uppercase tracking-wider w-12 border-r border-white/10">No</th>
                        <th class="px-4 py-[14px] text-center text-sm font-bold uppercase tracking-wider border-r border-white/10">Kode</th>
                        <th class="px-4 py-[14px] text-center text-sm font-bold uppercase tracking-wider border-r border-white/10">Nama Barang</th>
                        <th class="px-4 py-[14px] text-center text-sm font-bold uppercase tracking-wider border-r border-white/10">Vendor</th>
                        <th class="px-4 py-[14px] text-center text-sm font-bold uppercase tracking-wider border-r border-white/10">Tgl TE</th>
                        <th class="px-4 py-[14px] text-center text-sm font-bold uppercase tracking-wider border-r border-white/10">Tgl RE-TE</th>
                        <th class="px-4 py-[14px] text-center text-sm font-bold uppercase tracking-wider border-r border-white/10">Tgl PO</th>
                        <th class="px-4 py-[14px] text-center text-sm font-bold uppercase tracking-wider w-24">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="(item, index) in filteredProcurements" :key="item.id" class="hover:bg-gray-50 transition ease-in-out duration-150">
                        <td class="px-4 py-[14px] whitespace-nowrap text-sm text-gray-500 text-center border-r border-gray-100">{{ index + 1 }}</td>
                        <td class="px-4 py-[14px] whitespace-nowrap text-sm font-semibold text-gray-800 text-center border-r border-gray-100">{{ item.kode_pengadaan }}</td>
                        <td class="px-4 py-[14px] whitespace-nowrap text-sm text-gray-600 text-center border-r border-gray-100">{{ item.nama_barang }}</td>
                        <td class="px-4 py-[14px] whitespace-nowrap text-sm text-gray-600 text-center border-r border-gray-100">{{ item.vendor }}</td>
                        <td class="px-4 py-[14px] whitespace-nowrap text-sm text-gray-500 text-center font-mono border-r border-gray-100">{{ item.tanggal_te || '—' }}</td>
                        <td class="px-4 py-[14px] whitespace-nowrap text-sm text-gray-500 text-center font-mono border-r border-gray-100">{{ item.tanggal_rete || '—' }}</td>
                        <td class="px-4 py-[14px] whitespace-nowrap text-sm text-gray-500 text-center font-mono border-r border-gray-100">{{ item.tanggal_po || '—' }}</td>
                        <td class="px-4 py-[14px] whitespace-nowrap text-center">
                            <span :class="getStatus(item.status).cls" class="inline-flex justify-center items-center w-[190px] h-[32px] text-[12px] font-semibold rounded-[8px]">
                                {{ getStatus(item.status).label }}
                            </span>
                        </td>
                    </tr>
                    <tr v-if="filteredProcurements.length === 0">
                        <td colspan="8" class="px-4 py-12 text-center">
                            <p class="text-sm text-gray-500">{{ searchQuery ? 'Tidak ditemukan hasil pencarian' : 'Belum ada data procurement' }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ searchQuery ? 'Coba kata kunci lain' : 'Mulai dengan mengimport data dari file Excel' }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- IMPORT HISTORY — styled like .card-box -->
    <section v-if="importLogs.length > 0" class="bg-white/95 backdrop-blur-[6px] rounded-[20px] p-[25px] shadow-[0_5px_15px_rgba(0,0,0,0.10)]">
        <h3 class="text-[22px] font-bold text-[#2f3f52] mb-4">Riwayat Import</h3>
        <div class="space-y-3">
            <div v-for="log in importLogs" :key="log.id"
                class="border border-gray-200 rounded-[12px] p-4 flex flex-wrap items-center gap-4"
                :class="{ 'bg-green-50': log.status === 'completed' && log.failure_count === 0, 'bg-amber-50': log.status === 'completed' && log.failure_count > 0, 'bg-blue-50': log.status === 'processing', 'bg-red-50': log.status === 'failed' }">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ log.file_name }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ log.created_at }}</p>
                </div>
                <div class="text-right text-xs space-y-0.5">
                    <p class="font-semibold text-green-700">✓ {{ log.success_count }} berhasil</p>
                    <p v-if="log.failure_count > 0" class="font-semibold text-red-600">✗ {{ log.failure_count }} gagal</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold"
                    :class="{ 'bg-green-100 text-green-800': log.status === 'completed', 'bg-blue-100 text-blue-800': log.status === 'processing', 'bg-red-100 text-red-800': log.status === 'failed' }">
                    {{ log.status }}
                </span>
                <a v-if="log.has_error_log" :href="'/report/error-log/' + log.id"
                    class="inline-flex items-center px-2.5 py-1.5 bg-red-600 text-white text-xs font-bold rounded-md hover:bg-red-500 transition ease-in-out duration-150">
                    Error Log
                </a>
            </div>
        </div>
    </section>

</div>
</ReportLayout>
</template>
