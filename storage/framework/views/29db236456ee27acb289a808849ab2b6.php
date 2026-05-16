<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <style>
        /* Main Workspace Wrapping Layout Adjustment */
        .workspace-container {
            padding-top: 60px;
            padding-bottom: 60px;
        }

        .page-header-title {
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #ffffff;
        }

        /* Welcome Banner Card */
        .welcome-banner {
            background: linear-gradient(135deg, #1e1b4b 0%, #111827 100%);
            border: 1px solid rgba(99, 102, 241, 0.15);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        .welcome-banner::after {
            content: "";
            position: absolute;
            right: -5%;
            top: -20%;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: rgba(99, 102, 241, 0.08);
            filter: blur(50px);
        }

        .btn-workspace {
            background: linear-gradient(135deg, #4834d4 0%, #6366f1 100%);
            color: white !important;
            border: none;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(72, 52, 212, 0.4);
            transition: all 0.25s ease;
        }

        .btn-workspace:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(72, 52, 212, 0.6);
        }

        /* Executive Analytics KPI Metric Cards */
        .metric-card {
            background: rgba(18, 20, 32, 0.65);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 18px;
            padding: 25px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .metric-card:hover {
            transform: translateY(-4px);
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: 0 15px 30px rgba(72, 52, 212, 0.1);
        }

        .metric-label {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 700;
            color: #64748b;
        }

        .metric-value {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1;
            margin-top: 10px;
            color: #ffffff;
        }

        /* Metric Icon Background Variations */
        .icon-box {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }
        .icon-total { background: rgba(99, 102, 241, 0.15); color: #818cf8; border: 1px solid rgba(99, 102, 241, 0.2); }
        .icon-complete { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.2); }
        .icon-pending { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.2); }

        /* Chart Section Containers */
        .chart-panel-card {
            background: rgba(18, 20, 32, 0.65);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        .panel-heading-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #ffffff;
        }
    </style>

    <div class="container workspace-container">
        
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="page-header-title mb-1">Analytics Dashboard</h2>
                <p class="text-muted small mb-0">Real-time performance distribution status metrics.</p>
            </div>
            <span class="badge bg-dark border border-secondary text-secondary px-3 py-2 rounded-3">
                <i class="fa-regular fa-calendar me-1"></i> Live Session Data
            </span>
        </div>

        <div class="welcome-banner d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4 mb-4">
            <div>
                <h4 class="fw-bold text-white mb-2">Welcome Back, <?php echo e(Auth::user()->name ?? 'shahzaib'); ?>!</h4>
                <p class="text-muted mb-0 style-fix" style="color: #94a3b8 !important; max-width: 580px;">
                    Your production workspace is active. Track your metrics indexes instantly, analyze output variables, or update your checklist categories here.
                </p>
            </div>
            <div>
                <div>
                    <a href="/todos" class="btn-workspace inline-flex items-center gap-2 text-center text-decoration-none">
                        <span>View My Tasks</span>
                        <i class="fa-solid fa-arrow-right-long text-xs"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="metric-card d-flex align-items-center justify-content-between">
                    <div>
                        <div class="metric-label">Total Stored Tasks</div>
                        <div class="metric-value"><?php echo e($totalTasks ?? 6); ?></div>
                    </div>
                    <div class="icon-box icon-total shadow-sm">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="metric-card d-flex align-items-center justify-content-between">
                    <div>
                        <div class="metric-label">Completed Status</div>
                        <div class="metric-value" style="color: #34d399;"><?php echo e($completedTasks ?? 3); ?></div>
                    </div>
                    <div class="icon-box icon-complete shadow-sm">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="metric-card d-flex align-items-center justify-content-between">
                    <div>
                        <div class="metric-label">Pending Status</div>
                        <div class="metric-value" style="color: #f87171;"><?php echo e($pendingTasks ?? 3); ?></div>
                    </div>
                    <div class="icon-box icon-pending shadow-sm">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="chart-panel-card text-center text-md-start">
                    <div class="mb-4">
                        <h5 class="panel-heading-title mb-1">Visual Summary Statistics</h5>
                        <p class="text-muted small mb-0">Graphical analysis of your current application state distribution.</p>
                    </div>
                    
                    <div class="d-flex justify-content-center align-items-center my-3" style="position: relative; height: 280px;">
                        <canvas id="productivityDonutChart" style="max-width: 280px; max-height: 280px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const completedCount = parseInt("<?php echo e($completedTasks ?? 3); ?>");
            const pendingCount = parseInt("<?php echo e($pendingTasks ?? 3); ?>");

            const canvas = document.getElementById('productivityDonutChart');
            if (canvas) {
                const ctx = canvas.getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Completed Tasks', 'Pending Tasks'],
                        datasets: [{
                            data: [completedCount, pendingCount],
                            backgroundColor: [
                                '#10b981', 
                                '#ef4444'  
                            ],
                            borderWidth: 4,
                            borderColor: '#121420', 
                            hoverBorderColor: '#121420',
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: '#94a3b8', 
                                    font: {
                                        family: "'Inter', sans-serif",
                                        size: 12,
                                        weight: '500'
                                    },
                                    padding: 20,
                                    usePointStyle: true,
                                    pointStyle: 'circle'
                                }
                            },
                            tooltip: {
                                backgroundColor: '#1e2132',
                                titleColor: '#ffffff',
                                bodyColor: '#f8fafc',
                                borderColor: 'rgba(255,255,255,0.08)',
                                borderWidth: 1,
                                padding: 12,
                                boxPadding: 6,
                                cornerRadius: 8,
                                bodyFont: {
                                    family: "'Inter', sans-serif"
                                }
                            }
                        },
                        cutout: '76%' 
                    }
                });
            }
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\sahzabe\todo-app\resources\views/dashboard.blade.php ENDPATH**/ ?>