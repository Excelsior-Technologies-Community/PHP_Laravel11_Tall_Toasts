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
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <a href="/" class="text-blue-500 hover:text-blue-600">
                ← Back to Home
            </a>
            <h1 class="text-2xl font-bold text-gray-800">
                Toast History
            </h1>
            <form action="/clear-history" method="POST" 
                  onsubmit="return confirm('Clear all history?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-600 text-sm">
                    Clear All
                </button>
            </form>
        </div>

        <!-- Toast List -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @forelse($toasts as $toast)
            <div class="border-b border-gray-100 p-4 hover:bg-gray-50">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <p class="font-medium text-gray-800">{{ $toast->message }}</p>
                        <p class="text-sm text-gray-400 mt-1">
                            {{ $toast->created_at->format('M d, Y h:i A') }}
                        </p>
                    </div>
                    <span class="px-2 py-1 rounded text-xs font-medium
                        @if($toast->type == 'success') bg-green-100 text-green-700
                        @elseif($toast->type == 'error') bg-red-100 text-red-700
                        @elseif($toast->type == 'warning') bg-yellow-100 text-yellow-700
                        @else bg-blue-100 text-blue-700 @endif">
                        {{ ucfirst($toast->type) }}
                    </span>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <p class="text-gray-400">No toast history yet</p>
                <a href="/" class="text-blue-500 hover:text-blue-600 text-sm mt-2 inline-block">
                    Create one →
                </a>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $toasts->links() }}
        </div>
    </div>

    <!-- Session Toast -->
    @if(session('toast'))
    <div class="fixed bottom-5 right-5 px-4 py-2 rounded-lg shadow-lg text-white text-sm
        @if(session('toast')['type'] == 'success') bg-green-500
        @elseif(session('toast')['type'] == 'error') bg-red-500
        @else bg-blue-500 @endif">
        {{ session('toast')['message'] }}
    </div>
    <script>
        setTimeout(() => {
            const toast = document.querySelector('.fixed.bottom-5');
            if(toast) toast.remove();
        }, 3000);
    </script>
    @endif
</body>
</html>