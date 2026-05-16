<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>TaskSphere Workspace</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php endif; ?>

    <style>
        /* Immersive Premium Deep Dark Theme Base */
        body {
            background-color: #0c0f1d !important;
            color: #f8fafc !important;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }
        
        /* Fixed Glassmorphism Top Navigation */
        .custom-navbar {
            background: rgba(18, 20, 32, 0.85) !important;
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            padding: 14px 0;
            z-index: 1050;
        }

        .navbar-brand-gradient {
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #a5b4fc 0%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Profile Dropdown Styling Adjustments */
        .profile-trigger-btn {
            background: rgba(99, 102, 241, 0.12) !important;
            border: 1px solid rgba(99, 102, 241, 0.25) !important;
            color: #a5b4fc !important;
            padding: 7px 18px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .profile-trigger-btn:hover, .profile-trigger-btn:focus, .profile-trigger-btn:active {
            background: rgba(99, 102, 241, 0.25) !important;
            color: #ffffff !important;
        }

        /* Custom Dark Theme Dropdown Menu */
        .custom-dark-menu {
            background-color: #121420 !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
            border-radius: 12px !important;
            margin-top: 10px !important;
            padding: 8px !important;
        }

        .custom-dark-menu .dropdown-item {
            color: #94a3b8 !important;
            font-size: 0.88rem;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .custom-dark-menu .dropdown-item:hover {
            background-color: rgba(99, 102, 241, 0.15) !important;
            color: #ffffff !important;
        }

        .logout-btn-link {
            width: 100%;
            text-align: left;
            border: none;
            background: none;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand navbar-dark custom-navbar fixed-top">
    <div class="container">
        
        <a class="navbar-brand d-flex align-items-center gap-2 m-0 text-decoration-none" href="/dashboard">
            <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 32px; height: 32px; background: linear-gradient(135deg, #4834d4 0%, #6366f1 100%);">
                <i class="fa-solid fa-check-double text-white" style="font-size: 0.9rem;"></i>
            </div>
            <span class="navbar-brand-gradient">TaskSphere</span>
        </a>
        
        <div class="ms-auto">
            <div class="dropdown">
                <button class="btn profile-trigger-btn dropdown-toggle d-flex align-items-center gap-2" 
                        type="button" 
                        id="userMenuDropdown" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                    
                    <i class="fa-regular fa-user-circle" style="font-size: 1.05rem;"></i>
                    <span><?php echo e(Auth::user()->name ?? 'shahzaib'); ?></span>
                </button>
                
                <ul class="dropdown-menu dropdown-menu-end custom-dark-menu" aria-labelledby="userMenuDropdown">
                    <li>
                        <a class="dropdown-item" href="/dashboard">
                            <i class="fa-solid fa-chart-pie me-2"></i>Dashboard Overview
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="/todos">
                            <i class="fa-solid fa-list-check me-2"></i>My Task Board
                        </a>
                    </li>
                    <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.06);"></li>
                    <li>
                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="m-0">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="dropdown-item logout-btn-link text-danger">
                                <i class="fa-solid fa-sign-out-alt me-2"></i>Log Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div style="padding-top: 80px;">
    <?php echo e($slot); ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

</body>
</html><?php /**PATH C:\Users\sahzabe\todo-app\resources\views/layouts/app.blade.php ENDPATH**/ ?>