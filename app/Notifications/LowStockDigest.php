<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockDigest extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param  Collection<int, Product>  $products
     */
    public function __construct(
        public Collection $products,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Daily low stock digest')
            ->line("{$this->products->count()} product(s) are currently below their reorder threshold:");

        foreach ($this->products as $product) {
            $message->line("- {$product->name} ({$product->sku}): {$product->quantity} in stock, threshold {$product->reorder_threshold}");
        }

        return $message;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'low_stock_digest',
            'product_count' => $this->products->count(),
            'product_ids' => $this->products->pluck('id')->all(),
        ];
    }
}
