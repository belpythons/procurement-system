<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);

const navItems = [
    { href: '/dashboard', label: 'Dashboard', icon: 'bi bi-speedometer2' },
    { href: '/rp',        label: 'Request Purchasing', icon: 'bi bi-file-earmark-text' },
    { href: '/te',        label: 'Technical Evaluation', icon: 'bi bi-clipboard-check' },
    { href: '/rete',      label: 'Re-Technical Evaluation', icon: 'bi bi-arrow-repeat' },
    { href: '/po',        label: 'Purchase Order', icon: 'bi bi-bag-check' },
    { href: '/report',    label: 'Laporan', icon: 'bi bi-bar-chart-fill', active: true },
];

const csrfToken = computed(() => {
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
});
</script>

<template>
    <div 
        class="min-h-screen bg-cover bg-center bg-fixed bg-no-repeat"
        style="background-image: url('/images/kmi2.jpg');"
    >
        <!-- ═══════════════════════════════════════════ -->
        <!-- SIDEBAR — Matches Blade Layout             -->
        <!-- ═══════════════════════════════════════════ -->
        <div class="sidebar w-full md:w-[240px] h-auto md:h-screen bg-[#2f3f52] relative md:fixed top-0 left-0 z-[100] flex flex-col justify-between text-white">
            <div>
                <!-- Sidebar Header (Logo) -->
                <div class="sidebar-header p-[25px_20px] text-center border-b border-white/10">
                    <img src="/images/kmi-logo.png" alt="KMI Logo" class="w-[150px] mx-auto" />
                </div>

                <!-- Sidebar Menu Links -->
                <div class="sidebar-menu pt-[10px]">
                    <a
                        v-for="item in navItems"
                        :key="item.href"
                        :href="item.href"
                        :class="[
                            'block py-[15px] px-[22px] text-[15px] transition duration-300 ease-in-out flex items-center gap-3',
                            item.active
                                ? 'bg-[#456ea4] text-white font-semibold'
                                : 'text-[#dce4ec] hover:bg-[#3e5670] hover:text-white'
                        ]"
                    >
                        <i :class="item.icon" class="text-[16px]"></i>
                        <span>{{ item.label }}</span>
                    </a>
                </div>
            </div>

            <!-- Logout Section -->
            <div class="logout p-[25px_20px] text-center mt-auto">
                <form method="POST" action="/logout" class="w-full flex justify-center">
                    <input type="hidden" name="_token" :value="csrfToken" />
                    <button
                        type="submit"
                        class="w-[180px] border-none bg-[#dc3545] hover:bg-[#bb2d3b] text-white p-[12px] rounded-[12px] font-bold transition duration-300 hover:scale-[1.03] flex items-center justify-center gap-2"
                    >
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
                <div class="admin-text mt-[12px] text-white font-bold text-[16px] text-center">
                    {{ user?.name || 'Admin' }}
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════ -->
        <!-- MAIN CONTENT AREA                          -->
        <!-- ═══════════════════════════════════════════ -->
        <div class="content ml-0 md:ml-[240px] p-[15px] md:p-[25px]">
            <!-- Topbar (Identical styling to Blade layout) -->
            <div class="topbar bg-[#264a67] rounded-[20px] md:rounded-[45px] p-[22px_35px] w-full mb-[10px] text-white sticky top-[10px] z-[99]">
                <div class="header-title text-[16px] font-bold">
                    PROCUREMENT SYSTEM - PT.KMI
                </div>
            </div>

            <!-- Page Title -->
            <div class="welcome-text mt-[5px] ml-[6px] mb-[8px] text-[30px] text-black font-extrabold">
                LAPORAN (REPORT)
            </div>

            <!-- Slot content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Specific layout properties to match original fixed setup */
@media(max-width:768px){
    .sidebar {
        width: 100% !important;
        height: auto !important;
        position: relative !important;
    }
    .content {
        margin-left: 0 !important;
        padding: 15px !important;
    }
}
</style>
