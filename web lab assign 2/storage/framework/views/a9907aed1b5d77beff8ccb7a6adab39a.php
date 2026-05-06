<?php use Illuminate\Support\Str; ?>



<?php $__env->startSection('content'); ?>
<div class="glass-card p-4">

    <form action="<?php echo e(route('users.index')); ?>" method="GET" class="row g-3 mb-4">
        <div class="col-md-9">
            <input type="email" name="search" value="<?php echo e($search); ?>" class="form-control"
                   placeholder="Search user by email...">
        </div>

        <div class="col-md-3 d-grid">
            <button class="premium-btn">
                <i class="bi bi-search"></i> Search
            </button>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Registered Users</h4>
        <span class="badge bg-light text-dark rounded-pill">
            Total: <?php echo e($users->total()); ?>

        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>CNIC</th>
                    <th>Telephone</th>
                    <th>Comments</th>
                    <th width="170">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <?php if($user->profile_picture): ?>
                                <img src="<?php echo e(asset('uploads/' . $user->profile_picture)); ?>" class="profile-img">
                            <?php else: ?>
                                <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($user->name)); ?>" class="profile-img">
                            <?php endif; ?>
                        </td>

                        <td><?php echo e($user->name); ?></td>
                        <td><?php echo e($user->email); ?></td>
                        <td><?php echo e($user->cnic); ?></td>
                        <td><?php echo e($user->telephone); ?></td>
                        <td><?php echo e(Str::limit($user->comments, 40)); ?></td>

                        <td>
                            <a href="<?php echo e(route('users.edit', $user->id)); ?>" class="btn btn-sm btn-warning rounded-pill">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form id="delete-form-<?php echo e($user->id); ?>"
                                  action="<?php echo e(route('users.destroy', $user->id)); ?>"
                                  method="POST"
                                  class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>

                                <button type="button"
                                        onclick="confirmDelete('delete-form-<?php echo e($user->id); ?>')"
                                        class="btn btn-sm btn-danger rounded-pill">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <h5>No users found</h5>
                            <p class="muted-text">Try another email or add a new user.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <?php echo e($users->links()); ?>

    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMP\htdocs\SmartProfileManagementSystem\resources\views/users/index.blade.php ENDPATH**/ ?>