<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Container from '@/Components/Container.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const phone = computed(() => usePage().props?.contact?.phone || '+123 456 7890');
const email = computed(() => usePage().props?.contact?.email || 'info@example.com');
const address = computed(() => usePage().props?.contact?.address || '24 Fifth st, Los Angeles, USA');
const intro = computed(() => usePage().props?.intro || 'Since our beginning, we have worked tirelessly to earn our reputation for quality, service and dependability.');
const appName = computed(() => usePage().props?.appName || 'Boldman');
const socialmedia = computed(() => usePage().props?.socialmedia || []);
</script>

<template>
    <footer class="relative z-10 bg-slate-900 text-slate-300">
        <!-- 1. Pre-footer Newsletter Subscriber Box -->
        <div class="relative z-20 border-b border-slate-800 bg-white shadow-xl">
            <Container>
                <div class="flex flex-col items-center justify-between gap-6 py-8 md:flex-row">
                    <div class="flex items-center gap-4 text-slate-900">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#f6a820]/15 text-[#f6a820]">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold">Subscribe To Our Newsletter</h4>
                            <p class="text-xs text-slate-500">Get latest news and exclusive offers delivered to your inbox.</p>
                        </div>
                    </div>
                    <form @submit.prevent class="flex w-full max-w-md items-center gap-2">
                        <input
                            type="email"
                            placeholder="Your mail address..."
                            class="w-full rounded-md border border-slate-300 bg-slate-50 px-4 py-2.5 text-xs text-slate-800 focus:border-[#f6a820] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#f6a820]"
                        />
                        <button type="submit" class="flex-none rounded-md bg-[#f6a820] px-6 py-2.5 text-xs font-extrabold uppercase tracking-wider text-slate-900 hover:bg-[#e0981a] transition-all">
                            Subscribe
                        </button>
                    </form>
                </div>
            </Container>
        </div>

        <!-- 2. Main Footer Grid -->
        <div class="py-16">
            <Container>
                <div class="grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-4">
                    <!-- Column 1: Brand & Working Hours -->
                    <div class="space-y-6">
                        <div class="h-12 w-auto">
                            <ApplicationLogo class="h-full w-auto object-contain text-white" />
                        </div>
                        <p class="text-xs leading-relaxed text-slate-400" v-html="intro"></p>
                        <!-- Working Hours Box -->
                        <div class="rounded-xl bg-slate-800/80 p-4 border border-slate-700/60 space-y-2 text-xs">
                            <div class="font-bold text-[#f6a820] uppercase tracking-wider text-[11px]">Work Days</div>
                            <div class="flex justify-between text-slate-300">
                                <span>Mon - Sat</span>
                                <span class="font-medium">09am - 05pm</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span>Sunday</span>
                                <span class="font-medium text-amber-500">Closed</span>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2: Useful Links -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-white border-l-2 border-[#f6a820] pl-3">
                            Useful Links
                        </h4>
                        <ul class="grid grid-cols-2 gap-2 text-xs text-slate-400">
                            <li><Link :href="route('home')" class="hover:text-[#f6a820] transition-colors">&rsaquo; Home</Link></li>
                            <li><Link :href="route('about')" class="hover:text-[#f6a820] transition-colors">&rsaquo; About Us</Link></li>
                            <li><Link :href="route('products')" class="hover:text-[#f6a820] transition-colors">&rsaquo; Products</Link></li>
                            <li><Link :href="route('news')" class="hover:text-[#f6a820] transition-colors">&rsaquo; News Articles</Link></li>
                            <li><Link :href="route('downloads')" class="hover:text-[#f6a820] transition-colors">&rsaquo; Downloads</Link></li>
                            <li><Link :href="route('contact')" class="hover:text-[#f6a820] transition-colors">&rsaquo; Contact Us</Link></li>
                            <li><Link :href="route('quote')" class="hover:text-[#f6a820] transition-colors">&rsaquo; Get Quote</Link></li>
                            <li><a href="#" class="hover:text-[#f6a820] transition-colors">&rsaquo; Privacy Policy</a></li>
                        </ul>
                    </div>

                    <!-- Column 3: Get Free Estimate -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-white border-l-2 border-[#f6a820] pl-3">
                            Get Free Estimate
                        </h4>
                        <div class="space-y-3 text-xs">
                            <p class="text-slate-400">Our online scheduling and support system is fast, secure and 24/7 available.</p>
                            <div class="text-2xl font-extrabold text-[#f6a820]">{{ phone }}</div>
                            <Link :href="route('contact')" class="inline-block rounded-md border border-slate-700 bg-slate-800/90 px-4 py-2.5 text-center text-xs font-bold uppercase tracking-wider text-slate-200 hover:bg-[#f6a820] hover:text-slate-900 hover:border-[#f6a820] transition-all">
                                Request Online Form
                            </Link>
                        </div>
                    </div>

                    <!-- Column 4: Social & Location -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-white border-l-2 border-[#f6a820] pl-3">
                            Contact Info
                        </h4>
                        <div class="space-y-3 text-xs text-slate-400">
                            <p><span class="font-bold text-slate-200">Address:</span> {{ address }}</p>
                            <p><span class="font-bold text-slate-200">Email:</span> {{ email }}</p>
                            <div class="flex items-center gap-3 pt-2">
                                <a v-for="{ url, title } in socialmedia" :key="url" :href="url" target="_blank" class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-800 text-slate-300 hover:bg-[#f6a820] hover:text-slate-900 transition-all">
                                    <span class="capitalize text-[10px] font-bold">{{ title[0] }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </Container>
        </div>

        <!-- 3. Sub-footer Copyright Bar -->
        <div class="border-t border-slate-800/80 bg-slate-950 py-4 text-xs text-slate-500">
            <Container>
                <div class="flex flex-col items-center justify-between gap-2 md:flex-row">
                    <div>&copy; {{ new Date().getFullYear() }} {{ appName }}. All rights reserved.</div>
                    <div class="flex items-center gap-4 text-slate-400">
                        <a href="#" class="hover:text-[#f6a820]">Terms of Service</a>
                        <span>&bull;</span>
                        <a href="#" class="hover:text-[#f6a820]">Privacy Policy</a>
                    </div>
                </div>
            </Container>
        </div>
    </footer>
</template>
