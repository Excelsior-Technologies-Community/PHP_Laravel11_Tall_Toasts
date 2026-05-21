<?php

namespace App\Http\Controllers;

use App\Models\Toast;
use Illuminate\Http\Request;

class ToastController extends Controller
{
    public function success()
    {
        return redirect('/')->with('toast', [
            'type' => 'success',
            'message' => 'Operation completed successfully!'
        ]);
    }

    public function error()
    {
        return redirect('/')->with('toast', [
            'type' => 'error',
            'message' => 'Something went wrong!'
        ]);
    }

    public function info()
    {
        return redirect('/')->with('toast', [
            'type' => 'info',
            'message' => 'New update available'
        ]);
    }

    public function warning()
    {
        return redirect('/')->with('toast', [
            'type' => 'warning',
            'message' => 'Please check your input'
        ]);
    }

    public function custom(Request $request)
    {
        $request->validate([
            'message' => 'required|min:3|max:200',
            'type' => 'required|in:success,error,info,warning'
        ]);

        return redirect('/')->with('toast', [
            'type' => $request->type,
            'message' => $request->message
        ]);
    }

    public function history()
    {
        $toasts = Toast::latest()->paginate(20);
        return view('history', compact('toasts'));
    }

    public function clearHistory()
    {
        Toast::truncate();
        return redirect('/toast-history')->with('toast', [
            'type' => 'success',
            'message' => 'History cleared!'
        ]);
    }
}