<?php

namespace App\Livewire;

use Livewire\Component;

class PrinterConfiguration extends Component
{
    public $companyName = 'VOTRE ENTREPRISE';
    public $companyAddress = 'Votre Adresse';
    public $companyPhone = '+243 XXX XXX XXX';
    public $companyEmail = 'contact@entreprise.cd';
    public $companyWebsite = 'www.votre-site.cd';
    public $paperWidth = 32; // 58mm par défaut
    public $printerType = 'usb'; // usb ou bluetooth
    public $printerName = '';
    public $testPrinting = false;

    public function mount()
    {
        // Les valeurs seront chargées depuis le localStorage côté client
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
