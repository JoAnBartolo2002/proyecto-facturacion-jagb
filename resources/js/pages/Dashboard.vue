<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';


interface Props {
    stats?: {
        total_invoiced: number;
        count: number;
    };
}

const props = withDefaults(defineProps<Props>(), {
    stats: () => ({
        total_invoiced: 0,
        count: 0
    })
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Facturación',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Facturación" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">

            <div class="relative rounded-xl border border-sidebar-border/70 bg-white p-6 shadow-sm">
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900">Bienvenido, {{ $page.props.auth.user.name }}</h1>
                <p class="mt-1 text-sm text-muted-foreground text-zinc-500">
                    Aquí podrás ver el resumen de tus actividades recientes.
                </p>
            </div>
            
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 p-6 bg-white shadow-sm">
                    <p class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Total Facturado</p>
                    <p class="mt-2 text-3xl font-bold text-zinc-900">${{ stats.total_invoiced }}</p>
                </div>

                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 p-6 bg-white shadow-sm">
                    <p class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Facturas Totales</p>
                    <p class="mt-2 text-3xl font-bold text-zinc-900">{{ stats.count }}</p>
                </div>

                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 p-6 bg-zinc-900 text-zinc-50 shadow-sm">
                    <p class="text-xs font-medium uppercase tracking-wider opacity-70">Acción rápida</p>
                    <div class="mt-4">
                        <a href="/invoices" class="rounded-md bg-white px-3 py-2 text-xs font-bold text-black hover:bg-zinc-200 transition-colors">
                            Gestionar Facturas
                        </a>
                    </div>
                </div>
            </div>

            

        </div>
    </AppLayout>
</template>