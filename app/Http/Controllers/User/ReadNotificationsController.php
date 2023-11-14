<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Auth;

class ReadNotificationsController
{
    public function readNotifications()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return back();
    }
}
