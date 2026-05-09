<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .header-purple { background-color: #6f42c1; color: white; padding: 20px; margin-bottom: 30px; }
        .btn-purple { background-color: #6f42c1; color: white; }
    </style>
</head>
<body>
    <div class="header-purple text-center"><h1>Edit Task</h1></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm p-4">
                    <form action="<?php echo e(route('todos.update', $todo->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="mb-3">
                            <label class="form-label">Task Title</label>
                            <input type="text" name="title" class="form-control" value="<?php echo e($todo->title); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"><?php echo e($todo->description); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-purple w-100">Update Task</button>
                        <a href="<?php echo e(route('todos.index')); ?>" class="btn btn-link w-100 mt-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Users\sahzabe\todo-app\resources\views/tasks/edit.blade.php ENDPATH**/ ?>