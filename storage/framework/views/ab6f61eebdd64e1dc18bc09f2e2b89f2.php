<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Premium Task Manager</title>
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
            max-width: 440px;
            background: rgba(18, 20, 32, 0.7);
            backdrop-filter: blur(16px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.07);
            padding: 35px;
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
            padding: 11px 15px;
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#818cf8" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                            <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm7.5-3a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
                        </svg>
                    </div>
                </div>
                <h2 class="fw-bold text-white text-center mb-2">Create Your Task Workspace</h2>
                <p class="text-muted text-center mb-0 fw-medium" style="color: #94a3b8 !important;">
                    Join now to initialize your customizable engineering board, set up your metrics tracking, and start monitoring productivity indexes flawlessly.
                </p>
            </div>
            
            <div class="mt-auto pt-4 text-muted small fw-medium">
                © <?php echo e(date('Y')); ?> Task Manager App. Crafted with Tailwind & Bootstrap styles.
            </div>
        </div>

        <div class="col-lg-6 form-panel">
            <div class="login-card">
                
                <div class="mb-3 text-center text-lg-start">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-3" style="width: 42px; height: 42px; background: rgba(72, 52, 212, 0.2); border: 1px solid #6366f1;">
                        <span class="fw-bold" style="color: #818cf8;">🚀</span>
                    </div>
                </div>

                <div class="mb-4">
                    <h3 class="welcome-title mb-1">Get Started</h3>
                    <p class="text-muted small fw-medium" style="color: #64748b !important;">Create your unique identity keys to launch your dashboard setup.</p>
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

                <form method="POST" action="<?php echo e(route('register')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input id="name" type="text" name="name" class="form-control" value="<?php echo e(old('name')); ?>" placeholder="John Doe" required autofocus autocomplete="name">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" placeholder="you@domain.com" required autocomplete="username">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Secure Password</label>
                        <input id="password" type="password" name="password" class="form-control" placeholder="••••••••••••" required autocomplete="new-password">
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" placeholder="••••••••••••" required autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn btn-login w-100 mb-3">
                        Register Account
                    </button>
                    
                    <div class="text-center mt-2">
                        <span class="small" style="color: #64748b;">Already have a workspace? </span>
                        <a href="<?php echo e(route('login')); ?>" class="small fw-bold text-decoration-none" style="color: #818cf8;">Sign In Here</a>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

</body>
</html><?php /**PATH C:\Users\sahzabe\todo-app\resources\views/auth/register.blade.php ENDPATH**/ ?>