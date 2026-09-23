<?php $__env->startSection('title', 'My Leave Requests'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h2>My Leave Requests</h2>
        <p>View and manage your leave applications</p>
    </div>
    <a href="<?php echo e(route('employee.leave-requests.create')); ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i> Apply Leave</a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('employee.leave-requests.index')); ?>" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <?php $__currentLoopData = ['pending','approved','rejected','cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(request('status') == $s ? 'selected' : ''); ?>><?php echo e(ucfirst($s)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Year</label>
                <select name="year" class="form-select">
                    <option value="">All Years</option>
                    <?php for($y = now()->year; $y >= now()->year - 2; $y--): ?>
                        <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary me-2"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="<?php echo e(route('employee.leave-requests.index')); ?>" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Leave Type</th>
                        <th>Period</th>
                        <th>Days</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Applied On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $leaveRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span class="badge bg-light text-dark"><?php echo e($req->leaveType->name); ?></span></td>
                        <td style="font-size:0.8rem"><?php echo e($req->start_date->format('M d')); ?> - <?php echo e($req->end_date->format('M d, Y')); ?></td>
                        <td class="fw-semibold"><?php echo e($req->total_days); ?></td>
                        <td class="text-muted" style="font-size:0.8rem"><?php echo e(Str::limit($req->reason, 40)); ?></td>
                        <td><span class="badge bg-<?php echo e($req->status_badge); ?>"><?php echo e(ucfirst($req->status)); ?></span></td>
                        <td style="font-size:0.8rem"><?php echo e($req->created_at->format('M d, Y')); ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo e(route('employee.leave-requests.show', $req)); ?>" class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                                <?php if($req->status === 'pending'): ?>
                                <form method="POST" action="<?php echo e(route('employee.leave-requests.cancel', $req)); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Cancel this request?')" title="Cancel">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted">No leave requests found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3"><?php echo e($leaveRequests->withQueryString()->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\bootstrap workshop\leave-management-system\leave-management-system\resources\views/employee/leave-requests/index.blade.php ENDPATH**/ ?>