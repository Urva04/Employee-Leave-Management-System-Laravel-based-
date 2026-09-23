@extends('layouts.app')
@section('title', 'Apply for Leave')

@section('content')
<div class="page-header">
    <div class="d-flex align-items-center gap-2 mb-2">
        <a href="{{ route('employee.leave-requests.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
        <h2 class="mb-0">Apply for Leave</h2>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('employee.leave-requests.store') }}" id="leaveForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                            <select name="leave_type_id" id="leaveType" class="form-select @error('leave_type_id') is-invalid @enderror" required>
                                <option value="">Select Leave Type</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} ({{ $type->days_per_year }} days/year)
                                    </option>
                                @endforeach
                            </select>
                            @error('leave_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="startDate" class="form-control @error('start_date') is-invalid @enderror"
                                   value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}" required>
                            @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" id="endDate" class="form-control @error('end_date') is-invalid @enderror"
                                   value="{{ old('end_date') }}" min="{{ date('Y-m-d') }}" required>
                            @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Reason <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="4"
                                      placeholder="Provide a reason for your leave request..." required>{{ old('reason') }}</textarea>
                            @error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Submit Request</button>
                        <a href="{{ route('employee.leave-requests.index') }}" class="btn btn-outline-secondary">Cancel</a>
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
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Fetch balance on leave type change
    $('#leaveType').on('change', function() {
        var leaveTypeId = $(this).val();
        if (leaveTypeId) {
            $.get('{{ route("employee.leave-balance") }}', { leave_type_id: leaveTypeId }, function(data) {
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
@endpush
