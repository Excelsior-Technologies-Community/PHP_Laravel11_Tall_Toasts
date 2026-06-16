<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Toast History</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="flex justify-between items-center mb-8">
            <a href="/" class="text-blue-500 hover:text-blue-600 transition">← Back</a>
            <h1 class="text-2xl font-bold text-gray-800">Toast History</h1>
            <form action="/clear-history" method="POST" onsubmit="return confirm('Clear all history?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-600 text-sm font-semibold">Clear All</button>
            </form>
        </div>

        <form method="GET" action="{{ route('history') }}" class="bg-white p-4 rounded-xl shadow-sm mb-6 flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search messages..." class="flex-1 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
            <select name="type" class="border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                <option value="">All Types</option>
                <option value="success" {{ request('type') == 'success' ? 'selected' : '' }}>Success</option>
                <option value="error" {{ request('type') == 'error' ? 'selected' : '' }}>Error</option>
                <option value="warning" {{ request('type') == 'warning' ? 'selected' : '' }}>Warning</option>
                <option value="info" {{ request('type') == 'info' ? 'selected' : '' }}>Info</option>
            </select>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">Filter</button>
        </form>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @forelse($toasts as $toast)
            <div class="border-b border-gray-100 p-4 hover:bg-gray-50 transition">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-medium text-gray-800">{{ $toast->message }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $toast->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                        @if($toast->type == 'success') bg-green-100 text-green-700
                        @elseif($toast->type == 'error') bg-red-100 text-red-700
                        @elseif($toast->type == 'warning') bg-yellow-100 text-yellow-700
                        @else bg-blue-100 text-blue-700 @endif">
                        {{ $toast->type }}
                    </span>
                </div>
            </div>
            @empty
            <div class="text-center py-12 text-gray-400">No toast history found.</div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $toasts->withQueryString()->links() }}
        </div>
    </div>

    @if(session('toast'))
    <div id="session-toast" class="fixed bottom-5 right-5 px-6 py-3 rounded-xl shadow-2xl text-white font-medium animate-bounce
        @if(session('toast')['type'] == 'success') bg-green-600
        @elseif(session('toast')['type'] == 'error') bg-red-600
        @elseif(session('toast')['type'] == 'warning') bg-yellow-600
        @else bg-blue-600 @endif">
        {{ session('toast')['message'] }}
    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('session-toast');
            if(toast) toast.style.display = 'none';
        }, {{ session('toast')['duration'] ?? 3000 }});
    </script>
    @endif
</body>
</html>