<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface DTO
{
    public static function fromRequest(Request $request): self;

    public static function fromArray(array $data): self;

    public function toArray(): array;
}
