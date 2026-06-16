<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Toast History</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="flex justify-between items-center mb-8">
            <a href="/" class="text-blue-500 hover:text-blue-600 transition">← Back</a>
            <h1 class="text-2xl font-bold text-gray-800">Toast History</h1>
            <form action="/clear-history" method="POST" onsubmit="return confirm('Clear all history?')">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="text-red-500 hover:text-red-600 text-sm font-semibold">Clear All</button>
            </form>
        </div>

        <form method="GET" action="<?php echo e(route('history')); ?>" class="bg-white p-4 rounded-xl shadow-sm mb-6 flex gap-3">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search messages..." class="flex-1 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
            <select name="type" class="border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                <option value="">All Types</option>
                <option value="success" <?php echo e(request('type') == 'success' ? 'selected' : ''); ?>>Success</option>
                <option value="error" <?php echo e(request('type') == 'error' ? 'selected' : ''); ?>>Error</option>
                <option value="warning" <?php echo e(request('type') == 'warning' ? 'selected' : ''); ?>>Warning</option>
                <option value="info" <?php echo e(request('type') == 'info' ? 'selected' : ''); ?>>Info</option>
            </select>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">Filter</button>
        </form>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $toasts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $toast): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="border-b border-gray-100 p-4 hover:bg-gray-50 transition">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-medium text-gray-800"><?php echo e($toast->message); ?></p>
                        <p class="text-xs text-gray-400 mt-1"><?php echo e($toast->created_at->format('M d, Y h:i A')); ?></p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                        <?php if($toast->type == 'success'): ?> bg-green-100 text-green-700
                        <?php elseif($toast->type == 'error'): ?> bg-red-100 text-red-700
                        <?php elseif($toast->type == 'warning'): ?> bg-yellow-100 text-yellow-700
                        <?php else: ?> bg-blue-100 text-blue-700 <?php endif; ?>">
                        <?php echo e($toast->type); ?>

                    </span>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-12 text-gray-400">No toast history found.</div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="mt-6">
            <?php echo e($toasts->withQueryString()->links()); ?>

        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('toast')): ?>
    <div id="session-toast" class="fixed bottom-5 right-5 px-6 py-3 rounded-xl shadow-2xl text-white font-medium animate-bounce
        <?php if(session('toast')['type'] == 'success'): ?> bg-green-600
        <?php elseif(session('toast')['type'] == 'error'): ?> bg-red-600
        <?php elseif(session('toast')['type'] == 'warning'): ?> bg-yellow-600
        <?php else: ?> bg-blue-600 <?php endif; ?>">
        <?php echo e(session('toast')['message']); ?>

    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('session-toast');
            if(toast) toast.style.display = 'none';
        }, <?php echo e(session('toast')['duration'] ?? 3000); ?>);
    </script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</body>
</html><?php /**PATH D:\xampp\htdocs\git_desktop\PHP_Laravel11_Tall_Toasts\resources\views/history.blade.php ENDPATH**/ ?>