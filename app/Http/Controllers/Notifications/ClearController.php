<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClearController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $request->user()->readNotifications()->delete();

        return response()->noContent();
    }
}
