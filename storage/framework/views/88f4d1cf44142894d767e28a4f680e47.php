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
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="text-red-500 hover:text-red-600 text-sm">
                    Clear All
                </button>
            </form>
        </div>

        <!-- Toast List -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $toasts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $toast): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="border-b border-gray-100 p-4 hover:bg-gray-50">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <p class="font-medium text-gray-800"><?php echo e($toast->message); ?></p>
                        <p class="text-sm text-gray-400 mt-1">
                            <?php echo e($toast->created_at->format('M d, Y h:i A')); ?>

                        </p>
                    </div>
                    <span class="px-2 py-1 rounded text-xs font-medium
                        <?php if($toast->type == 'success'): ?> bg-green-100 text-green-700
                        <?php elseif($toast->type == 'error'): ?> bg-red-100 text-red-700
                        <?php elseif($toast->type == 'warning'): ?> bg-yellow-100 text-yellow-700
                        <?php else: ?> bg-blue-100 text-blue-700 <?php endif; ?>">
                        <?php echo e(ucfirst($toast->type)); ?>

                    </span>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-12">
                <p class="text-gray-400">No toast history yet</p>
                <a href="/" class="text-blue-500 hover:text-blue-600 text-sm mt-2 inline-block">
                    Create one →
                </a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            <?php echo e($toasts->links()); ?>

        </div>
    </div>

    <!-- Session Toast -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('toast')): ?>
    <div class="fixed bottom-5 right-5 px-4 py-2 rounded-lg shadow-lg text-white text-sm
        <?php if(session('toast')['type'] == 'success'): ?> bg-green-500
        <?php elseif(session('toast')['type'] == 'error'): ?> bg-red-500
        <?php else: ?> bg-blue-500 <?php endif; ?>">
        <?php echo e(session('toast')['message']); ?>

    </div>
    <script>
        setTimeout(() => {
            const toast = document.querySelector('.fixed.bottom-5');
            if(toast) toast.remove();
        }, 3000);
    </script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\PHP_Laravel11_Tall_Toasts\resources\views/history.blade.php ENDPATH**/ ?>