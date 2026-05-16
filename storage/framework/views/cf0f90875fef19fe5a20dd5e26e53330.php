<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex flex-col items-center justify-center p-4">

    <div class="w-full max-w-xl bg-white shadow-md rounded-xl overflow-hidden">
        <div class="bg-indigo-600 text-white text-center py-6">
            <h1 class="text-3xl font-bold tracking-wide">Edit Task</h1>
        </div>

        <form action="<?php echo e(route('todos.update', $todo->id)); ?>" method="POST" class="p-6 space-y-5">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?> <div>
                <label for="title" class="block text-sm font-semibold text-indigo-900 mb-1">Task Title</label>
                <input type="text" name="title" id="title" value="<?php echo e(old('title', $todo->title)); ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-indigo-900 mb-1">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"><?php echo e(old('description', $todo->description)); ?></textarea>
            </div>

            <div>
                <label for="due_date" class="block text-sm font-semibold text-indigo-900 mb-1">Due Date</label>
                <input type="date" name="due_date" id="due_date" value="<?php echo e(old('due_date', $todo->due_date)); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="priority" class="block text-sm font-semibold text-indigo-900 mb-1">Priority Level</label>
                    <select name="priority" id="priority" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition bg-white">
                        <option value="Low" <?php echo e($todo->priority == 'Low' ? 'selected' : ''); ?>>Low</option>
                        <option value="Medium" <?php echo e($todo->priority == 'Medium' ? 'selected' : ''); ?>>Medium</option>
                        <option value="High" <?php echo e($todo->priority == 'High' ? 'selected' : ''); ?>>High</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-semibold text-indigo-900 mb-1">Task Status</label>
                    <select name="status" id="status" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition bg-white">
                        <option value="Pending" <?php echo e(strtolower($todo->status) == 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="Completed" <?php echo e(strtolower($todo->status) == 'completed' ? 'selected' : ''); ?>>Completed</option>
                    </select>
                </div>
            </div>

            <div class="pt-2 space-y-3">
                <button type="submit" 
                    class="w-full py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 shadow-md transition duration-150">
                    Update Task
                </button>
                <div class="text-center">
                    <a href="<?php echo e(route('todos.index')); ?>" class="text-sm font-semibold text-indigo-600 hover:underline">
                        Cancel & Go Back
                    </a>
                </div>
            </div>
        </form>
    </div>

</body>
</html><?php /**PATH C:\Users\sahzabe\todo-app\resources\views/tasks/edit.blade.php ENDPATH**/ ?>