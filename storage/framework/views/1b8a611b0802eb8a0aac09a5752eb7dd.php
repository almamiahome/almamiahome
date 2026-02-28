<?php if($paginator->hasPages()): ?>
    <div>
        <span class="inline-flex relative z-0 shadow-sm">
            
            <?php if($paginator->onFirstPage()): ?>
                <div class="inline-flex relative items-center px-2 py-2 text-sm font-medium leading-5 bg-white rounded-l-md border transition duration-150 ease-in-out text-zinc-400 border-zinc-300 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-zinc-100 active:text-zinc-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            <?php else: ?>
                <a href="<?php echo e($paginator->previousPageUrl()); ?>" class="inline-flex relative items-center px-2 py-2 text-sm font-medium leading-5 bg-white rounded-l-md border transition duration-150 ease-in-out text-zinc-700 border-zinc-300 hover:text-zinc-800 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-zinc-100 active:text-zinc-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </a>
            <?php endif; ?>

            
            <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                
                <?php if(is_string($element)): ?>
                    <span class="inline-flex relative items-center px-4 py-2 -ml-px text-sm font-medium leading-5 bg-white border text-zinc-700 border-zinc-300">
                    ...
                    </span>
                <?php endif; ?>

                
                <?php if(is_array($element)): ?>
                    <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $paginator->currentPage()): ?>
                            <div class="hidden relative items-center px-4 py-2 -ml-px text-sm font-medium leading-5 bg-white border transition duration-150 ease-in-out text-zinc-400 border-zinc-300 md:inline-flex focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-zinc-100 active:text-zinc-700">
                                <?php echo e($page); ?>

                            </div>
                        <?php else: ?>
                            <a href="<?php echo e($url); ?>" class="hidden relative items-center px-4 py-2 -ml-px text-sm font-medium leading-5 bg-white border transition duration-150 ease-in-out text-zinc-700 border-zinc-300 md:inline-flex hover:text-zinc-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-zinc-100 active:text-zinc-700">
                                <?php echo e($page); ?>

                            </a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($paginator->hasMorePages()): ?>
                <a href="<?php echo e($paginator->nextPageUrl()); ?>" class="inline-flex relative items-center px-2 py-2 -ml-px text-sm font-medium leading-5 bg-white rounded-r-md border transition duration-150 ease-in-out text-zinc-700 border-zinc-300 hover:text-zinc-800 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-zinc-100 active:text-zinc-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </a>
            <?php else: ?>
                <div class="inline-flex relative items-center px-2 py-2 -ml-px text-sm font-medium leading-5 bg-white rounded-r-md border transition duration-150 ease-in-out text-zinc-500 border-zinc-300 hover:text-zinc-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-zinc-100 active:text-zinc-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            <?php endif; ?>
        </span>
    </div>
<?php endif; ?>
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/partials/pagination.blade.php ENDPATH**/ ?>