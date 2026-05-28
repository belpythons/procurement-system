<script setup>
import { computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const form = useForm({
    status: 'RP',
    kode_pengadaan: '',
    nama_barang: '',
    vendor: '',
    quantity: '',
    departemen: '',
    keterangan: '',
    hasil_evaluasi: '',
    catatan: '',
    tanggal: new Date().toISOString().split('T')[0],
});

// Menentukan label dinamis untuk kode pengadaan sesuai fase
const labelKode = computed(() => {
    if (form.status === 'RP') return 'Kode RP';
    if (form.status === 'TE') return 'Kode TE';
    if (form.status === 'RE-TE') return 'Kode Re-TE';
    return 'Kode PO';
});

function submit() {
    form.post('/procurement/store');
}
</script>

<template>
    <AuthenticatedLayout title="Tambah Data Procurement">
        <div class="max-w-[580px] mx-auto bg-white/95 backdrop-blur-[6px] rounded-[20px] p-[35px] shadow-[0_15px_35px_rgba(0,0,0,0.15)] mt-6">
            
            <form @submit.prevent="submit" class="space-y-4 text-left">
                <!-- Dropdown Status Fase -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status Fase</label>
                    <select v-model="form.status" class="w-full h-11 px-4 border border-gray-300 rounded-[10px] bg-white focus:ring-2 focus:ring-[#214da0] focus:border-[#214da0] outline-none transition duration-150">
                        <option value="RP">Request Purchasing (RP)</option>
                        <option value="TE">Technical Evaluation (TE)</option>
                        <option value="RE-TE">Re-Technical Evaluation (RE-TE)</option>
                        <option value="PO">Purchase Order (PO)</option>
                    </select>
                    <span v-if="form.errors.status" class="text-red-500 text-xs font-semibold mt-1 block">
                        {{ form.errors.status }}
                    </span>
                </div>

                <!-- Kode Pengadaan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ labelKode }}</label>
                    <input type="text" v-model="form.kode_pengadaan" placeholder="Contoh: PRQ-001" class="w-full h-11 px-4 border border-gray-300 rounded-[10px] focus:ring-2 focus:ring-[#214da0] focus:border-[#214da0] outline-none transition duration-150" :class="{ 'border-red-500': form.errors.kode_pengadaan }" />
                    <span v-if="form.errors.kode_pengadaan" class="text-red-500 text-xs font-semibold mt-1 block">
                        {{ form.errors.kode_pengadaan }}
                    </span>
                </div>

                <!-- Nama Barang (Tampil pada RP, RE-TE, PO) -->
                <div v-if="['RP', 'RE-TE', 'PO'].includes(form.status)">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Barang</label>
                    <input type="text" v-model="form.nama_barang" placeholder="Nama barang" class="w-full h-11 px-4 border border-gray-300 rounded-[10px] focus:ring-2 focus:ring-[#214da0] focus:border-[#214da0] outline-none transition duration-150" :class="{ 'border-red-500': form.errors.nama_barang }" />
                    <span v-if="form.errors.nama_barang" class="text-red-500 text-xs font-semibold mt-1 block">
                        {{ form.errors.nama_barang }}
                    </span>
                </div>

                <!-- Vendor (Tampil pada TE, RE-TE, PO) -->
                <div v-if="['TE', 'RE-TE', 'PO'].includes(form.status)">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Vendor</label>
                    <input type="text" v-model="form.vendor" placeholder="Nama vendor" class="w-full h-11 px-4 border border-gray-300 rounded-[10px] focus:ring-2 focus:ring-[#214da0] focus:border-[#214da0] outline-none transition duration-150" :class="{ 'border-red-500': form.errors.vendor }" />
                    <span v-if="form.errors.vendor" class="text-red-500 text-xs font-semibold mt-1 block">
                        {{ form.errors.vendor }}
                    </span>
                </div>

                <!-- Quantity (Tampil pada RP, PO) -->
                <div v-if="['RP', 'PO'].includes(form.status)">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Quantity</label>
                    <input type="text" v-model="form.quantity" placeholder="Contoh: 10 Unit" class="w-full h-11 px-4 border border-gray-300 rounded-[10px] focus:ring-2 focus:ring-[#214da0] focus:border-[#214da0] outline-none transition duration-150" :class="{ 'border-red-500': form.errors.quantity }" />
                    <span v-if="form.errors.quantity" class="text-red-500 text-xs font-semibold mt-1 block">
                        {{ form.errors.quantity }}
                    </span>
                </div>

                <!-- Departemen (Tampil pada RP, PO) -->
                <div v-if="['RP', 'PO'].includes(form.status)">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Departemen</label>
                    <input type="text" v-model="form.departemen" placeholder="Departemen peminta" class="w-full h-11 px-4 border border-gray-300 rounded-[10px] focus:ring-2 focus:ring-[#214da0] focus:border-[#214da0] outline-none transition duration-150" :class="{ 'border-red-500': form.errors.departemen }" />
                    <span v-if="form.errors.departemen" class="text-red-500 text-xs font-semibold mt-1 block">
                        {{ form.errors.departemen }}
                    </span>
                </div>

                <!-- Keterangan (Tampil pada RP, PO) -->
                <div v-if="['RP', 'PO'].includes(form.status)">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Keterangan</label>
                    <textarea v-model="form.keterangan" placeholder="Keterangan pengadaan" rows="3" class="w-full p-4 border border-gray-300 rounded-[10px] focus:ring-2 focus:ring-[#214da0] focus:border-[#214da0] outline-none transition duration-150 resize-y" :class="{ 'border-red-500': form.errors.keterangan }"></textarea>
                    <span v-if="form.errors.keterangan" class="text-red-500 text-xs font-semibold mt-1 block">
                        {{ form.errors.keterangan }}
                    </span>
                </div>

                <!-- Hasil Evaluasi (Tampil pada TE, PO) -->
                <div v-if="['TE', 'PO'].includes(form.status)">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Hasil Evaluasi</label>
                    <textarea v-model="form.hasil_evaluasi" placeholder="Hasil evaluasi teknis" rows="3" class="w-full p-4 border border-gray-300 rounded-[10px] focus:ring-2 focus:ring-[#214da0] focus:border-[#214da0] outline-none transition duration-150 resize-y" :class="{ 'border-red-500': form.errors.hasil_evaluasi }"></textarea>
                    <span v-if="form.errors.hasil_evaluasi" class="text-red-500 text-xs font-semibold mt-1 block">
                        {{ form.errors.hasil_evaluasi }}
                    </span>
                </div>

                <!-- Catatan (Tampil pada RE-TE, PO) -->
                <div v-if="['RE-TE', 'PO'].includes(form.status)">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan</label>
                    <textarea v-model="form.catatan" placeholder="Catatan evaluasi ulang" rows="3" class="w-full p-4 border border-gray-300 rounded-[10px] focus:ring-2 focus:ring-[#214da0] focus:border-[#214da0] outline-none transition duration-150 resize-y" :class="{ 'border-red-500': form.errors.catatan }"></textarea>
                    <span v-if="form.errors.catatan" class="text-red-500 text-xs font-semibold mt-1 block">
                        {{ form.errors.catatan }}
                    </span>
                </div>

                <!-- Tanggal -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal</label>
                    <input type="date" v-model="form.tanggal" class="w-full h-11 px-4 border border-gray-300 rounded-[10px] focus:ring-2 focus:ring-[#214da0] focus:border-[#214da0] outline-none transition duration-150" />
                    <span v-if="form.errors.tanggal" class="text-red-500 text-xs font-semibold mt-1 block">
                        {{ form.errors.tanggal }}
                    </span>
                </div>

                <div class="pt-4 flex flex-col gap-3">
                    <button type="submit" :disabled="form.processing" class="w-full h-12 bg-[#214da0] hover:bg-[#1b3d80] text-white font-bold rounded-[10px] shadow-lg transition duration-200 uppercase tracking-wider flex items-center justify-center gap-2">
                        <i class="bi bi-save"></i>
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Data' }}
                    </button>

                    <Link href="/dashboard" class="w-full h-12 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-[10px] shadow-md transition duration-200 uppercase tracking-wider flex items-center justify-center gap-2">
                        <i class="bi bi-arrow-left"></i>
                        Kembali
                    </Link>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
