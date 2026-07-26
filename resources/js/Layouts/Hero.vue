<script setup lang="ts">
import { iSlide } from '@/interfaces';
import { Link } from '@inertiajs/vue3';
import { initFlowbite } from 'flowbite';
import { onMounted } from 'vue';

defineProps<{
    slides?: iSlide[];
}>();

onMounted(() => {
    initFlowbite();
});

const setComponent = (mediaType?: string) => {
    return mediaType === 'video' ? 'video' : 'img';
};
</script>

<template>
    <section class="relative z-0 overflow-hidden bg-slate-900 text-white">
        <div id="hero-carousel" class="relative w-full" data-carousel="slide" data-carousel-interval="7000">
            <!-- Carousel Items Wrapper -->
            <div class="relative min-h-[520px] md:min-h-[640px] overflow-hidden">
                <div
                    v-for="(slide, index) in (slides && slides.length ? slides : [{ id: 1, title: 'BUILD YOUR VISION', caption: 'Since our beginning, we have worked tirelessly to earn our reputation for quality, service and dependability.', picture: '/images/hero-default.jpg', media_type: 'image' }])"
                    :key="slide.id"
                    class="hidden duration-1000 ease-in-out"
                    data-carousel-item
                    :class="{ 'active': index === 0 }"
                >
                    <!-- Background Media -->
                    <div class="absolute inset-0 z-0">
                        <component
                            :is="setComponent(slide.media_type)"
                            :src="slide.picture"
                            class="h-full w-full object-cover opacity-40 scale-105 transition-transform duration-10000"
                            :alt="slide.title"
                            :autoplay="slide.media_type === 'video'"
                            muted
                            loop
                        />
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/80 to-transparent"></div>
                    </div>

                    <!-- Slide Content Container -->
                    <div class="relative z-10 mx-auto flex max-w-7xl flex-col justify-center px-4 py-20 sm:px-6 md:min-h-[640px] lg:px-8">
                        <div class="max-w-2xl space-y-6">
                            <!-- Category Badge Tag -->
                            <div class="inline-flex items-center gap-2 rounded-sm bg-[#f6a820] px-3 py-1 text-xs font-black uppercase tracking-widest text-slate-950 shadow">
                                <span class="h-2 w-0.5 bg-slate-950"></span>
                                {{ slide.title ? 'DESIGN. DEVELOP. DISTRIBUTE' : 'QUALITY SERVICE' }}
                            </div>

                            <!-- Main Title -->
                            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl md:text-6xl uppercase leading-tight">
                                {{ slide.title || 'BUILD YOUR VISION' }}
                            </h1>

                            <!-- Subtitle / Caption -->
                            <p class="text-sm text-slate-300 sm:text-base md:text-lg max-w-xl font-normal leading-relaxed">
                                {{ slide.caption || 'Since our beginning, we have worked tirelessly to earn our reputation for quality, service and dependability.' }}
                            </p>

                            <!-- CTAs -->
                            <div class="flex flex-wrap items-center gap-4 pt-2">
                                <Link
                                    :href="route('quote')"
                                    class="inline-flex items-center gap-2 rounded-md bg-[#f6a820] px-7 py-3.5 text-xs font-extrabold uppercase tracking-wider text-slate-950 shadow-lg hover:bg-[#e0981a] transition-all hover:scale-105"
                                >
                                    Request A Quote
                                </Link>

                                <Link
                                    :href="route('about')"
                                    class="inline-flex items-center gap-3 rounded-md border border-white/20 bg-white/10 px-6 py-3.5 text-xs font-extrabold uppercase tracking-wider text-white backdrop-blur-md hover:bg-white/20 transition-all"
                                >
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#f6a820] text-slate-950">
                                        <svg class="h-3 w-3 fill-current ml-0.5" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </span>
                                    <span>How We Work</span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carousel Slider Indicators -->
            <div v-if="slides && slides.length > 1" class="absolute bottom-6 left-1/2 z-20 flex -translate-x-1/2 gap-2">
                <button
                    v-for="(item, index) in slides"
                    :key="index"
                    type="button"
                    class="h-2.5 w-8 rounded-full bg-white/40 transition-all data-[carousel-slide-to-active]:w-12 data-[carousel-slide-to-active]:bg-[#f6a820]"
                    :aria-label="`Slide ${index + 1}`"
                    :data-carousel-slide-to="index"
                ></button>
            </div>
        </div>

        <!-- 3 Feature Cards Bar Below Hero -->
        <div class="relative z-20 bg-white text-slate-900 border-b border-slate-200">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 divide-y divide-slate-100 md:grid-cols-3 md:divide-x md:divide-y-0">
                    <!-- Feature 1 -->
                    <div class="flex items-start gap-4 py-8 px-4 transition-all hover:bg-slate-50">
                        <div class="flex h-12 w-12 flex-none items-center justify-center rounded-xl bg-[#f6a820]/15 text-[#f6a820]">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-base font-bold text-slate-900">Office 24/7 Opened</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">Dedicated support team focused on rapid response and project delivery.</p>
                            <Link :href="route('contact')" class="inline-flex items-center text-xs font-extrabold uppercase tracking-wider text-[#f6a820] hover:underline pt-1">
                                Get A Free Quote &rsaquo;
                            </Link>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex items-start gap-4 py-8 px-4 transition-all hover:bg-slate-50">
                        <div class="flex h-12 w-12 flex-none items-center justify-center rounded-xl bg-[#f6a820]/15 text-[#f6a820]">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-base font-bold text-slate-900">Our Installations</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">Precision equipment setup and certified maintenance services.</p>
                            <Link :href="route('products')" class="inline-flex items-center text-xs font-extrabold uppercase tracking-wider text-[#f6a820] hover:underline pt-1">
                                Get A Free Quote &rsaquo;
                            </Link>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex items-start gap-4 py-8 px-4 transition-all hover:bg-slate-50">
                        <div class="flex h-12 w-12 flex-none items-center justify-center rounded-xl bg-[#f6a820]/15 text-[#f6a820]">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-base font-bold text-slate-900">Our Services</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">Full range of specialized industrial, safety and quality solutions.</p>
                            <Link :href="route('about')" class="inline-flex items-center text-xs font-extrabold uppercase tracking-wider text-[#f6a820] hover:underline pt-1">
                                Get A Free Quote &rsaquo;
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
