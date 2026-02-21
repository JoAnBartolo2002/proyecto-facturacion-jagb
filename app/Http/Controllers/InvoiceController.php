<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    public function index()
    {
        return Inertia::render('invoices/Index', [
            'invoices' => auth()->user()->invoices()->latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|unique:invoices',
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email',
            'date'           => 'required|date',
            'total_amount'   => 'required|numeric|min:0',
            'status'         => 'required|in:pending,paid,cancelled',
        ]);

        auth()->user()->invoices()->create($validated);

        return redirect()->route('invoices.index')->with('message', 'Factura creada con éxito');
    }

    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar esta factura.');
        }

        $validated = $request->validate([
            'invoice_number' => 'required|unique:invoices,invoice_number,' . $invoice->id,
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email',
            'date'           => 'required|date',
            'total_amount'   => 'required|numeric|min:0',
            'status'         => 'required|in:pending,paid,cancelled',
        ]);

        $invoice->update($validated);
        return redirect()->back();
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para eliminar esta factura.');
        }

        $invoice->delete();
        return redirect()->back();
    }
}