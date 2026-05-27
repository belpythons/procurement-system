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
    'RP':    { cls: 'bg-blue-100 text-blue-800',    label: 'RP' },
    'TE':    { cls: 'bg-green-100 text-green-800',  label: 'TE' },
    'RE-TE': { cls: 'bg-amber-100 text-amber-800',  label: 'RE-TE' },
    'PO':    { cls: 'bg-purple-100 text-purple-800', label: 'PO' },
};
function getStatus(status) { return statusConfig[status] || { cls: 'bg-gray-100 text-gray-800', label: status }; }
</script>

<template>
<ReportLayout>
<div class="space-y-6">

    <!-- STATS CARDS -->
    <section class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg shadow-sm p-5 text-center">
            <p class="text-2xl font-semibold text-gray-800">{{ stats.total || 0 }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Data</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-5 text-center border-t-4 border-blue-400">
            <p class="text-2xl font-semibold text-blue-600">{{ stats.rp || 0 }}</p>
            <p class="text-sm text-gray-500 mt-1">Status RP</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-5 text-center border-t-4 border-green-400">
            <p class="text-2xl font-semibold text-green-600">{{ stats.te || 0 }}</p>
            <p class="text-sm text-gray-500 mt-1">Status TE</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-5 text-center border-t-4 border-amber-400">
            <p class="text-2xl font-semibold text-amber-600">{{ stats.rete || 0 }}</p>
            <p class="text-sm text-gray-500 mt-1">Status RE-TE</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-5 text-center border-t-4 border-purple-400">
            <p class="text-2xl font-semibold text-purple-600">{{ stats.po || 0 }}</p>
            <p class="text-sm text-gray-500 mt-1">Status PO</p>
        </div>
    </section>

    <!-- IMPORT SECTION -->
    <section class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Import Data</h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Dropzone -->
            <div @dragover.prevent="dragOver = true" @dragleave.prevent="dragOver = false" @drop.prevent="onDrop" @click="fileInputRef?.click()"
                :class="['border-2 border-dashed rounded-lg p-8 text-center cursor-pointer transition ease-in-out duration-150', dragOver ? 'border-indigo-400 bg-indigo-50' : 'border-gray-300 bg-gray-50 hover:border-gray-400 hover:bg-gray-100']">
                <input ref="fileInputRef" type="file" accept=".xlsx,.xls,.csv" class="hidden" @change="onFileSelect">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                <p v-if="!selectedFileName" class="mt-2 text-sm font-medium text-gray-600">Drag & drop atau klik untuk upload</p>
                <p v-if="!selectedFileName" class="text-xs text-gray-500 mt-1">Format: .xlsx, .xls, .csv (Maks 50MB)</p>
                <div v-if="selectedFileName" class="mt-2">
                    <p class="text-sm font-medium text-gray-800">{{ selectedFileName }}</p>
                    <button @click.stop="clearFile" class="text-xs text-red-500 font-medium mt-1 hover:underline">Hapus file</button>
                </div>
            </div>
            <!-- Action Buttons -->
            <div class="flex flex-col gap-3 justify-center">
                <button @click="submitImport" :disabled="!importForm.file || importForm.processing"
                    :class="['inline-flex items-center justify-center px-4 py-2.5 rounded-md font-semibold text-xs uppercase tracking-widest transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2',
                        importForm.file && !importForm.processing ? 'bg-gray-800 text-white hover:bg-gray-700 active:bg-gray-900' : 'bg-gray-200 text-gray-400 cursor-not-allowed']">
                    <span v-if="importForm.processing">Mengupload...</span>
                    <span v-else>Upload & Import</span>
                </button>
                <a href="/report/template" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 text-center">
                    Download Template
                </a>
                <div v-if="importForm.errors.file" class="bg-red-50 border border-red-200 rounded-md p-3">
                    <p class="text-red-600 text-xs font-medium">{{ importForm.errors.file }}</p>
                </div>
            </div>
        </div>

        <!-- PROGRESS BAR -->
        <div v-if="isImporting" class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-700">Importing...</span>
                <span class="text-sm font-semibold text-gray-800">{{ progressPct }}%</span>
            </div>
            <div class="w-full h-2.5 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full bg-indigo-500 rounded-full transition-all duration-500 ease-out" :style="{ width: progressPct + '%' }"></div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Proses berjalan di background. Jangan tutup halaman ini.</p>
        </div>

        <!-- IMPORT RESULT -->
        <div v-if="importResult" class="mt-6 border rounded-lg p-4" :class="importResult.failure_count > 0 ? 'bg-amber-50 border-amber-200' : 'bg-green-50 border-green-200'">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-gray-800">Import Selesai</h4>
                    <p class="text-sm mt-1">
                        <span class="font-medium text-green-700">{{ importResult.success_count }} baris berhasil</span>
                        <span v-if="importResult.failure_count > 0" class="font-medium text-red-600 ml-2">· {{ importResult.failure_count }} baris gagal</span>
                    </p>
                </div>
                <a v-if="importResult.error_file_url" :href="'/report/error-log/' + importLogId"
                    class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-xs font-semibold rounded-md hover:bg-red-500 transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    Download Error Log
                </a>
                <button @click="importResult = null; progressPct = 0;"
                    class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 text-xs font-semibold text-gray-700 rounded-md hover:bg-gray-50 transition ease-in-out duration-150">
                    Tutup
                </button>
            </div>
        </div>
    </section>

    <!-- EXPORT SECTION -->
    <section class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Export Laporan</h3>
        <div class="flex flex-wrap gap-3">
            <a href="/report/export/excel" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Export Excel
            </a>
            <a href="/report/export/pdf" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Export PDF
            </a>
        </div>
    </section>

    <!-- DATA TABLE -->
    <section class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 pb-4 flex flex-wrap items-center justify-between gap-4">
            <h3 class="text-lg font-semibold text-gray-800">
                Data Procurement
                <span class="text-sm font-normal text-gray-500 ml-1">({{ filteredProcurements.length }} records)</span>
            </h3>
            <input v-model="searchQuery" type="text" placeholder="Cari kode / barang / vendor..."
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm w-full md:w-72 px-3 py-2" />
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl TE</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl RE-TE</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl PO</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="(item, index) in filteredProcurements" :key="item.id" class="hover:bg-gray-50 transition ease-in-out duration-150">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ index + 1 }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-800">{{ item.kode_pengadaan }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ item.nama_barang }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ item.vendor }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 text-center font-mono">{{ item.tanggal_te || '—' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 text-center font-mono">{{ item.tanggal_rete || '—' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 text-center font-mono">{{ item.tanggal_po || '—' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <span :class="getStatus(item.status).cls" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
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

    <!-- IMPORT HISTORY -->
    <section v-if="importLogs.length > 0" class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Import</h3>
        <div class="space-y-3">
            <div v-for="log in importLogs" :key="log.id"
                class="border border-gray-200 rounded-lg p-4 flex flex-wrap items-center gap-4"
                :class="{ 'bg-green-50': log.status === 'completed' && log.failure_count === 0, 'bg-amber-50': log.status === 'completed' && log.failure_count > 0, 'bg-blue-50': log.status === 'processing', 'bg-red-50': log.status === 'failed' }">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ log.file_name }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ log.created_at }}</p>
                </div>
                <div class="text-right text-xs space-y-0.5">
                    <p class="font-medium text-green-700">✓ {{ log.success_count }} berhasil</p>
                    <p v-if="log.failure_count > 0" class="font-medium text-red-600">✗ {{ log.failure_count }} gagal</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="{ 'bg-green-100 text-green-800': log.status === 'completed', 'bg-blue-100 text-blue-800': log.status === 'processing', 'bg-red-100 text-red-800': log.status === 'failed' }">
                    {{ log.status }}
                </span>
                <a v-if="log.has_error_log" :href="'/report/error-log/' + log.id"
                    class="inline-flex items-center px-2.5 py-1.5 bg-red-600 text-white text-xs font-medium rounded-md hover:bg-red-500 transition ease-in-out duration-150">
                    Error Log
                </a>
            </div>
        </div>
    </section>

</div>
</ReportLayout>
</template>
