<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\StockMovementFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $product_id
 * @property int $quantity_change
 * @property string $reason
 * @property Product $product
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static StockMovementFactory factory($count = null, $state = [])
 */
class StockMovement extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'quantity_change',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'quantity_change' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}