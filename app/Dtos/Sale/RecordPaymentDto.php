<?php

namespace App\Dtos\Sale;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Data Transfer Object for Recording Payments
 * 
 * Encapsulates payment data for a sale
 */
readonly class RecordPaymentDto
{
    public function __construct(
        public int $saleId,
        public int $userId,
        public float $amount,
        public string $paymentMethod,
        public ?string $paymentDate = null,
        public ?string $notes = null,
    ) {
        $this->validate();
    }

    /**
     * Create from array with validation
     */
    public static function fromArray(array $data): self
    {
        return new self(
            saleId: (int) $data['sale_id'],
            userId: (int) ($data['user_id'] ?? auth()->id()),
            amount: (float) $data['amount'],
            paymentMethod: $data['payment_method'],
            paymentDate: $data['payment_date'] ?? null,
            notes: $data['notes'] ?? null,
        );
    }

    /**
     * Create from request
     */
    public static function fromRequest($request, int $saleId): self
    {
        $data = $request->validated();
        $data['sale_id'] = $saleId;
        $data['user_id'] = $data['user_id'] ?? auth()->id();
        
        return self::fromArray($data);
    }

    /**
     * Convert to array for service
     */
    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'amount' => $this->amount,
            'payment_method' => $this->paymentMethod,
            'payment_date' => $this->paymentDate ?? now()->toDateTimeString(),
            'notes' => $this->notes,
        ];
    }

    /**
     * Validate the DTO data
     * 
     * @throws ValidationException
     */
    private function validate(): void
    {
        $data = [
            'sale_id' => $this->saleId,
            'user_id' => $this->userId,
            'amount' => $this->amount,
            'payment_method' => $this->paymentMethod,
            'payment_date' => $this->paymentDate,
            'notes' => $this->notes,
        ];

        $validator = Validator::make($data, [
            'sale_id' => ['required', 'integer', 'exists:sales,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string', 'in:cash,card,mobile_money,bank_transfer,check,credit'],
            'payment_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Check if payment has notes
     */
    public function hasNotes(): bool
    {
        return !empty($this->notes);
    }
}
