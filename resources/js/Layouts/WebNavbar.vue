<script setup lang="ts">
import ApplicationMark from '@/Components/ApplicationMark.vue';
import MegaNavLink from '@/Components/MegaNavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { iQuoteItem } from '@/interfaces';
import { useQuoteStore } from '@/stores/quote';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { useLinks } from '../Composables/links';

const links = useLinks();
const quoteStore = useQuoteStore();

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);
const isSticky = ref(false);
const showingQuoteDropdown = ref(false);

const phone = computed(() => usePage().props?.contact?.phone || '+123 456 7890');
const email = computed(() => usePage().props?.contact?.email || 'info@example.com');
const address = computed(() => usePage().props?.contact?.address || '24 Fifth st, Los Angeles, USA');
const socialmedia = computed(() => usePage().props?.socialmedia || []);

onMounted(() => {
    quoteStore.initialize();
    if (typeof window !== 'undefined') {
        window.addEventListener('quote-updated', handleQuoteUpdate);
        window.addEventListener('scroll', handleScroll);
        document.addEventListener('click', handleClickOutside);
    }
});

onUnmounted(() => {
    if (typeof window !== 'undefined') {
        window.removeEventListener('quote-updated', handleQuoteUpdate);
        window.removeEventListener('scroll', handleScroll);
        document.removeEventListener('click', handleClickOutside);
    }
});

const handleQuoteUpdate = () => {};

const uniqueProductsCount = computed(() => quoteStore.uniqueProductsCount);
const quoteItems = computed(() => quoteStore.items);
const totalItemsCount = computed(() => quoteStore.totalItems);

const handleScroll = () => {
    if (typeof window !== 'undefined') {
        isSticky.value = window.scrollY > 150;
    }
};

const clearQuote = () => {
    if (uniqueProductsCount.value === 0) return;
    if (confirm('Are you sure you want to clear all items from your quote?')) {
        quoteStore.clearItems();
        showingQuoteDropdown.value = false;
    }
};

const handleClickOutside = (event: MouseEvent) => {
    const target = event.target as HTMLElement;
    if (!target.closest('.quote-dropdown') && !target.closest('.quote-button')) {
        showingQuoteDropdown.value = false;
    }
};

const getProductPicture = (item: iQuoteItem): string => {
    return (item?.product?.picture as string) ?? '/images/placeholder-product.png';
};
const getProductTitle = (item: iQuoteItem): string => {
    return item?.product?.title as string;
};
const onProductPictureError = (e: Event) => {
    let element = e.target as HTMLImageElement;
    element.src = '/images/placeholder-product.png';
};
</script>

