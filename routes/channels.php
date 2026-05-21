<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('toasts', function ($user) {
    return true; // Allow all users for demo
});