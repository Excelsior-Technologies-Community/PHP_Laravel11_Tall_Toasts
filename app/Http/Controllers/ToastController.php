<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ToastController extends Controller
{
    // Show success toast
    public function success()
    {
        return redirect('/')->with('toast', [
            'type' => 'success',
            'message' => 'Data saved successfully!'
        ]);
    }

    // Show error toast
    public function error()
    {
        return redirect('/')->with('toast', [
            'type' => 'error',
            'message' => 'Something went wrong!'
        ]);
    }

    // Show info toast
    public function info()
    {
        return redirect('/')->with('toast', [
            'type' => 'info',
            'message' => 'This is an info message!'
        ]);
    }
}