<!-- Loop Through Posts Here -->
<?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <article id="post-<?php echo e($post->id); ?>" class="overflow-hidden relative col-span-2 p-4 bg-white rounded-2xl border cursor-pointer border-zinc-200 border-zinc-100 dark:bg-black sm:col-span-1" onClick="window.location='<?php echo e($post->link()); ?>'">
        <meta property="name" content="<?php echo e($post->title); ?>">
        <meta property="author" typeof="Person" content="admin">
        <meta property="dateModified" content="<?php echo e(Carbon\Carbon::parse($post->updated_at)->toIso8601String()); ?>">
        <meta class="uk-margin-remove-adjacent" property="datePublished" content="<?php echo e(Carbon\Carbon::parse($post->created_at)->toIso8601String()); ?>">

        <img src="<?php echo e($post->image()); ?>" class="w-full h-auto rounded-lg">
        <div class="px-1 py-1">
            <div class="flex gap-x-4 items-center my-3 text-xs">
                <time datetime="2020-03-16" class="text-zinc-500"><?php echo e($post->updated_at->format('M d, Y')); ?></time>
                <a href="/blog/<?php echo e($post->category->slug); ?>" class="relative z-10 px-3 py-1.5 font-medium text-zinc-600 bg-zinc-50 rounded-full hover:bg-zinc-100"><?php echo e($post->category->name); ?></a>
              </div>
            <h2 class="text-lg font-semibold leading-6 text-zinc-900 group-hover:text-zinc-600">
                <a href="<?php echo e($post->link()); ?>">
                    <span class="absolute inset-0"></span>
                    <?php echo e($post->title); ?>

                </a>
            </h2>
            <p class="mt-5 text-sm leading-6 text-zinc-600 line-clamp-3"><?php echo e(substr(strip_tags($post->body), 0, 110)); ?><?php if(strlen(strip_tags($post->body)) > 200): ?><?php echo e('...'); ?><?php endif; ?></p>
            
        </div>
    </article>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<!-- End Post Loop Here --><?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/partials/blog/posts-loop.blade.php ENDPATH**/ ?>