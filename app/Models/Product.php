<?php

namespace App\Models;

use App\DTOs\Products\CreateProductData;
use Carbon\Carbon;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $name
 * @property string $sku
 * @property int $quantity
 * @property int $reorder_threshold
 * @property ?Carbon $low_stock_alerted_at
 * @property bool $is_below_threshold
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static ProductFactory factory($count = null, $state = [])
 */
class Product extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'name',
        'sku',
        'quantity',
        'reorder_threshold',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'reorder_threshold' => 'integer',
            'low_stock_alerted_at' => 'datetime',
        ];
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    protected function isBelowThreshold(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->quantity < $this->reorder_threshold,
        );
    }

    public function scopeBelowThreshold(Builder $query): Builder
    {
        return $query->whereColumn('quantity', '<', 'reorder_threshold');
    }
}