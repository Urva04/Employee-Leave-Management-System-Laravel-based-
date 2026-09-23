<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h2>Dashboard</h2>
        <p>Welcome back, <?php echo e(auth()->user()->name); ?>!</p>
    </div>
    <a href="<?php echo e(route('admin.leave-requests.index', ['status' => 'pending'])); ?>" class="btn btn-primary btn-sm">
        <i class="bi bi-clipboard-check me-1"></i> Review Pending
    </a>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Total Employees</div>
                    <div class="stat-value"><?php echo e($totalEmployees); ?></div>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-people-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Pending Requests</div>
                    <div class="stat-value text-warning"><?php echo e($pendingRequests); ?></div>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-clock-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">On Leave Today</div>
                    <div class="stat-value text-success"><?php echo e($approvedToday); ?></div>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-calendar-check-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Departments</div>
                    <div class="stat-value text-info"><?php echo e($totalDepartments); ?></div>
                </div>
                <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="bi bi-building"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Recent Requests -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-clock-history me-2"></i>Recent Leave Requests</span>
                <a href="<?php echo e(route('admin.leave-requests.index')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recentRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar" style="width:32px;height:32px;font-size:0.7rem"><?php echo e(strtoupper(substr($request->user->name, 0, 1))); ?></div>
                                        <div>
                                            <div class="fw-semibold" style="font-size:0.85rem"><?php echo e($request->user->name); ?></div>
                                            <div class="text-muted" style="font-size:0.75rem"><?php echo e($request->user->employee_id); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark"><?php echo e($request->leaveType->name); ?></span></td>
                                <td>
                                    <div style="font-size:0.8rem"><?php echo e($request->start_date->format('M d')); ?> - <?php echo e($request->end_date->format('M d, Y')); ?></div>
                                    <div class="text-muted" style="font-size:0.75rem"><?php echo e($request->total_days); ?> day(s)</div>
                                </td>
                                <td><span class="badge bg-<?php echo e($request->status_badge); ?>"><?php echo e(ucfirst($request->status)); ?></span></td>
                                <td>
                                    <a href="<?php echo e(route('admin.leave-requests.show', $request)); ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="5" class="text-center py-4 text-muted">No leave requests yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Stats -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-building me-2"></i>Department Overview
            </div>
            <div class="card-body">
                <?php $__currentLoopData = $departmentStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div class="fw-semibold" style="font-size:0.875rem"><?php echo e($dept->name); ?></div>
                        <div class="text-muted" style="font-size:0.75rem"><?php echo e($dept->active_users_count); ?> employees</div>
                    </div>
                    <div class="badge bg-primary bg-opacity-10 text-primary"><?php echo e($dept->active_users_count); ?></div>
                </div>
                <?php if(!$loop->last): ?><hr class="my-0"><?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\bootstrap workshop\leave-management-system\leave-management-system\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>