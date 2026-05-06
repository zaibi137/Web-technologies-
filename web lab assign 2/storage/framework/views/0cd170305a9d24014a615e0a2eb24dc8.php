

<?php $__env->startSection('content'); ?>
<div class="glass-card p-4">
    <h3 class="fw-bold mb-4">Edit User</h3>

    <form action="<?php echo e(route('users.update', $user->id)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="row g-3">
            <div class="col-md-6">
                <label>Name</label>
                <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" class="form-control">
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-warning"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-6">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" class="form-control">
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-warning"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-6">
                <label>CNIC</label>
                <input type="text" name="cnic" value="<?php echo e(old('cnic', $user->cnic)); ?>" class="form-control">
                <?php $__errorArgs = ['cnic'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-warning"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-6">
                <label>Telephone</label>
                <input type="text" name="telephone" value="<?php echo e(old('telephone', $user->telephone)); ?>" class="form-control">
                <?php $__errorArgs = ['telephone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-warning"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-12">
                <label>Comments</label>
                <textarea name="comments" class="form-control" rows="4"><?php echo e(old('comments', $user->comments)); ?></textarea>
                <?php $__errorArgs = ['comments'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-warning"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-12">
                <label>Profile Picture</label>
                <input type="file" name="profile_picture" class="form-control" accept="image/*" onchange="previewImage(event)">
                <?php $__errorArgs = ['profile_picture'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-warning"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <?php if($user->profile_picture): ?>
                    <img id="preview" src="<?php echo e(asset('uploads/' . $user->profile_picture)); ?>" class="profile-img mt-3">
                <?php else: ?>
                    <img id="preview" class="profile-img mt-3 d-none">
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button class="premium-btn">
                <i class="bi bi-save"></i> Update User
            </button>

            <a href="<?php echo e(route('users.index')); ?>" class="btn btn-light rounded-pill px-4">Back</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    function previewImage(event) {
        let preview = document.getElementById('preview');
        preview.src = URL.createObjectURL(event.target.files[0]);
        preview.classList.remove('d-none');
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMP\htdocs\SmartProfileManagementSystem\resources\views/users/edit.blade.php ENDPATH**/ ?>