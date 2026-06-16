<?php

namespace App\Http\Controllers;

use App\Models\Toast;
use App\Events\ToastSent;
use Illuminate\Http\Request;

class ToastController extends Controller
{
    public function success()
    {
        $toast = Toast::create(['message' => 'Operation completed successfully!', 'type' => 'success']);
        event(new ToastSent($toast));
        return redirect('/')->with('toast', ['type' => 'success', 'message' => $toast->message]);
    }

    public function error()
    {
        $toast = Toast::create(['message' => 'Something went wrong!', 'type' => 'error']);
        event(new ToastSent($toast));
        return redirect('/')->with('toast', ['type' => 'error', 'message' => $toast->message]);
    }

    public function info()
    {
        $toast = Toast::create(['message' => 'New update available', 'type' => 'info']);
        event(new ToastSent($toast));
        return redirect('/')->with('toast', ['type' => 'info', 'message' => $toast->message]);
    }

    public function warning()
    {
        $toast = Toast::create(['message' => 'Please check your input', 'type' => 'warning']);
        event(new ToastSent($toast));
        return redirect('/')->with('toast', ['type' => 'warning', 'message' => $toast->message]);
    }

    public function custom(Request $request)
    {
        $request->validate([
            'message' => 'required|min:3|max:200',
            'type' => 'required|in:success,error,info,warning'
        ]);

        $toast = Toast::create([
            'message' => $request->message,
            'type' => $request->type
        ]);

        event(new ToastSent($toast));

        return redirect('/')->with('toast', [
            'type' => $toast->type,
            'message' => $toast->message
        ]);
    }

    public function history(Request $request)
    {
        $toasts = Toast::query()
            ->when($request->search, fn($q) => $q->where('message', 'like', '%'.$request->search.'%'))
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->latest()
            ->paginate(10);

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