<!-- resources/views/components/auth/elements/session-message.blade.php -->
<?php
$messageTypes = ['error', 'warning', 'success', 'info'];
$message = null;
$type = null;

foreach ($messageTypes as $messageType) {
    if (session()->has($messageType)) {
        $message = session($messageType);
        $type = $messageType;
        break;
    }
}
?>

<!--[if BLOCK]><![endif]--><?php if($message): ?>
    <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
        'mb-6 p-4 text-sm rounded-lg',
        'bg-red-100 text-red-700' => $type == 'error',
        'bg-orange-100 text-orange-700' => $type == 'warning',
        'bg-green-100 text-green-700' => $type == 'success',
        'bg-blue-100 text-blue-700' => $type == 'info',
    ]); ?>" role="alert">
        <?php echo e($message); ?>

    </div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]--><?php /**PATH /home/unquxtyh/public_html/vendor/devdojo/auth/src/../resources/views/components/elements/session-message.blade.php ENDPATH**/ ?>