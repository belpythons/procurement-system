<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);

const navItems = [
    { href: '/dashboard',  label: 'Dashboard' },
    { href: '/rp',         label: 'Request Purchasing' },
    { href: '/te',         label: 'Technical Eval' },
    { href: '/rete',       label: 'Re-Technical Eval' },
    { href: '/po',         label: 'Purchase Order' },
    { href: '/report',     label: 'Report', active: true },
];
</script>

<template>
    <div class="min-h-screen bg-gray-100">

        <!-- ═══════════════════════════════════════════ -->
        <!-- TOP NAVBAR — Clean UI (matches layouts/navigation.blade.php) -->
        <!-- ═══════════════════════════════════════════ -->
        <nav class="bg-white border-b border-gray-100 shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">

                    <!-- Left: Logo + Nav Links -->
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <a href="/dashboard" class="text-lg font-semibold text-gray-800 tracking-tight">
                                Procurement System
                            </a>
                        </div>

                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <a
                                v-for="item in navItems"
                                :key="item.href"
                                :href="item.href"
                                :class="[
                                    'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition ease-in-out duration-150',
                                    item.active
                                        ? 'border-indigo-500 text-gray-900 focus:border-indigo-700'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300'
                                ]"
                            >
                                {{ item.label }}
                            </a>
                        </div>
                    </div>

                    <!-- Right: User Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <div class="relative">
                            <span class="text-sm font-medium text-gray-500">
                                {{ user?.name || 'Admin' }}
                            </span>
                        </div>
                        <form method="POST" action="/logout" class="ms-4">
                            <input type="hidden" name="_token" :value="csrfToken">
                            <button
                                type="submit"
                                class="text-sm font-medium text-gray-500 hover:text-gray-700 transition ease-in-out duration-150"
                            >
                                Log Out
                            </button>
                        </form>
                    </div>

                    <!-- Mobile Hamburger -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button
                            @click="mobileOpen = !mobileOpen"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition ease-in-out duration-150"
                        >
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Nav (responsive) -->
            <div v-if="mobileOpen" class="sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <a
                        v-for="item in navItems"
                        :key="item.href"
                        :href="item.href"
                        :class="[
                            'block w-full ps-3 pe-4 py-2 border-l-4 text-start text-base font-medium transition ease-in-out duration-150',
                            item.active
                                ? 'border-indigo-400 text-indigo-700 bg-indigo-50 focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700'
                                : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300'
                        ]"
                    >
                        {{ item.label }}
                    </a>
                </div>
            </div>
        </nav>

        <!-- ═══════════════════════════════════════════ -->
        <!-- PAGE HEADER                                -->
        <!-- ═══════════════════════════════════════════ -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Report Center
                </h2>
            </div>
        </header>

        <!-- ═══════════════════════════════════════════ -->
        <!-- MAIN CONTENT AREA                          -->
        <!-- ═══════════════════════════════════════════ -->
        <main class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <slot />
            </div>
        </main>
    </div>
</template>

<script>
export default {
    data() {
        return {
            mobileOpen: false,
        };
    },
    computed: {
        csrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.content || '';
        }
    }
};
</script>
