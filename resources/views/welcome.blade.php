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

<!-- DARK MODE WRAPPER -->
<body x-data="{ dark: false }"
      :class="dark ? 'dark bg-gray-900 text-white' : 'bg-gray-100 text-black'"
      class="flex items-center justify-center min-h-screen font-sans transition-all duration-300">

    <!-- DARK MODE BUTTON -->
    <div class="absolute top-4 right-4">
        <button @click="dark = !dark"
            class="px-4 py-2 rounded-xl bg-gray-800 text-white shadow hover:bg-gray-700 transition">
            Toggle Dark Mode
        </button>
    </div>

    <!-- Modern card -->
    <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-3xl p-8 md:p-12 flex flex-col items-center gap-6 w-full max-w-md relative">

        <!-- Welcome text -->
        <h1 class="text-4xl md:text-5xl font-extrabold text-center">
            Welcome!
        </h1>

        <p class="text-gray-500 dark:text-gray-300 text-center md:text-lg">
            Select an action below to see toast notifications.
        </p>

        <!-- TOAST MESSAGE -->
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
                 class="w-full px-6 py-3 rounded-xl text-white font-medium text-center bg-{{ $color }}-600 shadow-lg opacity-0 mb-4">
                {{ $message }}
            </div>

            <script>
                const toast = document.getElementById('toast');
                if (toast) {
                    toast.style.opacity = '1';
                    toast.style.animation = 'toastIn 0.4s forwards';

                    setTimeout(() => {
                        toast.style.animation = 'toastOut 0.4s forwards';
                    }, 3000);
                }
            </script>
        @endif

        <!-- CUSTOM TOAST FORM -->
        <form action="/toast/custom" method="POST"
              class="w-full bg-gray-50 dark:bg-gray-700 p-6 rounded-xl shadow-md mb-4">

            @csrf

            <h2 class="text-lg font-bold mb-3">Create Custom Toast</h2>

            <!-- Message -->
            <input type="text" name="message" placeholder="Enter message"
                class="w-full p-3 rounded-lg border mb-3 text-black"
                required>

            <!-- Type -->
            <select name="type"
                class="w-full p-3 rounded-lg border mb-3 text-black"
                required>
                <option value="success">Success</option>
                <option value="error">Error</option>
                <option value="info">Info</option>
            </select>

            <button type="submit"
                class="w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 transition">
                Show Toast
            </button>
        </form>

        <!-- DEFAULT BUTTONS -->
        <div class="flex gap-3 w-full">

            <a href="/success"
               class="flex-1 text-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                Success
            </a>

            <a href="/error"
               class="flex-1 text-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                Error
            </a>

            <a href="/info"
               class="flex-1 text-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                Info
            </a>

        </div>

    </div>

    <!-- Alpine.js (IMPORTANT for dark mode) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>
</html>