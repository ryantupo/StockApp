<?php

namespace App\DTOs\StockMovements;

use App\Contracts\DTO;
use Illuminate\Http\Request;

readonly class CreateStockMovementData implements DTO
{
    public function __construct(
        public int $quantityChange,
        public string $reason,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            quantityChange: $request->input('quantity_change'),
            reason: $request->input('reason'),
        );
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            quantityChange: $data['quantity_change'],
            reason: $data['reason'],
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'quantity_change' => $this->quantityChange,
            'reason' => $this->reason,
        ];
    }
}
