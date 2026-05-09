<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My To-Do List</title>
    <!-- Modern Typography and Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
    /* Gradient Background & Typography */
    body { 
        font-family: 'Inter', sans-serif; 
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); 
        min-height: 100vh;
        color: #2d3436; 
    }
    
    .app-container { margin-top: 50px; padding-bottom: 50px; }
    
    /* Header Styling */
    .header-section { margin-bottom: 40px; }
    .header-section h1 { 
        font-weight: 800; 
        color: #4834d4; 
        letter-spacing: -1px;
    }
    .header-section p { color: #636e72; font-weight: 500; }
    
    /* Card & Glassmorphism Effect */
    .card { 
        border: none; 
        border-radius: 16px; 
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); 
    }
    .card-title { font-weight: 700; color: #2d3436; }
    
    /* Input & Form Styling */
    .form-label { font-weight: 600; color: #4834d4; font-size: 0.85rem; }
    .form-control, .form-select { 
        border-radius: 10px; 
        border: 2px solid #edf2f7; 
        padding: 12px;
        transition: all 0.3s ease;
    }
    .form-control:focus { 
        border-color: #4834d4; 
        box-shadow: 0 0 0 3px rgba(72, 52, 212, 0.1); 
    }

    /* Vibrant Purple Button */
    .btn-add { 
        background: #4834d4; 
        color: white;
        border: none;
        border-radius: 10px; 
        padding: 12px; 
        font-weight: 600; 
        transition: transform 0.2s, background 0.2s;
    }
    .btn-add:hover { 
        background: #3c2bb3; 
        color: white;
        transform: translateY(-2px);
    }

    /* Table Styling */
    .table thead th { 
        background-color: rgba(72, 52, 212, 0.05);
        color: #4834d4;
        border: none;
        padding: 15px;
    }
    .task-title { font-weight: 700; color: #2d3436; }
    
    /* Enhanced Badges */
    .badge-priority { 
        padding: 6px 14px; 
        border-radius: 8px; 
        font-weight: 700; 
        font-size: 0.7rem; 
        text-transform: uppercase;
    }
    .priority-high { background-color: #ff7675; color: #fff; }
    .priority-medium { background-color: #fdcb6e; color: #fff; }
    .priority-low { background-color: #55efc4; color: #fff; }
    
    .badge-status { 
        padding: 6px 14px; 
        border-radius: 8px; 
        font-weight: 700; 
        font-size: 0.7rem;
        background: #fab1a0;
        color: #fff;
    }
    .status-completed { background-color: #00b894; }

    /* Action Buttons */
    .action-btn { 
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
    }
    .edit-btn { background: #e1f5fe; color: #03a9f4; }
    .edit-btn:hover { background: #03a9f4; color: white; }
    .delete-btn { background: #ffebee; color: #f44336; }
    .delete-btn:hover { background: #f44336; color: white; }
</style>

<div class="container app-container">
    <div class="header-section text-center">
        <h1>My To-Do List</h1>
        <p>Stay organized and get things done.</p>
    </div>

    <div class="row g-4">
        <!-- Left Column: Add New Task -->
        <div class="col-md-3">
            <div class="card p-4">
                <h5 class="card-title">Add New Task</h5>
                <form action="<?php echo e(route('todos.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label">Task Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter task title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Task Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Enter task description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-select">
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-add w-100">Add Task</button>
                </form>
            </div>
        </div>

        <!-- Right Column: Task List -->
        <div class="col-md-9">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">All Tasks</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button">All</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="40%">Title</th>
                                <th width="15%">Due Date</th>
                                <th width="15%">Priority</th>
                                <th width="15%">Status</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $todos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $todo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td>
                                    <span class="task-title"><?php echo e($todo->title); ?></span>
                                    <span class="task-desc"><?php echo e($todo->description); ?></span>
                                </td>
                                <td>
                                    <div class="small fw-bold">
                                        <?php echo e($todo->due_date ? date('d M Y', strtotime($todo->due_date)) : 'No date'); ?>

                                    </div>
                                </td>
                                <td>
                                    <span class="badge-priority <?php echo e(strtolower('priority-' . $todo->priority)); ?>">
                                        <?php echo e($todo->priority); ?>

                                    </span>
                                </td>
                                <td>
                                    <span class="badge-status <?php echo e($todo->status == 'Completed' ? 'status-completed' : 'status-pending'); ?>">
                                        <?php echo e($todo->status ?? 'Pending'); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="<?php echo e(route('todos.edit', $todo->id)); ?>" class="action-btn edit-btn" title="Edit">
                                            <small>📝</small>
                                        </a>
                                            <form action="<?php echo e(route('todos.destroy', $todo->id)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="action-btn delete-btn" title="Complete">
                                                <small>✔️</small>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html><?php /**PATH C:\Users\sahzabe\todo-app\resources\views/tasks/index.blade.php ENDPATH**/ ?>