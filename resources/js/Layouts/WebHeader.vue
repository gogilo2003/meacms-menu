<script setup lang="ts">
import Container from '@/Components/Container.vue';
</script>

<template>
    <div class="relative z-10 overflow-hidden bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 py-16 text-white shadow-inner">
        <!-- Background Overlay Pattern & Accents -->
        <div class="absolute inset-0 bg-[radial-gradient(#f6a820_1px,transparent_1px)] [background-size:24px_24px] opacity-10"></div>
        <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-[#f6a820]/10 blur-3xl"></div>

        <Container>
            <div class="relative z-10 flex flex-col items-center justify-between gap-4 text-center md:flex-row md:text-left">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 rounded-full bg-[#f6a820]/20 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-[#f6a820]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#f6a820]"></span>
                        Navigation & Overview
                    </div>
                    <div class="text-3xl font-extrabold tracking-tight text-white md:text-4xl">
                        <slot />
                    </div>
                </div>
            </div>
        </Container>
    </div>
</template>
