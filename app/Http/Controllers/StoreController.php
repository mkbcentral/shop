<?php

namespace App\Http\Controllers;

use App\Actions\Store\CreateStoreAction;
use App\Actions\Store\UpdateStoreAction;
use App\Actions\Store\DeleteStoreAction;
use App\Actions\Store\SwitchUserStoreAction;
use App\Repositories\StoreRepository;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        return view('livewire.store.store-index');
    }

    public function create()
    {
        return view('livewire.store.store-create');
    }

    public function show($id)
    {
        return view('livewire.store.store-show', ['storeId' => $id]);
    }

    public function edit($id)
    {
        return view('livewire.store.store-edit', ['storeId' => $id]);
    }

    /**
     * Switch current store for authenticated user
     */
    public function switch(Request $request, int $storeId)
    {
        $action = app(SwitchUserStoreAction::class);

        try {
            $action->execute(auth()->id(), $storeId);

            return redirect()->back()->with('success', 'Magasin changé avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Get stores for authenticated user (API)
     */
    public function userStores(StoreRepository $repository)
    {
        $stores = $repository->getStoresForUser(auth()->id());

        return response()->json([
            'stores' => $stores,
            'current_store_id' => auth()->user()->current_store_id,
        ]);
    }
}
