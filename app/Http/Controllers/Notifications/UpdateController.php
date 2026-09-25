<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Notifications\DatabaseNotification;

class UpdateController extends Controller
{
    public function __invoke(DatabaseNotification $notification): Response
    {
        $notification->markAsRead();

        return response()->noContent();
    }
}
