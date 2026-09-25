<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Notifications\DatabaseNotification;

class UpdateController extends Controller
{
    public function __invoke(DatabaseNotification $notification): RedirectResponse
    {
        $notification->markAsRead();

        $productId = $notification->data['product_id'] ?? null;

        return $productId
            ? to_route('products.show', $productId)
            : to_route('dashboard');
    }
}
