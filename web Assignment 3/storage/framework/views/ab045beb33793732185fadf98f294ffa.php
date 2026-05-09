<!DOCTYPE html>
<html>
<head>
    <title>Create Todo</title>
</head>
<body>

<h1>Add Todo</h1>

<form action="<?php echo e(route('todos.store')); ?>" method="POST">
    <?php echo csrf_field(); ?>

    <input type="text" name="title" placeholder="Title">

    <br><br>

    <textarea name="description" placeholder="Description"></textarea>

    <br><br>

    <button type="submit">Save</button>
</form>

</body>
</html><?php /**PATH C:\Users\sahzabe\todo-app\resources\views/tasks/create.blade.php ENDPATH**/ ?>