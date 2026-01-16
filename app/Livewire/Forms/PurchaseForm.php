<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class PurchaseForm extends Form
{
    #[Validate('required|exists:suppliers,id')]
    public $supplier_id = '';

    #[Validate('required|date')]
    public $purchase_date = '';

    #[Validate('required|in:pending,received,cancelled')]
    public $status = 'pending';

    #[Validate('required|in:pending,paid,partial')]
    public $payment_status = 'pending';

    #[Validate('nullable|numeric|min:0')]
    public $paid_amount = 0;

    #[Validate('nullable|string|max:1000')]
    public $notes = '';

    public $total = 0;
}
