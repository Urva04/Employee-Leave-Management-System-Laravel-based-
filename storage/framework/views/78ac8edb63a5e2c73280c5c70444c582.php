<?php $__env->startSection('title', 'Apply for Leave'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="d-flex align-items-center gap-2 mb-2">
        <a href="<?php echo e(route('employee.leave-requests.index')); ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
        <h2 class="mb-0">Apply for Leave</h2>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="<?php echo e(route('employee.leave-requests.store')); ?>" id="leaveForm">
                    <?php echo csrf_field(); ?>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                            <select name="leave_type_id" id="leaveType" class="form-select <?php $__errorArgs = ['leave_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="">Select Leave Type</option>
                                <?php $__currentLoopData = $leaveTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($type->id); ?>" <?php echo e(old('leave_type_id') == $type->id ? 'selected' : ''); ?>>
                                        <?php echo e($type->name); ?> (<?php echo e($type->days_per_year); ?> days/year)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['leave_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="startDate" class="form-control <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('start_date')); ?>" min="<?php echo e(date('Y-m-d')); ?>" required>
                            <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" id="endDate" class="form-control <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('end_date')); ?>" min="<?php echo e(date('Y-m-d')); ?>" required>
                            <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Reason <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="4"
                                      placeholder="Provide a reason for your leave request..." required><?php echo e(old('reason')); ?></textarea>
                            <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Submit Request</button>
                        <a href="<?php echo e(route('employee.leave-requests.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Balance Info -->
    <div class="col-lg-4">
        <div class="card" id="balanceCard" style="display:none">
            <div class="card-header"><i class="bi bi-wallet2 me-2"></i>Leave Balance</div>
            <div class="card-body">
                <div class="row g-2 text-center">
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <div class="text-muted small">Total</div>
                            <div class="fw-bold text-primary" id="balTotal">0</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <div class="text-muted small">Used</div>
                            <div class="fw-bold text-danger" id="balUsed">0</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <div class="text-muted small">Pending</div>
                            <div class="fw-bold text-warning" id="balPending">0</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <div class="text-muted small">Available</div>
                            <div class="fw-bold text-success" id="balAvailable">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3" id="daysCard" style="display:none">
            <div class="card-body text-center py-3">
                <div class="text-muted small">Business Days Selected</div>
                <div class="fw-bold text-primary" style="font-size:2rem" id="selectedDays">0</div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="fw-bold mb-2" style="font-size:0.85rem"><i class="bi bi-info-circle me-1"></i>Guidelines</h6>
                <ul class="list-unstyled mb-0" style="font-size:0.8rem;color:#64748b">
                    <li class="mb-1">&bull; Leave must be applied at least 1 day in advance</li>
                    <li class="mb-1">&bull; Weekends are excluded from business days</li>
                    <li class="mb-1">&bull; Ensure sufficient balance before applying</li>
                    <li class="mb-1">&bull; You can cancel pending requests anytime</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Fetch balance on leave type change
    $('#leaveType').on('change', function() {
        var leaveTypeId = $(this).val();
        if (leaveTypeId) {
            $.get('<?php echo e(route("employee.leave-balance")); ?>', { leave_type_id: leaveTypeId }, function(data) {
                $('#balTotal').text(parseFloat(data.total).toFixed(1));
                $('#balUsed').text(parseFloat(data.used).toFixed(1));
                $('#balPending').text(parseFloat(data.pending).toFixed(1));
                $('#balAvailable').text(parseFloat(data.available).toFixed(1));
                $('#balanceCard').slideDown();
            });
        } else {
            $('#balanceCard').slideUp();
        }
    });

    // Calculate business days
    function calculateBusinessDays() {
        var start = $('#startDate').val();
        var end = $('#endDate').val();
        if (start && end) {
            var startDate = new Date(start);
            var endDate = new Date(end);
            var days = 0;
            var current = new Date(startDate);
            while (current <= endDate) {
                var dayOfWeek = current.getDay();
                if (dayOfWeek !== 0 && dayOfWeek !== 6) days++;
                current.setDate(current.getDate() + 1);
            }
            $('#selectedDays').text(days);
            $('#daysCard').slideDown();
        }
    }

    $('#startDate, #endDate').on('change', calculateBusinessDays);

    // Update end date min when start date changes
    $('#startDate').on('change', function() {
        $('#endDate').attr('min', $(this).val());
    });

    // Trigger balance load if old value exists
    if ($('#leaveType').val()) {
        $('#leaveType').trigger('change');
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\bootstrap workshop\leave-management-system\leave-management-system\resources\views/employee/leave-requests/create.blade.php ENDPATH**/ ?>