<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Leave Management System'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --sidebar-bg: #1e1b4b;
            --sidebar-hover: #312e81;
            --sidebar-text: #c7d2fe;
            --sidebar-active: #ffffff;
        }
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #f8fafc; min-height: 100vh; }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1040;
            transition: transform 0.3s ease;
        }
        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-brand h4 {
            color: #fff;
            font-weight: 700;
            margin: 0;
            font-size: 1.1rem;
        }
        .sidebar-brand small {
            color: var(--sidebar-text);
            font-size: 0.75rem;
        }
        .sidebar-nav { padding: 1rem 0; }
        .sidebar-nav .nav-section {
            padding: 0.5rem 1.5rem;
            color: rgba(255,255,255,0.35);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 0.5rem;
        }
        .sidebar-nav .nav-link {
            color: var(--sidebar-text);
            padding: 0.6rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0;
            transition: all 0.15s;
            border-left: 3px solid transparent;
        }
        .sidebar-nav .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }
        .sidebar-nav .nav-link.active {
            background: var(--sidebar-hover);
            color: var(--sidebar-active);
            border-left-color: var(--primary-color);
        }
        .sidebar-nav .nav-link i { font-size: 1.1rem; width: 20px; text-align: center; }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        .top-bar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        .content-area { padding: 1.5rem; }

        /* Cards */
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.25rem;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }
        .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.06); transform: translateY(-1px); }
        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        .stat-card .stat-value { font-size: 1.75rem; font-weight: 700; color: #1e293b; }
        .stat-card .stat-label { font-size: 0.8rem; color: #64748b; font-weight: 500; }

        .card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: none;
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 1.25rem;
            font-weight: 600;
        }

        .table th {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            border-bottom-width: 1px;
        }
        .table td { vertical-align: middle; font-size: 0.875rem; }

        .badge { font-weight: 500; font-size: 0.75rem; padding: 0.35em 0.75em; }

        .btn-primary { background: var(--primary-color); border-color: var(--primary-color); }
        .btn-primary:hover { background: var(--primary-hover); border-color: var(--primary-hover); }

        .page-header { margin-bottom: 1.5rem; }
        .page-header h2 { font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0; }
        .page-header p { color: #64748b; margin: 0.25rem 0 0; font-size: 0.875rem; }

        /* Mobile */
        .sidebar-toggle { display: none; }
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .sidebar-toggle { display: block; }
            .sidebar-overlay {
                position: fixed; inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 1035; display: none;
            }
            .sidebar-overlay.show { display: block; }
        }

        /* Avatar */
        .avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: var(--primary-light);
            color: var(--primary-color);
            display: flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: 0.85rem;
        }

        .form-label { font-weight: 500; font-size: 0.875rem; color: #374151; }
        .form-control, .form-select { font-size: 0.875rem; border-color: #d1d5db; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(79,70,229,0.1); }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <?php if(auth()->guard()->check()): ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h4><i class="bi bi-calendar2-check me-2"></i>LMS</h4>
            <small>Leave Management System</small>
        </div>
        <div class="sidebar-nav">
            <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                <div class="nav-section">Administration</div>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
                <a href="<?php echo e(route('admin.leave-requests.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.leave-requests.*') ? 'active' : ''); ?>">
                    <i class="bi bi-clipboard-check"></i> Leave Requests
                </a>
                <a href="<?php echo e(route('admin.employees.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.employees.*') ? 'active' : ''); ?>">
                    <i class="bi bi-people"></i> Employees
                </a>
                <a href="<?php echo e(route('admin.leave-types.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.leave-types.*') ? 'active' : ''); ?>">
                    <i class="bi bi-tags"></i> Leave Types
                </a>
                <a href="<?php echo e(route('admin.departments.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.departments.*') ? 'active' : ''); ?>">
                    <i class="bi bi-building"></i> Departments
                </a>
                <div class="nav-section">Self Service</div>
            <?php else: ?>
                <div class="nav-section">Menu</div>
            <?php endif; ?>
            <a href="<?php echo e(route('employee.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('employee.dashboard') ? 'active' : ''); ?>">
                <i class="bi bi-house"></i> My Dashboard
            </a>
            <a href="<?php echo e(route('employee.leave-requests.index')); ?>" class="nav-link <?php echo e(request()->routeIs('employee.leave-requests.*') ? 'active' : ''); ?>">
                <i class="bi bi-file-earmark-text"></i> My Leaves
            </a>
            <a href="<?php echo e(route('employee.leave-requests.create')); ?>" class="nav-link <?php echo e(request()->routeIs('employee.leave-requests.create') ? 'active' : ''); ?>">
                <i class="bi bi-plus-circle"></i> Apply Leave
            </a>
        </div>
    </nav>

    <div class="main-content">
        <div class="top-bar d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-outline-secondary sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <span class="text-muted d-none d-md-block" style="font-size:0.85rem">
                    <?php echo e(now()->format('l, F j, Y')); ?>

                </span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="avatar"><?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?></div>
                        <div class="d-none d-md-block">
                            <div style="font-size:0.85rem;font-weight:600;color:#1e293b"><?php echo e(auth()->user()->name); ?></div>
                            <div style="font-size:0.7rem;color:#64748b"><?php echo e(ucfirst(auth()->user()->role)); ?></div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="content-area">
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>
    <?php else: ?>
        <?php echo $__env->yieldContent('content'); ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        // Sidebar toggle
        $('#sidebarToggle').on('click', function() {
            $('#sidebar').toggleClass('show');
            $('#sidebarOverlay').toggleClass('show');
        });
        $('#sidebarOverlay').on('click', function() {
            $('#sidebar').removeClass('show');
            $(this).removeClass('show');
        });

        // Auto-dismiss alerts
        setTimeout(function() { $('.alert-dismissible').alert('close'); }, 5000);
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /workspace/leave-management-system/resources/views/layouts/app.blade.php ENDPATH**/ ?>