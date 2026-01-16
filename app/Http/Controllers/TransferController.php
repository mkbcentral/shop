<?php

namespace App\Http\Controllers;

use App\Actions\StoreTransfer\CreateTransferAction;
use App\Actions\StoreTransfer\ApproveTransferAction;
use App\Actions\StoreTransfer\ReceiveTransferAction;
use App\Actions\StoreTransfer\CancelTransferAction;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function index()
    {
        return view('livewire.store.transfer-index');
    }

    public function create()
    {
        return view('livewire.store.transfer-create');
    }

    public function show($id)
    {
        return view('livewire.store.transfer-show', ['transferId' => $id]);
    }

    /**
     * Approve a transfer
     */
    public function approve(Request $request, int $transferId)
    {
        $action = app(ApproveTransferAction::class);

        try {
            $action->execute($transferId, auth()->id());

            return redirect()->back()->with('success', 'Transfert approuvé avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Receive a transfer
     */
    public function receive(Request $request, int $transferId)
    {
        $validated = $request->validate([
            'received_quantities' => 'required|array',
            'notes' => 'nullable|string',
        ]);

        $action = app(ReceiveTransferAction::class);

        try {
            $action->execute(
                $transferId,
                $validated['received_quantities'],
                auth()->id(),
                $validated['notes'] ?? null
            );

            return redirect()->back()->with('success', 'Transfert reçu avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Cancel a transfer
     */
    public function cancel(Request $request, int $transferId)
    {
        $validated = $request->validate([
            'reason' => 'required|string',
        ]);

        $action = app(CancelTransferAction::class);

        try {
            $action->execute($transferId, auth()->id(), $validated['reason']);

            return redirect()->back()->with('success', 'Transfert annulé');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
