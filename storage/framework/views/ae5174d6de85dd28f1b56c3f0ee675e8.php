<div class="w-full flex items-center justify-start md:justify-center">
<ul class="inline-flex self-start md:self-center px-3 py-2 mt-5 md:mt-7 w-auto text-xs font-medium rounded-full border bg-zinc-100 border-zinc-200 text-zinc-600 border-zinc-100">
    <li class="mr-4 font-bold uppercase text-zinc-800 md:block hidden">Categories:</li>
    <li class="<?php if(!isset($category)): ?><?php echo e('text-zinc-800'); ?><?php endif; ?>"><a href="<?php echo e(route('blog')); ?>">View All</a></li>
    <li class="mx-2">&middot;</li>
    <?php $__currentLoopData = \Wave\Category::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li class="<?php if(isset($category) && isset($category->slug) && ($category->slug == $cat->slug)): ?><?php echo e('text-zinc-800'); ?><?php endif; ?>"><a href="<?php echo e(route('blog.category', ['category' => $cat])); ?>"><?php echo e($cat->name); ?></a></li>
        <?php if(!$loop->last): ?>
            <li class="mx-2">&middot;</li>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
</div><?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/partials/blog/categories.blade.php ENDPATH**/ ?>