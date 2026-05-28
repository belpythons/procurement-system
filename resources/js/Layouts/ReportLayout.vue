<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);

const navItems = computed(() => [
    { href: '/dashboard', label: 'Dashboard', icon: 'bi bi-speedometer2', active: page.url === '/dashboard' },
    { href: '/dashboard?status=RP', label: 'Request Purchasing', icon: 'bi bi-file-earmark-text', active: page.url === '/dashboard?status=RP' },
    { href: '/dashboard?status=TE', label: 'Technical Evaluation', icon: 'bi bi-clipboard-check', active: page.url === '/dashboard?status=TE' },
    { href: '/dashboard?status=RE-TE', label: 'Re-Technical Evaluation', icon: 'bi bi-arrow-repeat', active: page.url === '/dashboard?status=RE-TE' },
    { href: '/dashboard?status=PO', label: 'Purchase Order', icon: 'bi bi-bag-check', active: page.url === '/dashboard?status=PO' },
    { href: '/report', label: 'Laporan', icon: 'bi bi-bar-chart-fill', active: page.url.startsWith('/report') },
]);

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
        <!-- SIDEBAR — Matches Blade Layout Exactly     -->
        <!-- ═══════════════════════════════════════════ -->
        <div class="sidebar">
            <div>
                <!-- Sidebar Header (Logo) -->
                <div class="sidebar-header">
                    <img src="/images/kmi-logo.png" alt="KMI Logo" />
                </div>

                <!-- Sidebar Menu Links -->
                <div class="sidebar-menu">
                    <a
                        v-for="item in navItems"
                        :key="item.href"
                        :href="item.href"
                        :class="{ active: item.active }"
                    >
                        <i :class="item.icon"></i>
                        {{ item.label }}
                    </a>
                </div>
            </div>

            <!-- Logout Section -->
            <div class="logout">
                <form method="POST" action="/logout">
                    <input type="hidden" name="_token" :value="csrfToken" />
                    <button type="submit">
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </button>
                </form>
                <div class="admin-text">
                    {{ user?.name || 'Admin' }}
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════ -->
        <!-- MAIN CONTENT AREA                          -->
        <!-- ═══════════════════════════════════════════ -->
        <div class="content">
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
/* SIDEBAR */
.sidebar{
    width:240px;
    height:100vh;
    background:#2f3f52;
    position:fixed;
    top:0;
    left:0;
    z-index:100;
    display:flex;
    flex-direction:column;
    color:white;
}

.sidebar-header{
    padding:25px 20px;
    text-align:center;
    border-bottom:1px solid rgba(255,255,255,0.1);
}

.sidebar-header img{
    width:150px;
}

.sidebar-menu{
    padding-top:10px;
}

.sidebar-menu a{
    display:block;
    padding:15px 22px;
    color:#ffffff;
    text-decoration:none;
    font-size:15px;
    transition:0.3s;
}

.sidebar-menu a i {
    margin-right: 8px;
}

.sidebar-menu a:hover{
    background:#3e5670;
    color:white;
}

.sidebar-menu .active{
    background:#456ea4;
    color:white;
}

/* LOGOUT */
.logout{
    margin-top:auto;
    padding:25px 20px;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    text-align:center;
}

.logout form{
    width:100%;
    display:flex;
    justify-content:center;
}

.logout button{
    width:180px;
    border:none;
    background:#dc3545;
    color:white;
    padding:12px;
    border-radius:12px;
    font-weight:bold;
    transition:0.3s;
}

.logout button i {
    margin-right: 8px;
}

.logout button:hover{
    background:#bb2d3b;
    transform:scale(1.03);
}

.admin-text{
    margin-top:12px;
    color:white;
    font-weight:700;
    font-size:16px;
    text-align:center;
}

/* CONTENT */
.content{
    margin-left:240px;
    padding:25px;
}

/* RESPONSIVE */
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
