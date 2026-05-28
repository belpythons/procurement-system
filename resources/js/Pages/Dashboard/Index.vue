<script setup>
import { ref, computed, watch } from 'vue';
import { useForm, usePage, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    procurements: { type: Array, default: () => [] },
    totalRP:      { type: Number, default: 0 },
    totalTE:      { type: Number, default: 0 },
    totalRETE:    { type: Number, default: 0 },
    totalPO:      { type: Number, default: 0 },
    filters:      { type: Object, default: () => ({}) },
});

const page = usePage();

// Reactive Filter states
const searchQuery = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || null);

// Dynamic filtering on client-side for ultra-fast response
const filteredProcurements = computed(() => {
    let data = props.procurements;
    if (statusFilter.value) {
        data = data.filter(p => p.status === statusFilter.value);
    }
    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        data = data.filter(p =>
            p.kode_pengadaan.toLowerCase().includes(q) ||
            (p.nama_barang && p.nama_barang.toLowerCase().includes(q)) ||
            (p.vendor && p.vendor.toLowerCase().includes(q)) ||
            p.status.toLowerCase().includes(q)
        );
    }
    return data;
});

// Toast / Flash handling
const showToast = ref(!!page.props.flash?.success);
const toastMessage = computed(() => page.props.flash?.success || '');
watch(() => page.props.flash?.success, (newVal) => {
    if (newVal) {
        showToast.value = true;
        setTimeout(() => { showToast.value = false; }, 3000);
    }
});

// Modal state
const showEditModal = ref(false);
const editForm = useForm({
    id: '',
    status: 'RP',
    kode_pengadaan: '',
    nama_barang: '',
    vendor: '',
    quantity: '',
    departemen: '',
    keterangan: '',
    hasil_evaluasi: '',
    catatan: '',
    tanggal: '',
});

// Helper for caching dates for Edit Modal
const datesCache = ref({ RP: '', TE: '', RETE: '', PO: '' });

function openEditModal(item) {
    editForm.clearErrors();
    editForm.id = item.id;
    editForm.status = item.status;
    editForm.kode_pengadaan = item.kode_pengadaan;
    editForm.nama_barang = item.nama_barang || '';
    editForm.vendor = item.vendor || '';
    editForm.quantity = item.quantity || '';
    editForm.departemen = item.departemen || '';
    editForm.keterangan = item.keterangan || '';
    editForm.hasil_evaluasi = item.hasil_evaluasi || '';
    editForm.catatan = item.catatan || '';

    datesCache.value.RP = item.tanggal_in ? item.tanggal_in.split('T')[0] : '';
    datesCache.value.TE = item.tanggal_te ? item.tanggal_te.split('T')[0] : '';
    datesCache.value.RETE = item.tanggal_rete ? item.tanggal_rete.split('T')[0] : '';
    datesCache.value.PO = item.tanggal_po ? item.tanggal_po.split('T')[0] : '';

    updateModalDate(item.status);
    showEditModal.value = true;
}

function updateModalDate(status) {
    if (status === 'RP') editForm.tanggal = datesCache.value.RP || new Date().toISOString().split('T')[0];
    else if (status === 'TE') editForm.tanggal = datesCache.value.TE || new Date().toISOString().split('T')[0];
    else if (status === 'RE-TE') editForm.tanggal = datesCache.value.RETE || new Date().toISOString().split('T')[0];
    else if (status === 'PO') editForm.tanggal = datesCache.value.PO || new Date().toISOString().split('T')[0];
}

watch(() => editForm.status, (newStatus) => {
    updateModalDate(newStatus);
});

function submitUpdate() {
    editForm.put(`/procurement/update/${editForm.id}`, {
        onSuccess: () => {
            showEditModal.value = false;
        }
    });
}

// Delete Modal state
const showDeleteModal = ref(false);
const deleteId = ref(null);

function confirmDelete(id) {
    deleteId.value = id;
    showDeleteModal.value = true;
}

function submitDelete() {
    if (!deleteId.value) return;
    router.post(`/procurement/delete/${deleteId.value}`, {}, {
        onSuccess: () => {
            showDeleteModal.value = false;
            deleteId.value = null;
        }
    });
}

// Phase automation approvals/rejects
function approvePhase(id) {
    router.post(`/procurement/approve-phase/${id}`, {}, {
        preserveScroll: true
    });
}

function approvePhaseWithTarget(id, target) {
    router.post(`/procurement/approve-phase/${id}`, { target }, {
        preserveScroll: true
    });
}

function rejectPhase(id) {
    router.post(`/procurement/reject-phase/${id}`, {}, {
        preserveScroll: true
    });
}

