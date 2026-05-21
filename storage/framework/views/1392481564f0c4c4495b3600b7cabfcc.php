<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Toast Notifications</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .toast-slide-in {
            animation: slideIn 0.3s ease forwards;
        }
        
        .toast-slide-out {
            animation: slideOut 0.3s ease forwards;
        }
        
        .progress-bar {
            transition: width linear;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">

    <div class="max-w-4xl mx-auto px-4 py-12">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">
                Toast Notifications
            </h1>
            <p class="text-gray-500">
                Simple and lightweight toast messages
            </p>
        </div>

        <!-- Toast Container -->
        <div id="toast-container" class="fixed top-5 right-5 z-50 space-y-3"></div>

        <!-- Session Toast -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('toast')): ?>
        <div id="session-toast" 
             class="fixed top-5 right-5 z-50 px-5 py-3 rounded-lg shadow-lg text-white font-medium
                <?php if(session('toast')['type'] == 'success'): ?> bg-green-500
                <?php elseif(session('toast')['type'] == 'error'): ?> bg-red-500
                <?php elseif(session('toast')['type'] == 'warning'): ?> bg-yellow-500
                <?php else: ?> bg-blue-500 <?php endif; ?>">
            <?php echo e(session('toast')['message']); ?>

        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('session-toast');
                if(toast) {
                    toast.style.animation = 'slideOut 0.3s ease forwards';
                    setTimeout(() => toast.remove(), 300);
                }
            }, 3000);
        </script>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Buttons Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <a href="/success" 
               class="bg-green-500 hover:bg-green-600 text-white text-center px-5 py-3 rounded-lg font-medium transition">
                Success
            </a>
            <a href="/error" 
               class="bg-red-500 hover:bg-red-600 text-white text-center px-5 py-3 rounded-lg font-medium transition">
                Error
            </a>
            <a href="/info" 
               class="bg-blue-500 hover:bg-blue-600 text-white text-center px-5 py-3 rounded-lg font-medium transition">
                Info
            </a>
            <a href="/warning" 
               class="bg-yellow-500 hover:bg-yellow-600 text-white text-center px-5 py-3 rounded-lg font-medium transition">
                Warning
            </a>
        </div>

        <!-- Custom Toast Form -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-5 text-center">
                Custom Toast
            </h2>
            
            <form action="/toast/custom" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Message
                    </label>
                    <input type="text" 
                           name="message" 
                           required
                           placeholder="Enter your message..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Type
                    </label>
                    <select name="type" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="success">Success</option>
                        <option value="error">Error</option>
                        <option value="info">Info</option>
                        <option value="warning">Warning</option>
                    </select>
                </div>
                
                <button type="submit" 
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg font-medium transition">
                    Show Toast
                </button>
            </form>
        </div>

        <!-- History Link -->
        <div class="text-center mt-8">
            <a href="/toast-history" 
               class="text-blue-500 hover:text-blue-600 text-sm">
                View Toast History →
            </a>
        </div>
    </div>

    <script>
        // Function to show toast notifications
        function showToast(message, type) {
            const container = document.getElementById('toast-container');
            if (!container) return;
            
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                info: 'bg-blue-500',
                warning: 'bg-yellow-500'
            };
            
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
                width -= 100 / 30;
                if (width <= 0) {
                    clearInterval(interval);
                } else if (progressBar) {
                    progressBar.style.width = width + '%';
                }
            }, 100);
            
            setTimeout(() => {
                removeToast(id);
            }, 3000);
        }
        
        function removeToast(id) {
            const toast = document.getElementById(id);
            if (toast) {
                toast.classList.remove('toast-slide-in');
                toast.classList.add('toast-slide-out');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Listen for AJAX toasts (optional)
        document.addEventListener('showToast', function(e) {
            showToast(e.detail.message, e.detail.type);
        });
    </script>
</body>
</html><?php /**PATH C:\xampp\htdocs\PHP_Laravel11_Tall_Toasts\resources\views/welcome.blade.php ENDPATH**/ ?>