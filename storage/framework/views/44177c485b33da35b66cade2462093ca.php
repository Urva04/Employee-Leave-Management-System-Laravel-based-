<?php $__env->startSection('title', 'Departments'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h2>Departments</h2>
        <p>Manage company departments</p>
    </div>
    <a href="<?php echo e(route('admin.departments.create')); ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i> Add Department</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Employees</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-semibold"><?php echo e($dept->name); ?></td>
                        <td><code><?php echo e($dept->code); ?></code></td>
                        <td class="text-muted" style="font-size:0.85rem"><?php echo e(Str::limit($dept->description, 50) ?? '—'); ?></td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary"><?php echo e($dept->active_users_count); ?></span></td>
                        <td><span class="badge bg-<?php echo e($dept->is_active ? 'success' : 'danger'); ?>"><?php echo e($dept->is_active ? 'Active' : 'Inactive'); ?></span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo e(route('admin.departments.edit', $dept)); ?>" class="btn btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="<?php echo e(route('admin.departments.toggle-status', $dept)); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-outline-<?php echo e($dept->is_active ? 'warning' : 'success'); ?>" onclick="return confirm('Are you sure?')">
                                        <i class="bi bi-<?php echo e($dept->is_active ? 'pause' : 'play'); ?>"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center py-4 text-muted">No departments found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\bootstrap workshop\leave-management-system\leave-management-system\resources\views/admin/departments/index.blade.php ENDPATH**/ ?>