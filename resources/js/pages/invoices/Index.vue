<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';
import { Pencil, Trash2 } from 'lucide-vue-next';

const props = defineProps<{
    invoices: any[];
}>();


const showForm = ref(false);
const isEditing = ref(false);
const editingId = ref<number | null>(null);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Inicio', href: '/dashboard' },
    { title: 'Facturas', href: '/invoices' },
];

const form = useForm({
    invoice_number: '',
    customer_name: '',
    customer_email: '',
    date: new Date().toISOString().split('T')[0], 
    total_amount: 0,
    status: 'pending',
});


const openCreate = () => {
    isEditing.value = false;
    editingId.value = null;
    form.reset();
    showForm.value = true;
};


const openEdit = (invoice: any) => {
    isEditing.value = true;
    editingId.value = invoice.id;
    
    form.invoice_number = invoice.invoice_number;
    form.customer_name = invoice.customer_name;
    form.customer_email = invoice.customer_email;
    form.date = invoice.date;
    form.total_amount = invoice.total_amount;
    form.status = invoice.status;
    
    showForm.value = true;
};

const submit = () => {
    if (isEditing.value && editingId.value) {

        form.put(`/invoices/${editingId.value}`, {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post('/invoices', {
            onSuccess: () => closeModal(),
        });
    }
};

const deleteInvoice = (id: number) => {
    if (confirm('¿Estás seguro de eliminar esta factura?')) {
        router.delete(`/invoices/${id}`);
    }
};

const closeModal = () => {
    showForm.value = false;
    isEditing.value = false;
    form.reset();
};
</script>

<template>
    <Head title="Facturación" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-zinc-900">Gestión de Facturas</h1>
                    <p class="text-sm text-zinc-500">Aquí podrás ver toda tu información.</p>
                </div>
                <Button @click="showForm ? closeModal() : openCreate()" :variant="showForm ? 'outline' : 'default'">
                    {{ showForm ? 'Cancelar' : '+ Nueva Factura' }}
                </Button>
            </div>

            <div v-if="showForm" class="rounded-xl border border-dashed border-zinc-300 bg-zinc-50/50 p-6 animate-in fade-in zoom-in duration-200">
                <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-zinc-600 uppercase">Folio</label>
                        <input v-model="form.invoice_number" type="text" placeholder="FAC-000" class="rounded-md border border-zinc-200 p-2 text-sm focus:ring-2 focus:ring-black outline-none" />
                        <span v-if="form.errors.invoice_number" class="text-[10px] text-red-500">{{ form.errors.invoice_number }}</span>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-zinc-600 uppercase">Cliente</label>
                        <input v-model="form.customer_name" type="text" placeholder="Nombre completo" class="rounded-md border border-zinc-200 p-2 text-sm focus:ring-2 focus:ring-black outline-none" />
                        <span v-if="form.errors.customer_name" class="text-[10px] text-red-500">{{ form.errors.customer_name }}</span>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-zinc-600 uppercase">Correo Electrónico</label>
                        <input v-model="form.customer_email" type="email" placeholder="cliente@correo.com" class="rounded-md border border-zinc-200 p-2 text-sm focus:ring-2 focus:ring-black outline-none" />
                        <span v-if="form.errors.customer_email" class="text-[10px] text-red-500">{{ form.errors.customer_email }}</span>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-zinc-600 uppercase">Fecha</label>
                        <input v-model="form.date" type="date" class="rounded-md border border-zinc-200 p-2 text-sm focus:ring-2 focus:ring-black outline-none" />
                        <span v-if="form.errors.date" class="text-[10px] text-red-500">{{ form.errors.date }}</span>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-zinc-600 uppercase">Monto ($)</label>
                        <input v-model="form.total_amount" type="number" step="0.01" class="rounded-md border border-zinc-200 p-2 text-sm focus:ring-2 focus:ring-black outline-none" />
                        <span v-if="form.errors.total_amount" class="text-[10px] text-red-500">{{ form.errors.total_amount }}</span>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-bold text-zinc-600 uppercase">Estado</label>
                        <select v-model="form.status" class="rounded-md border border-zinc-200 p-2 text-sm focus:ring-2 focus:ring-black outline-none bg-white">
                            <option value="pending">Pendiente</option>
                            <option value="paid">Pagado</option>
                            <option value="cancelled">Cancelado</option>
                        </select>
                    </div>

                    <div class="md:col-span-3 flex justify-end pt-2">
                        <Button type="submit" :disabled="form.processing">
                            {{ form.processing ? 'Procesando...' : 'Guardar Factura' }}
                        </Button>
                    </div>
                </form>
            </div>

            <div class="rounded-xl border border-sidebar-border/70 bg-white shadow-sm overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-zinc-50 border-b border-sidebar-border/70 text-zinc-500">
                        <tr>
                            <th class="px-6 py-3 font-medium">Información Fiscal</th>
                            <th class="px-6 py-3 font-medium">Cliente</th>
                            <th class="px-6 py-3 text-right font-medium">Monto</th>
                            <th class="px-6 py-3 text-center font-medium">Estado</th>
                            <th class="px-6 py-3 text-right font-medium">Acciones</th> </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        <tr v-for="invoice in invoices" :key="invoice.id" class="hover:bg-zinc-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-zinc-900">{{ invoice.invoice_number }}</div>
                                <div class="text-[10px] text-zinc-400 uppercase">{{ invoice.date }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-zinc-900">{{ invoice.customer_name }}</div>
                                <div class="text-xs text-zinc-400">{{ invoice.customer_email }}</div>
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-zinc-900">
                                ${{ parseFloat(invoice.total_amount).toFixed(2) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span :class="{
                                    'bg-amber-100 text-amber-700': invoice.status === 'pending',
                                    'bg-green-100 text-green-700': invoice.status === 'paid',
                                    'bg-red-100 text-red-700': invoice.status === 'cancelled'
                                }" class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase">
                                    {{ invoice.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button @click="openEdit(invoice)" class="p-2 text-zinc-600 hover:bg-zinc-100 rounded-md transition-colors" title="Editar">
                                        <Pencil class="w-4 h-4" />
                                    </button>
                                    <button @click="deleteInvoice(invoice.id)" class="p-2 text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Eliminar">
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>