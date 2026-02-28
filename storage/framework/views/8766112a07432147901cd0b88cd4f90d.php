<?php

use function Laravel\Folio\{name};









use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;

?>


        <div class="flex overflow-hidden relative justify-start items-start w-full h-full bg-white rounded-xl border shadow-sm border-zinc-200/50">
            <div class="w-full h-full">
                <div x-data="{ 
                        active: <?php if ((object) ('selectedFile') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedFile'->value()); ?>')<?php echo e('selectedFile'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedFile'); ?>')<?php endif; ?>,
                        search: <?php if ((object) ('search') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('search'->value()); ?>')<?php echo e('search'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('search'); ?>')<?php endif; ?>,
                        searchResults: <?php if ((object) ('searchResults') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('searchResults'->value()); ?>')<?php echo e('searchResults'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('searchResults'); ?>')<?php endif; ?>,
                        clientSideActive: false,
                        files: <?php if ((object) ('files') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('files'->value()); ?>')<?php echo e('files'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('files'); ?>')<?php endif; ?>, 
                        storageURL: <?php if ((object) ('storageURL') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('storageURL'->value()); ?>')<?php echo e('storageURL'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('storageURL'); ?>')<?php endif; ?>,
                        activeFileDrawer: true,
                        fileOrFolderCopied: <?php if ((object) ('fileOrFolderCopied') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('fileOrFolderCopied'->value()); ?>')<?php echo e('fileOrFolderCopied'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('fileOrFolderCopied'); ?>')<?php endif; ?>,
                        view: $persist('grid').as('media-view'),
                        searchFocused: false,
                        isActiveFile(file) {
                            if(!this.active){
                                return false;
                            }
                            
                            return this.active.relative_path == file.relative_path;
                        },
                        activeFileSelected() {
                            if(!this.active) {
                                return false;
                            }
                            return true;
                        },
                        setSelectedFileNull(){
                            $wire.setSelectedFile(null);
                            this.active=null;
                        },
                        getFullImagePath(){
                            if(this.active){
                                return this.storageURL + this.active.relative_path
                            } else {
                                return '';
                            }
                        },
                        handleKeydown(event) {
                            // Check if an input element is focused
                            if (document.activeElement.tagName === 'INPUT' || document.activeElement.tagName === 'TEXTAREA') {
                                return; // Exit the function if an input is focused
                            }

                            if (event.code === 'Space' || event.keyCode === 32) {
                                event.preventDefault(); // Prevent page scroll
                                window.dispatchEvent(new CustomEvent('open-file-modal', { detail: { file: this.active }}));

                            } else if (event.code === 'Delete' || event.keyCode === 46 || event.code === 'Backspace' || event.keyCode === 8) {
                                event.preventDefault();
                                console.log('Delete key pressed');
                                // Your delete key action here
                                if (this.activeFileSelected()) {
                                    $wire.triggerDeleteAction();
                                }
                            } else if (event.code === 'Escape' || event.keyCode === 27){
                                window.dispatchEvent(new CustomEvent('close-file-modal'));
                            }
                        },
                        renameActive(){
                            if(this.active){
                                document.getElementById('active-file').dispatchEvent(new CustomEvent('rename'));
                            }
                        },
                        handleFileDoubleClick(file){
                            if(file.type == 'folder'){
                                this.goToActiveDirectory();
                            } else {
                                window.dispatchEvent(new CustomEvent('open-file-modal', { detail: { file: file }}));
                            }
                        },
                        goToActiveDirectory(){
                            $wire.goToDirectory(this.active.relative_path)
                        },
                        setActiveFileBasedOnName(name){
                            let that = this;
                            setTimeout(function(){
                                that.files.forEach(file => {
                                    if (file.name === name) {
                                        that.active = file;
                                    }
                                });
                            }, 1);
                        },
                        isUploading: false, 
                        progress: 0
                    }"
                    x-init="
                        $watch('active', function(value){
                            if(value == null){
                                clientSideActive = false;
                            } else {
                                clientSideActive = true;
                            }
                        })
                    "
                    @set-active-file.window="setActiveFileBasedOnName($event.detail.name)"
                    @go-to-active-directory.window="goToActiveDirectory()"
                    @duplicate.window="$wire.duplicate()"
                    @copy.window="$wire.copy()"
                    @paste.window="$wire.paste()"
                    @rename-active.window="renameActive()"
                    x-on:keydown.window="handleKeydown"
                    @trigger-delete-action.window="$wire.triggerDeleteAction"
                    class="flex flex-col justify-start items-center w-full h-full bg-white">
                    
                    <div class="relative p-5 pb-0 w-full bg-white border-b border-zinc-200/70">
                        <?php echo $__env->make('wave::media.views.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php echo $__env->make('wave::media.views.breadcrumbs', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>

                    <div class="flex justify-start items-start w-full h-full bg-white">
                        <?php echo $__env->make('wave::media.views.files', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php echo $__env->make('wave::media.views.active-file', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                    <?php echo $__env->make('wave::media.views.full-screen-file-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
            
        <?php if (isset($component)) { $__componentOriginal028e05680f6c5b1e293abd7fbe5f9758 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal028e05680f6c5b1e293abd7fbe5f9758 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-actions::components.modals','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-actions::modals'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal028e05680f6c5b1e293abd7fbe5f9758)): ?>
<?php $attributes = $__attributesOriginal028e05680f6c5b1e293abd7fbe5f9758; ?>
<?php unset($__attributesOriginal028e05680f6c5b1e293abd7fbe5f9758); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal028e05680f6c5b1e293abd7fbe5f9758)): ?>
<?php $component = $__componentOriginal028e05680f6c5b1e293abd7fbe5f9758; ?>
<?php unset($__componentOriginal028e05680f6c5b1e293abd7fbe5f9758); ?>
<?php endif; ?>
        </div>
    <?php /**PATH /home/unquxtyh/public_html/storage/framework/views/62933a2951ef01f4eafd9bdf4d3cd2f0.blade.php ENDPATH**/ ?>