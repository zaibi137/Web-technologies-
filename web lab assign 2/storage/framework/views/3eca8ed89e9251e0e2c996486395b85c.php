<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart Profile Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a, #312e81, #701a75);
            font-family: 'Segoe UI', sans-serif;
            color: white;
        }

        .glass-card {
            background: rgba(255,255,255,0.12);
            border-radius: 24px;
            backdrop-filter: blur(18px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.35);
            border: 1px solid rgba(255,255,255,0.18);
        }

        .premium-btn {
            background: linear-gradient(135deg, #8b5cf6, #ec4899);
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 14px;
            padding: 10px 18px;
        }

        .premium-btn:hover {
            opacity: 0.9;
            color: white;
        }

        .table {
            color: white;
        }

        .table thead {
            background: rgba(255,255,255,0.18);
        }

        .form-control {
            border-radius: 14px;
            padding: 12px;
        }

        .profile-img {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255,255,255,0.7);
        }

        .page-title {
            font-weight: 800;
        }

        .muted-text {
            color: #d1d5db;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container py-5">

    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Smart Profile Management System</h1>
            <p class="muted-text mb-0">Premium Laravel CRUD with image upload and email search</p>
        </div>

        <a href="<?php echo e(route('users.create')); ?>" class="premium-btn">
            <i class="bi bi-person-plus"></i> Add User
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success rounded-4">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php echo $__env->yieldContent('content'); ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(formId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This user record will be permanently deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ec4899',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>

<?php echo $__env->yieldContent('scripts'); ?>

</body>
</html><?php /**PATH D:\XAMP\htdocs\SmartProfileManagementSystem\resources\views/layout.blade.php ENDPATH**/ ?>