<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Toast animations */
        @keyframes toastIn {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes toastOut {
            0% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-10px); }
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen font-sans">

    <!-- Modern card -->
    <div class="bg-white shadow-2xl rounded-3xl p-8 md:p-12 flex flex-col items-center gap-6 w-full max-w-md relative">

        <!-- Welcome text -->
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 text-center">Welcome!</h1>
        <p class="text-gray-500 text-center md:text-lg">Select an action below to see toast notifications.</p>

        <!-- Toast message inside card -->
        @if(session('toast'))
            @php
                $type = session('toast')['type'];
                $message = session('toast')['message'];
                $color = match($type) {
                    'success' => 'green',
                    'error' => 'red',
                    'info' => 'blue',
                    default => 'gray',
                };
            @endphp

            <div id="toast"
                 class="w-full px-6 py-3 rounded-xl text-white font-medium text-center
                        bg-{{ $color }}-600 shadow-lg opacity-0 mb-4">
                {{ $message }}
            </div>

            <script>
                const toast = document.getElementById('toast');
                if(toast) {
                    toast.style.opacity = '1';
                    toast.style.animation = 'toastIn 0.4s forwards';

                    setTimeout(() => {
                        toast.style.animation = 'toastOut 0.4s forwards';
                    }, 3000);
                }
            </script>
        @endif

        <!-- Action buttons -->
        <div class="flex flex-wrap justify-center gap-4 w-full mt-2">
            <a href="/success" 
               class="flex-1 text-center px-6 py-3 bg-green-500 text-white rounded-xl shadow-md hover:bg-green-600 hover:shadow-lg transition-all duration-300">
               Success
            </a>
            <a href="/error" 
               class="flex-1 text-center px-6 py-3 bg-red-500 text-white rounded-xl shadow-md hover:bg-red-600 hover:shadow-lg transition-all duration-300">
               Error
            </a>
            <a href="/info" 
               class="flex-1 text-center px-6 py-3 bg-blue-500 text-white rounded-xl shadow-md hover:bg-blue-600 hover:shadow-lg transition-all duration-300">
               Info
            </a>
        </div>

    </div>

</body>
</html>