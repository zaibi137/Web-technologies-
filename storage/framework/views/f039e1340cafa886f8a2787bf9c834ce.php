<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Premium Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Immersive Deep Dark Theme Base */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0c0f1d;
            min-height: 100vh;
            overflow-x: hidden;
            color: #f8fafc;
        }

        .login-wrapper {
            min-height: 100vh;
        }

        /* Left Panel: Luxury Midnight Purple Gradient */
        .brand-panel {
            background: linear-gradient(145deg, #0f0c20 0%, #1a103c 50%, #2b1055 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            position: relative;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Subtle glowing futuristic ambient light spots */
        .brand-panel::before {
            content: "";
            position: absolute;
            bottom: -20%;
            left: -20%;
            width: 450px;
            height: 450px;
            border-radius: 50%;
            background: rgba(72, 52, 212, 0.15);
            filter: blur(80px);
            pointer-events: none;
        }

        .illustration-box {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        /* Right Panel: Sleek Dark Glassmorphism Content Area */
        .form-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background-color: #090a10;
            position: relative;
        }

        /* Upper right corner glow on the form panel */
        .form-panel::after {
            content: "";
            position: absolute;
            top: -10%;
            right: -10%;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: rgba(104, 109, 224, 0.1);
            filter: blur(60px);
            pointer-events: none;
        }

        .login-card {
            width: 100%;
            max-width: 430px;
            background: rgba(18, 20, 32, 0.7);
            backdrop-filter: blur(16px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.07);
            padding: 40px;
            z-index: 2;
        }

        .welcome-title {
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.5px;
        }

        /* Dark Mode Inputs with Soft Glowing Highlights */
        .form-label {
            font-weight: 600;
            color: #818cf8;
            font-size: 0.85rem;
            letter-spacing: 0.3px;
        }

        .form-control {
            background-color: rgba(30, 33, 50, 0.5);
            border: 2px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 13px 16px;
            color: #ffffff;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-control::placeholder {
            color: #4b5563;
        }

        .form-control:focus {
            background-color: #1e2132;
            border-color: #6366f1;
            color: #ffffff;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
        }

        /* Interactive Checkbox styling adjustment for Dark Mode */
        .form-check-input {
            background-color: rgba(30, 33, 50, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .form-check-input:checked {
            background-color: #4834d4;
            border-color: #4834d4;
        }

        /* Neon-Vibrant Purple Button styling */
        .btn-login {
            background: linear-gradient(135deg, #4834d4 0%, #6366f1 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(72, 52, 212, 0.4);
            transition: all 0.25s ease;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #3c2bb3 0%, #4f46e5 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(72, 52, 212, 0.6);
        }

        .link-forgot {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .link-forgot:hover {
            color: #818cf8;
        }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0 login-wrapper">
        
        <div class="col-lg-6 d-none d-lg-flex brand-panel">
            <div class="mb-5">
                <span class="badge px-3 py-2 fw-bold rounded-3 shadow-sm" style="background: rgba(255,255,255,0.07); color: #a5b4fc; border: 1px solid rgba(255,255,255,0.1);">
                    ✨ Premium Edition v1.0
                </span>
            </div>
            
            <div class="illustration-box my-4">
                <div class="text-center mb-3">
                    <div class="d-inline-flex p-3 rounded-circle mb-2" style="background: rgba(99, 102, 241, 0.15); border: 1px solid rgba(99, 102, 241, 0.3);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#818cf8" class="bi bi-check2-all" viewBox="0 0 16 16">
                            <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z"/>
                            <path d="m5.354 7.146.896-.897-.707-.707-.543.543 3.646-3.647a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0z"/>
                        </svg>
                    </div>
                </div>
                <h2 class="fw-bold text-white text-center mb-2">Master Your Daily Workflow</h2>
                <p class="text-muted text-center mb-0 fw-medium" style="color: #94a3b8 !important;">
                    Organize tasks, track performance indexes effortlessly, and filter categories inside a beautifully optimized engineering workspace.
                </p>
            </div>
            
            <div class="mt-auto pt-4 text-muted small fw-medium">
                © <?php echo e(date('Y')); ?> Task Manager App. Crafted with Tailwind & Bootstrap styles.
            </div>
        </div>

        <div class="col-lg-6 form-panel">
            <div class="login-card">
                
                <div class="mb-4 text-center text-lg-start">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-3" style="width: 42px; height: 42px; background: rgba(72, 52, 212, 0.2); border: 1px solid #6366f1;">
                        <span class="fw-bold" style="color: #818cf8;">⚡</span>
                    </div>
                </div>

                <div class="mb-4">
                    <h3 class="welcome-title mb-1">Welcome Back</h3>
                    <p class="text-muted small fw-medium" style="color: #64748b !important;">Enter your workspace keys below to resume management.</p>
                </div>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger border-0 small rounded-3 p-3 mb-4" style="background-color: rgba(239, 68, 68, 0.15); color: #fca5a5;">
                        <ul class="mb-0 ps-3">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('login')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" placeholder="you@domain.com" required autofocus autocomplete="username">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Secure Password</label>
                        <input id="password" type="password" name="password" class="form-control" placeholder="••••••••••••" required autocomplete="current-password">
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input id="remember_me" type="checkbox" name="remember" class="form-check-input" style="cursor: pointer;">
                            <label for="remember_me" class="form-check-label small fw-medium" style="cursor: pointer; user-select: none; color: #94a3b8;">
                                Remember device
                            </label>
                        </div>
                        <?php if(Route::has('password.request')): ?>
                            <a class="link-forgot" href="<?php echo e(route('password.request')); ?>">
                                Forgot Password?
                            </a>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-login w-100 mb-3">
                        Launch Dashboard
                    </button>
                    
                    <div class="text-center mt-3">
                        <span class="small" style="color: #64748b;">New to this platform? </span>
                        <a href="<?php echo e(route('register')); ?>" class="small fw-bold text-decoration-none" style="color: #818cf8;">Create Workspace</a>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

</body>
</html><?php /**PATH C:\Users\sahzabe\todo-app\resources\views/auth/login.blade.php ENDPATH**/ ?>