<template>
    <header class="w-full flex-none">
        <!-- 1. Top Utility Bar (Dark Slate) -->
        <div class="hidden bg-slate-900 text-slate-300 md:block text-xs font-medium border-b border-slate-800">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8 py-2">
                <div class="flex items-center gap-2 text-slate-300">
                    <span class="inline-flex h-2 w-2 rounded-full bg-[#f6a820]"></span>
                    <span>Your Trusted 24 Hours Service Provider!</span>
                </div>
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-4 text-slate-400">
                        <a v-for="{ url, title } in socialmedia" :key="url" :href="url" target="_blank" class="hover:text-[#f6a820] transition-colors">
                            <span class="capitalize">{{ title }}</span>
                        </a>
                    </div>
                    <a :href="'tel:' + phone" class="inline-flex items-center gap-2 rounded-full bg-[#f6a820] px-3.5 py-1 text-xs font-bold text-slate-900 shadow transition-all hover:bg-[#e0981a]">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>Talk To Expert: {{ phone }}</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. Middle Info Header (White Header with Contact Items) -->
        <div class="hidden bg-white py-4 md:block border-b border-gray-100">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <!-- Brand Logo -->
                <Link :href="route('home')" class="flex items-center gap-3">
                    <div class="h-14 w-auto">
                        <ApplicationMark class="h-full w-auto object-contain" />
                    </div>
                </Link>

                <!-- Contact Info Blocks -->
                <div class="flex items-center gap-8 text-sm">
                    <!-- Call -->
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-slate-50 text-[#f6a820] border border-slate-200/60 shadow-sm">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase text-slate-400">Call</span>
                            <span class="font-bold text-slate-800">{{ phone }}</span>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-slate-50 text-[#f6a820] border border-slate-200/60 shadow-sm">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase text-slate-400">Email</span>
                            <span class="font-bold text-slate-800">{{ email }}</span>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-slate-50 text-[#f6a820] border border-slate-200/60 shadow-sm">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold uppercase text-slate-400">Address</span>
                            <span class="font-bold text-slate-800 max-w-[180px] truncate block">{{ address }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Main Sticky Navbar -->
        <div :class="[isSticky ? 'fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md shadow-md animate-fadeIn' : 'relative bg-white shadow-sm border-b border-gray-200']" class="transition-all duration-300">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <!-- Mobile Logo (visible on small screens) -->
                <div class="flex items-center py-3 md:hidden">
                    <Link :href="route('home')">
                        <div class="h-10 w-auto">
                            <ApplicationMark class="h-full w-auto object-contain" />
                        </div>
                    </Link>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex md:items-center md:gap-1">
                    <MegaNavLink v-for="{ name, caption, items } in links" :key="name" :name="name" :items="items">
                        {{ caption }}
                    </MegaNavLink>
                </div>

                <!-- Desktop Right Actions (Quote Badge Button) -->
                <div class="hidden md:flex md:items-center md:gap-4 py-2">
                    <div class="quote-dropdown relative">
                        <button
                            @click="showingQuoteDropdown = !showingQuoteDropdown"
                            class="quote-button relative inline-flex items-center gap-2 rounded-md bg-[#f6a820] px-5 py-2.5 text-xs font-extrabold uppercase tracking-wider text-slate-900 shadow hover:bg-[#e0981a] focus:outline-none focus:ring-2 focus:ring-[#f6a820] focus:ring-offset-2 transition-all"
                        >
                            <svg class="h-4 w-4 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span>Get A Quote</span>
                            <span
                                v-if="uniqueProductsCount > 0"
                                class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-slate-900 text-[10px] font-extrabold text-[#f6a820] shadow"
                            >
                                {{ uniqueProductsCount }}
                            </span>
                        </button>

                        <!-- Quote Dropdown -->
                        <div v-if="showingQuoteDropdown" class="absolute right-0 top-full z-50 mt-2 w-80 origin-top-right rounded-xl bg-white p-4 shadow-2xl ring-1 ring-black/10">
                            <div class="mb-3 flex items-center justify-between border-b pb-2">
                                <h4 class="font-bold text-slate-900">Quote Summary</h4>
                                <span class="text-xs font-semibold text-slate-500">{{ totalItemsCount }} items</span>
                            </div>

                            <div v-if="quoteItems.length > 0" class="max-h-60 space-y-3 overflow-y-auto pr-1">
                                <div v-for="item in quoteItems.slice(0, 4)" :key="item.id" class="flex items-center gap-3">
                                    <img :src="getProductPicture(item)" :alt="getProductTitle(item)" class="h-10 w-10 rounded-md object-cover border" @error="onProductPictureError" />
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-xs font-bold text-slate-900">{{ getProductTitle(item) }}</p>
                                        <p class="text-[11px] text-slate-500">Qty: {{ item.quantity }}</p>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="py-6 text-center text-xs text-slate-400">
                                No items added to quote
                            </div>

                            <div class="mt-4 space-y-2 border-t pt-3">
                                <Link :href="route('quote')" @click="showingQuoteDropdown = false" class="block w-full rounded-md bg-[#f6a820] px-4 py-2 text-center text-xs font-extrabold uppercase tracking-wider text-slate-900 hover:bg-[#e0981a]">
                                    View Full Quote
                                </Link>
                                <button v-if="quoteItems.length > 0" @click="clearQuote" class="block w-full rounded-md border border-slate-200 bg-white px-4 py-1.5 text-center text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                    Clear All
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Hamburger Button -->
                <div class="flex items-center md:hidden">
                    <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center rounded-md p-2 text-slate-700 hover:bg-slate-100 hover:text-slate-900 focus:outline-none">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div v-if="showingNavigationDropdown" class="md:hidden bg-slate-900 text-white px-4 pt-3 pb-6 border-t border-slate-800">
                <div class="space-y-1">
                    <ResponsiveNavLink v-for="{ name, caption } in links" :key="name" :href="route(name)" :active="route().current(name)" @click="showingNavigationDropdown = false">
                        {{ caption }}
                    </ResponsiveNavLink>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-800">
                    <Link :href="route('quote')" @click="showingNavigationDropdown = false" class="block w-full rounded-md bg-[#f6a820] px-4 py-2.5 text-center text-xs font-extrabold uppercase tracking-wider text-slate-900">
                        Get A Quote ({{ uniqueProductsCount }})
                    </Link>
                </div>
            </div>
        </div>
    </header>
</template>