// Helper formatting tanggal
function formatTanggal(item) {
    const d = item.tanggal_in || item.tanggal_te || item.tanggal_rete || item.tanggal_po;
    if (!d) return '-';
    return d.split('T')[0];
}
</script>

<template>
    <AuthenticatedLayout title="Selamat Datang, Admin">
        <!-- Toast Success -->
        <Transition name="slide">
            <div v-if="showToast" class="fixed top-5 left-1/2 -translate-x-1/2 bg-white min-w-[320px] max-w-[450px] p-4 rounded-xl flex items-center gap-3 shadow-lg z-[9999] border-l-6 border-green-500">
                <i class="bi bi-check-circle-fill text-[24px] text-green-500"></i>
                <div class="flex-1 text-sm font-semibold text-gray-800">{{ toastMessage }}</div>
                <div class="text-xl cursor-pointer text-gray-500 hover:text-gray-700" @click="showToast = false">×</div>
            </div>
        </Transition>

        <!-- Stats Cards -->
        <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
            <div class="card-stat bg-[#1f2937] text-white rounded-2xl p-4 flex items-center gap-3 shadow-md hover:-translate-y-1 hover:scale-[1.02] transition duration-300 cursor-pointer" @click="statusFilter = null">
                <i class="bi bi-database-fill text-[40px]"></i>
                <div>
                    <div class="text-xs opacity-90">Semua Data</div>
                    <h4 class="text-xl font-bold">{{ totalRP + totalTE + totalRETE + totalPO }}</h4>
                </div>
            </div>
            <div class="card-stat bg-[#214da0] text-white rounded-2xl p-4 flex items-center gap-3 shadow-md hover:-translate-y-1 hover:scale-[1.02] transition duration-300 cursor-pointer" @click="statusFilter = 'RP'">
                <i class="bi bi-file-earmark-text text-[40px]"></i>
                <div>
                    <div class="text-xs opacity-90">Total RP</div>
                    <h4 class="text-xl font-bold">{{ totalRP }}</h4>
                </div>
            </div>
            <div class="card-stat bg-[#3fb92f] text-white rounded-2xl p-4 flex items-center gap-3 shadow-md hover:-translate-y-1 hover:scale-[1.02] transition duration-300 cursor-pointer" @click="statusFilter = 'TE'">
                <i class="bi bi-clipboard-check text-[40px]"></i>
                <div>
                    <div class="text-xs opacity-90">Total TE</div>
                    <h4 class="text-xl font-bold">{{ totalTE }}</h4>
                </div>
            </div>
            <div class="card-stat bg-[#ff961f] text-white rounded-2xl p-4 flex items-center gap-3 shadow-md hover:-translate-y-1 hover:scale-[1.02] transition duration-300 cursor-pointer" @click="statusFilter = 'RE-TE'">
                <i class="bi bi-arrow-repeat text-[40px]"></i>
                <div>
                    <div class="text-xs opacity-90">Total Re-TE</div>
                    <h4 class="text-xl font-bold">{{ totalRETE }}</h4>
                </div>
            </div>
            <div class="card-stat bg-[#ff0095] text-white rounded-2xl p-4 flex items-center gap-3 shadow-md hover:-translate-y-1 hover:scale-[1.02] transition duration-300 cursor-pointer" @click="statusFilter = 'PO'">
                <i class="bi bi-bag-check text-[40px]"></i>
                <div>
                    <div class="text-xs opacity-90">Total PO</div>
                    <h4 class="text-xl font-bold">{{ totalPO }}</h4>
                </div>
            </div>
        </section>

        <!-- Card Box (Table Section) -->
        <section class="bg-white/95 backdrop-blur-[6px] rounded-2xl p-6 shadow-md mt-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
                <h3 class="text-[24px] font-extrabold text-[#2f3f52]">Data Procurement</h3>
                <div class="flex flex-wrap gap-2">
                    <input type="text" v-model="searchQuery" placeholder="Cari kode / barang / status..." class="h-10 w-64 px-4 border border-gray-300 rounded-lg text-sm outline-none focus:border-[#214da0] focus:ring-1 focus:ring-[#214da0] transition duration-150" />
                    <Link href="/procurement/create" class="h-10 px-4 bg-[#214da0] hover:bg-[#1b3d80] text-white font-bold rounded-lg text-sm inline-flex items-center justify-center gap-1 shadow-sm transition duration-150 uppercase tracking-wider">
                        <i class="bi bi-plus-lg"></i> Tambah Data
                    </Link>
                </div>
            </div>

            <!-- Table Responsive -->
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="w-full text-left border-collapse bg-white">
                    <thead>
                        <tr class="bg-[#2f3f52] text-white text-center text-sm font-semibold">
                            <th class="p-4 w-12">No</th>
                            <th class="p-4">Kode Pengadaan</th>
                            <th class="p-4">Nama Barang</th>
                            <th class="p-4">Vendor</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Tanggal</th>
                            <th class="p-4 w-60">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-center text-sm">
                        <tr v-for="(item, index) in filteredProcurements" :key="item.id" class="hover:bg-gray-50/50 transition">
                            <td class="p-4 font-semibold text-gray-700">{{ index + 1 }}</td>
                            <td class="p-4 font-bold text-gray-800">{{ item.kode_pengadaan }}</td>
                            <td class="p-4 text-gray-700">{{ item.nama_barang }}</td>
                            <td class="p-4 text-gray-700">{{ item.vendor }}</td>
                            <td class="p-4">
                                <span v-if="item.status === 'RP'" class="inline-flex items-center px-3 py-1 bg-[#214da0] text-white font-semibold rounded-md text-xs uppercase tracking-wide">RP</span>
                                <span v-else-if="item.status === 'TE'" class="inline-flex items-center px-3 py-1 bg-[#3fb92f] text-white font-semibold rounded-md text-xs uppercase tracking-wide">TE</span>
                                <span v-else-if="item.status === 'RE-TE'" class="inline-flex items-center px-3 py-1 bg-[#ff961f] text-white font-semibold rounded-md text-xs uppercase tracking-wide">RE-TE</span>
                                <span v-else-if="item.status === 'PO'" class="inline-flex items-center px-3 py-1 bg-[#ff0095] text-white font-semibold rounded-md text-xs uppercase tracking-wide">PO</span>
                            </td>
                            <td class="p-4 text-gray-600">{{ formatTanggal(item) }}</td>
                            <td class="p-4 flex items-center justify-center gap-1.5 flex-wrap">
                                <!-- Action Buttons -->
                                <button @click="openEditModal(item)" class="h-7 px-2.5 bg-[#ffc107] hover:bg-[#e0a800] text-white font-semibold rounded text-xs flex items-center gap-1 transition shadow-sm">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                                <button @click="confirmDelete(item.id)" class="h-7 px-2.5 bg-[#dc3545] hover:bg-[#c82333] text-white font-semibold rounded text-xs flex items-center gap-1 transition shadow-sm">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>

                                <!-- Phase Transitions -->
                                <button v-if="item.status === 'RP'" @click="approvePhase(item.id)" class="h-7 px-2.5 bg-[#214da0] hover:bg-[#1b3d80] text-white font-semibold rounded text-xs transition shadow-sm">
                                    Approve to TE
                                </button>
                                <template v-else-if="item.status === 'TE'">
                                    <button @click="approvePhaseWithTarget(item.id, 'RE-TE')" class="h-7 px-2.5 bg-[#ff961f] hover:bg-[#e68519] text-white font-semibold rounded text-xs transition shadow-sm">
                                        Approve to RE-TE
                                    </button>
                                    <button @click="approvePhaseWithTarget(item.id, 'PO')" class="h-7 px-2.5 bg-[#ff0095] hover:bg-[#e60086] text-white font-semibold rounded text-xs transition shadow-sm">
                                        Approve to PO
                                    </button>
                                    <button @click="rejectPhase(item.id)" class="h-7 px-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded text-xs transition shadow-sm">
                                        Tolak
                                    </button>
                                </template>
                                <template v-else-if="item.status === 'RE-TE'">
                                    <button @click="approvePhase(item.id)" class="h-7 px-2.5 bg-[#ff0095] hover:bg-[#e60086] text-white font-semibold rounded text-xs transition shadow-sm">
                                        Approve to PO
                                    </button>
                                    <button @click="rejectPhase(item.id)" class="h-7 px-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded text-xs transition shadow-sm">
                                        Tolak
                                    </button>
                                </template>
                            </td>
                        </tr>
                        <tr v-if="filteredProcurements.length === 0">
                            <td colspan="7" class="p-6 text-gray-500 font-medium">Data kosong</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- MODAL EDIT (VUE 3 REACTIVE) -->
        <Transition name="fade">
            <div v-if="showEditModal" class="fixed inset-0 bg-black/50 backdrop-blur-[2px] z-[999] flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl w-full max-w-[500px] max-h-[85vh] overflow-y-auto p-6 shadow-2xl relative">
                    <h3 class="text-xl font-bold text-[#2f3f52] mb-4">Edit Data Procurement</h3>

                    <!-- Global Validation Errors -->
                    <div v-if="Object.keys(editForm.errors).length > 0" class="mb-4 bg-red-50 border border-red-200 text-red-700 p-3 rounded-lg text-xs space-y-1">
                        <div v-for="(err, key) in editForm.errors" :key="key">
                            · {{ err }}
                        </div>
                    </div>

                    <form @submit.prevent="submitUpdate" class="space-y-4 text-left">
                        <!-- Status Dropdown -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                            <select v-model="editForm.status" class="w-full h-10 px-3 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-[#214da0] focus:border-[#214da0] text-sm">
                                <option value="RP">Request Purchasing (RP)</option>
                                <option value="TE">Technical Evaluation (TE)</option>
                                <option value="RE-TE">Re-Technical Evaluation (RE-TE)</option>
                                <option value="PO">Purchase Order (PO)</option>
                            </select>
                        </div>

                        <!-- Kode Pengadaan -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kode Pengadaan</label>
                            <input type="text" v-model="editForm.kode_pengadaan" required class="w-full h-10 px-3 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-[#214da0] focus:border-[#214da0] text-sm" />
                        </div>

                        <!-- Dynamic Fields with v-if -->
                        <div v-if="['RP', 'RE-TE', 'PO'].includes(editForm.status)">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Barang</label>
                            <input type="text" v-model="editForm.nama_barang" class="w-full h-10 px-3 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-[#214da0] focus:border-[#214da0] text-sm" />
                        </div>

                        <div v-if="['TE', 'RE-TE', 'PO'].includes(editForm.status)">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Vendor</label>
                            <input type="text" v-model="editForm.vendor" class="w-full h-10 px-3 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-[#214da0] focus:border-[#214da0] text-sm" />
                        </div>

                        <div v-if="['RP', 'PO'].includes(editForm.status)">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Quantity</label>
                            <input type="text" v-model="editForm.quantity" class="w-full h-10 px-3 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-[#214da0] focus:border-[#214da0] text-sm" />
                        </div>

                        <div v-if="['RP', 'PO'].includes(editForm.status)">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Departemen</label>
                            <input type="text" v-model="editForm.departemen" class="w-full h-10 px-3 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-[#214da0] focus:border-[#214da0] text-sm" />
                        </div>

                        <div v-if="['RP', 'PO'].includes(editForm.status)">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Keterangan</label>
                            <textarea v-model="editForm.keterangan" rows="2" class="w-full p-3 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-[#214da0] focus:border-[#214da0] text-sm resize-y"></textarea>
                        </div>

                        <div v-if="['TE', 'PO'].includes(editForm.status)">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Hasil Evaluasi</label>
                            <textarea v-model="editForm.hasil_evaluasi" rows="2" class="w-full p-3 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-[#214da0] focus:border-[#214da0] text-sm resize-y"></textarea>
                        </div>

                        <div v-if="['RE-TE', 'PO'].includes(editForm.status)">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan</label>
                            <textarea v-model="editForm.catatan" rows="2" class="w-full p-3 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-[#214da0] focus:border-[#214da0] text-sm resize-y"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal</label>
                            <input type="date" v-model="editForm.tanggal" class="w-full h-10 px-3 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-[#214da0] focus:border-[#214da0] text-sm" />
                        </div>

                        <div class="pt-4 space-y-2">
                            <button type="submit" :disabled="editForm.processing" class="w-full h-11 bg-[#214da0] hover:bg-[#1b3d80] text-white font-bold rounded-lg uppercase tracking-wider text-xs transition flex items-center justify-center gap-1 shadow-md">
                                <i class="bi bi-save"></i> Update Data
                            </button>
                            <button type="button" @click="showEditModal = false" class="w-full h-11 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg uppercase tracking-wider text-xs transition flex items-center justify-center gap-1 shadow-md">
                                Kembali
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Transition>

        <!-- DELETE CONFIRM MODAL -->
        <Transition name="fade">
            <div v-if="showDeleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-[2px] z-[9999] flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl w-full max-w-[400px] p-6 shadow-2xl text-center">
                    <div class="w-16 h-16 border-4 border-[#ff6b6b] text-[#ff6b6b] rounded-full flex items-center justify-center text-3xl font-extrabold mx-auto mb-4">!</div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">Yakin Hapus Data Ini?</h3>
                    <p class="text-sm text-gray-500 mb-6">Data yang dihapus tidak dapat dikembalikan lagi.</p>
                    <div class="flex gap-3 justify-center">
                        <button @click="showDeleteModal = false" class="h-10 px-6 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg text-sm transition">Batal</button>
                        <button @click="submitDelete" class="h-10 px-6 bg-[#dc3545] hover:bg-[#c82333] text-white font-semibold rounded-lg text-sm transition">Hapus</button>
                    </div>
                </div>
            </div>
        </Transition>
    </AuthenticatedLayout>
</template>

<style scoped>
.slide-enter-active, .slide-leave-active {
    transition: transform 0.4s ease, opacity 0.4s ease;
}
.slide-enter-from {
    transform: translate(-50%, -40px);
    opacity: 0;
}
.slide-leave-to {
    transform: translate(-50%, -20px);
    opacity: 0;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.25s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
