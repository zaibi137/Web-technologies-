<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskSphere Workspace - My Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Modern Vibrant Light Theme Background */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
            min-height: 100vh;
        }

        /* Clean Premium Navbar */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            padding: 15px 0;
        }

        .navbar-brand-text {
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-user-badge {
            background: rgba(99, 102, 241, 0.08);
            border: 1px solid rgba(99, 102, 241, 0.15);
            color: #4f46e5;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        /* Container Layout Spacing */
        .workspace-container {
            padding-top: 110px;
            padding-bottom: 60px;
        }

        /* Brand Title Typography */
        .page-main-heading {
            font-size: 2.8rem;
            font-weight: 800;
            letter-spacing: -1px;
            background: linear-gradient(135deg, #1e1b4b 30%, #4f46e5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* High-Contrast Beautiful White Cards */
        .panel-card {
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03);
            height: 100%;
        }

        .panel-card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            letter-spacing: -0.3px;
        }

        /* Vivid Sidebar Form Label Elements */
        .form-label {
            font-size: 0.82rem;
            font-weight: 700;
            color: #4f46e5;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Clean High-Visibility Input Elements */
        .form-control, .form-select {
            background-color: #f8fafc !important;
            border: 1px solid #cbd5e1 !important;
            color: #0f172a !important;
            border-radius: 10px;
            padding: 12px;
            font-size: 0.92rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
            background-color: #ffffff !important;
        }

        .form-control::placeholder {
            color: #94a3b8 !important;
        }

        /* TaskSphere Primary Interaction Button */
        .btn-workspace {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: white !important;
            border: none;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.25);
            transition: all 0.25s ease;
            width: 100%;
        }

        .btn-workspace:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
        }

        /* Filter Action Customization */
        .btn-filter {
            background: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            color: #475569 !important;
            font-weight: 600;
            font-size: 0.88rem;
            padding: 8px 16px;
            border-radius: 10px;
        }

        .btn-filter:hover {
            background: #f1f5f9 !important;
            color: #0f172a !important;
        }

        /* Crisp High-Visibility Table Component */
        .table {
            color: #334155 !important;
            margin-bottom: 0;
            vertical-align: middle;
        }

        .table thead th {
            color: #4f46e5 !important;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            border-bottom: 2px solid #f1f5f9 !important;
            padding: 16px;
        }

        .table tbody tr:hover {
            background-color: #f8fafc !important;
        }

        .table tbody td {
            border-bottom: 1px solid #f1f5f9 !important;
            padding: 16px;
        }

        /* Clean High-Contrast Text Labels */
        .task-title-main {
            color: #0f172a;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 2px;
        }

        .task-desc-sub {
            color: #64748b;
            font-size: 0.88rem;
        }

        .task-date-text {
            font-size: 0.92rem;
            font-weight: 600;
            color: #334155;
        }

        /* Vivid Badges Architecture */
        .badge-priority-high { background: #fee2e2; color: #ef4444; border: 1px solid #fca5a5; font-weight: 700; font-size: 0.75rem; padding: 6px 12px; border-radius: 6px; }
        .badge-priority-medium { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; font-weight: 700; font-size: 0.75rem; padding: 6px 12px; border-radius: 6px; }
        .badge-priority-low { background: #dbeafe; color: #2563eb; border: 1px solid #bfdbfe; font-weight: 700; font-size: 0.75rem; padding: 6px 12px; border-radius: 6px; }

        .badge-status-completed { background: #d1fae5; color: #059669; border: 1px solid #a7f3d0; font-weight: 700; font-size: 0.75rem; padding: 6px 12px; border-radius: 6px; }
        .badge-status-pending { background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; font-weight: 700; font-size: 0.75rem; padding: 6px 12px; border-radius: 6px; }

        /* Action Buttons Grid Layout */
        .btn-action-edit {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #4f46e5;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .btn-action-edit:hover {
            background: #e0e7ff;
            color: #4338ca;
            border-color: #c7d2fe;
        }

        .btn-action-delete {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            color: #ef4444;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .btn-action-delete:hover {
            background: #fee2e2;
            color: #dc2626;
            border-color: #fca5a5;
        }

        .btn-nav-dashboard {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: white !important;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 8px 18px;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
            transition: all 0.2s ease;
        }

        .btn-nav-dashboard:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(79, 70, 229, 0.35);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand navbar-light navbar-custom fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="/dashboard">
            <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 32px; height: 32px; background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);">
                <i class="fa-solid fa-check-double text-white" style="font-size: 0.9rem;"></i>
            </div>
            <span class="navbar-brand-text">TaskSphere</span>
        </a>
        
        <div class="ms-auto d-flex align-items-center gap-3">
            <span class="nav-user-badge shadow-sm">
                <i class="fa-regular fa-user-circle me-1"></i> <?php echo e(Auth::user()->name ?? 'shahzaib'); ?>

            </span>
            
            <a href="/dashboard" class="btn btn-nav-dashboard d-flex align-items-center gap-2 text-decoration-none">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Go to Dashboard</span>
            </a>

            <form method="POST" action="<?php echo e(route('logout')); ?>" class="m-0">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-link text-muted text-decoration-none small fw-bold p-1">
                    <i class="fa-solid fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="container workspace-container">
    
    <div class="text-center mb-5">
        <h1 class="page-main-heading mb-2">TaskSphere</h1>
        <p class="text-muted mb-0 fw-500">Stay organized and get things done smoothly.</p>
    </div>

    <div class="row g-4">
        
        <div class="col-lg-4">
            <div class="panel-card">
                <h4 class="panel-card-title mb-4">Add New Task</h4>
                
                <form action="<?php echo e(route('todos.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Task Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter task title" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Task Description</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Enter task description"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-select">
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-workspace">Add Task</button>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="panel-card">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h4 class="panel-card-title m-0">All Tasks</h4>
                    
                    <div class="dropdown">
                        <button class="btn btn-filter dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-filter text-primary"></i> Filter: All
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end bg-white border-light shadow-sm">
                            <li><a class="dropdown-item text-dark" href="?filter=all">All Tasks</a></li>
                            <li><a class="dropdown-item text-dark" href="?filter=pending">Pending</a></li>
                            <li><a class="dropdown-item text-dark" href="?filter=completed">Completed</a></li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 5%">#</th>
                                <th style="width: 45%">Title</th>
                                <th style="width: 20%">Due Date</th>
                                <th style="width: 10%">Priority</th>
                                <th style="width: 10%">Status</th>
                                <th style="width: 10%" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $todos ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $todo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="fw-bold text-secondary"><?php echo e($index + 1); ?></td>
                                    <td>
                                        <div class="task-title-main"><?php echo e($todo->title); ?></div>
                                        <div class="task-desc-sub"><?php echo e($todo->description); ?></div>
                                    </td>
                                    <td class="task-date-text">
                                        <?php echo e($todo->due_date ? \Carbon\Carbon::parse($todo->due_date)->format('d M Y') : 'No Date'); ?>

                                    </td>
                                    <td>
                                        <?php if(strtolower($todo->priority) === 'high'): ?>
                                            <span class="badge badge-priority-high">HIGH</span>
                                        <?php elseif(strtolower($todo->priority) === 'medium'): ?>
                                            <span class="badge badge-priority-medium">MEDIUM</span>
                                        <?php else: ?>
                                            <span class="badge badge-priority-low">LOW</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(strtolower($todo->status) === 'completed'): ?>
                                            <span class="badge badge-status-completed">Completed</span>
                                        <?php else: ?>
                                            <span class="badge badge-status-pending">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="<?php echo e(route('todos.edit', $todo->id)); ?>" class="btn-action-edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            
                                            <form action="<?php echo e(route('todos.destroy', $todo->id)); ?>" method="POST" class="m-0" onsubmit="return confirm('Are you sure?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn-action-delete">
                                                    <i class="fa-solid fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="fa-regular fa-folder-open d-block mb-2 style-fix" style="font-size: 2.5rem; color: #cbd5e1;"></i>
                                        No tasks discovered inside this directory view profile context.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html><?php /**PATH C:\Users\sahzabe\todo-app\resources\views/tasks/index.blade.php ENDPATH**/ ?>