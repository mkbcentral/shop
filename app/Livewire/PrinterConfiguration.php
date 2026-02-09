<?php

namespace App\Livewire;

use Livewire\Component;

class PrinterConfiguration extends Component
{
    public $companyName = '';
    public $companyAddress = '';
    public $companyPhone = '';
    public $companyEmail = '';
    public $companyWebsite = '';
    public $companyCurrency = 'CDF';
    public $paperWidth = 32; // 58mm par défaut
    public $printerType = 'usb'; // usb ou bluetooth
    public $printerName = '';
    public $testPrinting = false;

    public function mount()
    {
        // Charger les données de l'organisation de l'utilisateur connecté
        $organization = app()->bound('current_organization')
            ? app('current_organization')
            : auth()->user()?->defaultOrganization;

        if ($organization) {
            $this->companyName = $organization->name ?? $organization->legal_name ?? '';
            $this->companyAddress = $organization->address ?? '';
            $this->companyPhone = $organization->phone ?? '';
            $this->companyEmail = $organization->email ?? '';
            $this->companyWebsite = $organization->website ?? '';
            $this->companyCurrency = $organization->currency ?? 'CDF';
        }
    }

    public function testConnection()
    {
        $this->testPrinting = true;
        $this->dispatch('test-printer-connection');
    }

    public function testPrint()
    {
        \Log::info('🔵 testPrint() method called');

        $this->dispatch('test-thermal-print', [
            'invoice_number' => 'TEST-' . date('YmdHis'),
            'date' => date('d/m/Y H:i:s'),
            'items' => [
                [
                    'name' => 'Article Test 1',
                    'quantity' => 2,
                    'unit_price' => 500,
                    'total' => 1000,
                ],
                [
                    'name' => 'Article Test 2',
                    'quantity' => 1,
                    'unit_price' => 750,
                    'total' => 750,
                ],
            ],
            'subtotal' => 1750,
            'discount' => 0,
            'tax' => 0,
            'total' => 1750,
            'paid' => 2000,
            'change' => 250,
        ]);
    }

    public function render()
    {
        return view('livewire.printer-configuration')
            ;
    }
}
