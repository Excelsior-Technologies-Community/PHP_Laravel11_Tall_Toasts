<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Toast Notifications</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <style>
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
        .toast-slide-in { animation: slideIn 0.3s ease forwards; }
        .toast-slide-out { animation: slideOut 0.3s ease forwards; }
        .progress-bar { transition: width linear; }
    </style>
</head>
<body class="bg-gray-50 font-sans">

    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Toast Notifications</h1>
            <p class="text-gray-500">Real-time and lightweight notifications</p>
        </div>

        <div id="toast-container" class="fixed top-5 right-5 z-50 space-y-3"></div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <button onclick="triggerToast('/success')" class="bg-green-500 hover:bg-green-600 text-white text-center px-5 py-3 rounded-lg font-medium transition">Success</button>
            <button onclick="triggerToast('/error')" class="bg-red-500 hover:bg-red-600 text-white text-center px-5 py-3 rounded-lg font-medium transition">Error</button>
            <button onclick="triggerToast('/info')" class="bg-blue-500 hover:bg-blue-600 text-white text-center px-5 py-3 rounded-lg font-medium transition">Info</button>
            <button onclick="triggerToast('/warning')" class="bg-yellow-500 hover:bg-yellow-600 text-white text-center px-5 py-3 rounded-lg font-medium transition">Warning</button>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-5 text-center">Custom Toast</h2>
            <form id="customToastForm" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <input type="text" name="message" required placeholder="Enter your message..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="success">Success</option>
                        <option value="error">Error</option>
                        <option value="info">Info</option>
                        <option value="warning">Warning</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg font-medium transition">Show Toast</button>
            </form>
        </div>

        <div class="text-center mt-8">
            <a href="/toast-history" class="text-blue-500 hover:text-blue-600 text-sm">View Toast History →</a>
        </div>
    </div>

<script>
        Pusher.logToConsole = false;
        const pusher = new Pusher("<?php echo e(config('broadcasting.connections.pusher.key')); ?>", {
            cluster: "<?php echo e(config('broadcasting.connections.pusher.options.cluster')); ?>"
        });

        const channel = pusher.subscribe('toasts');

        function handleToastEvent(data) {
            showToast(data.message, data.type, 7000);
        }

        channel.bind('ToastSent', handleToastEvent);
        channel.bind('App\\Events\\ToastSent', handleToastEvent);

        function triggerToast(url) {
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } }).catch(e => console.log('Request failed', e));
        }

        document.getElementById('customToastForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('/toast/custom', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            }).catch(err => console.log('Request failed', err));
            this.reset();
        });

        function playTone(audioCtx, frequency, startTime, duration, waveType, peakGain) {
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();
            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);
            oscillator.type = waveType;
            oscillator.frequency.setValueAtTime(frequency, startTime);
            gainNode.gain.setValueAtTime(0.001, startTime);
            gainNode.gain.exponentialRampToValueAtTime(peakGain, startTime + 0.02);
            gainNode.gain.exponentialRampToValueAtTime(0.001, startTime + duration);
            oscillator.start(startTime);
            oscillator.stop(startTime + duration);
        }

        function playNotificationSound(type) {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const now = audioCtx.currentTime;

                if (type === 'success') {
                    playTone(audioCtx, 660, now, 0.15, 'sine', 0.3);
                    playTone(audioCtx, 990, now + 0.12, 0.2, 'sine', 0.3);
                } else if (type === 'error') {
                    playTone(audioCtx, 320, now, 0.3, 'sawtooth', 0.25);
                    playTone(audioCtx, 200, now + 0.15, 0.3, 'sawtooth', 0.25);
                } else if (type === 'warning') {
                    playTone(audioCtx, 740, now, 0.15, 'triangle', 0.3);
                    playTone(audioCtx, 740, now + 0.2, 0.15, 'triangle', 0.3);
                } else {
                    playTone(audioCtx, 523, now, 0.25, 'sine', 0.3);
                }
            } catch (e) {
                console.log('Audio play blocked', e);
            }
        }

        function showToast(message, type, duration = 7000) {
            playNotificationSound(type);
            
            const container = document.getElementById('toast-container');
            const colors = { success: 'bg-green-500', error: 'bg-red-500', info: 'bg-blue-500', warning: 'bg-yellow-500' };
            const bgColor = colors[type] || colors.info;
            const id = 'toast-' + Date.now();
            
            const toastHtml = `
                <div id="${id}" class="toast-slide-in ${bgColor} text-white px-5 py-3 rounded-lg shadow-lg min-w-[250px]">
                    <div class="flex justify-between items-center gap-3">
                        <span class="font-medium">${escapeHtml(message)}</span>
                        <button onclick="removeToast('${id}')" class="text-white hover:text-gray-200 text-lg leading-none">&times;</button>
                    </div>
                    <div class="progress-bar bg-white opacity-30 h-0.5 mt-2" style="width: 100%;"></div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', toastHtml);
            
            const toast = document.getElementById(id);
            const progressBar = toast.querySelector('.progress-bar');
            let width = 100;
            const interval = setInterval(() => {
                width -= (100 / (duration / 100));
                if (progressBar) progressBar.style.width = width + '%';
                if (width <= 0) clearInterval(interval);
            }, 100);
            
            setTimeout(() => removeToast(id), duration);
        }
        
        function removeToast(id) {
            const toast = document.getElementById(id);
            if (toast) {
                toast.classList.remove('toast-slide-in');
                toast.classList.add('toast-slide-out');
                setTimeout(() => toast.remove(), 300);
            }
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html><?php /**PATH D:\xampp\htdocs\git_desktop\PHP_Laravel11_Tall_Toasts\resources\views/welcome.blade.php ENDPATH**/ ?>