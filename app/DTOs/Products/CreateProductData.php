<?php

namespace App\DTOs\Products;

use App\Contracts\DTO;
use Illuminate\Http\Request;

readonly class CreateProductData implements DTO
{
    public function __construct(
        public string $name,
        public string $sku,
        public int $quantity,
        public int $reorderThreshold,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            sku: $request->input('sku'),
            quantity: $request->input('quantity'),
            reorderThreshold: $request->input('reorder_threshold'),
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            sku: $data['sku'],
            quantity: $data['quantity'],
            reorderThreshold: $data['reorder_threshold'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'sku' => $this->sku,
            'quantity' => $this->quantity,
            'reorder_threshold' => $this->reorderThreshold,
        ];
    }
}