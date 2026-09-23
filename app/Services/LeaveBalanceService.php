<?php

namespace App\Services;

use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;

class LeaveBalanceService
{
    /**
     * Initialize leave balances for a user for the current year.
     */
    public function initializeBalances(User $user, ?int $year = null): void
    {
        $year = $year ?? now()->year;
        $leaveTypes = LeaveType::where('is_active', true)->get();

        foreach ($leaveTypes as $leaveType) {
            LeaveBalance::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'leave_type_id' => $leaveType->id,
                    'year' => $year,
                ],
                [
                    'total_days' => $leaveType->days_per_year,
                    'used_days' => 0,
                    'pending_days' => 0,
                ]
            );
        }
    }

    /**
     * Calculate total business days between two dates (excluding weekends).
     */
    public function calculateBusinessDays(\DateTime $startDate, \DateTime $endDate): float
    {
        $days = 0;
        $current = clone $startDate;

        while ($current <= $endDate) {
            $dayOfWeek = (int) $current->format('N');
            if ($dayOfWeek <= 5) { // Monday to Friday
                $days++;
            }
            $current->modify('+1 day');
        }

        return $days;
    }

    /**
     * Check if user has sufficient balance for a leave request.
     */
    public function hasSufficientBalance(User $user, int $leaveTypeId, float $days, ?int $year = null): bool
    {
        $year = $year ?? now()->year;
        $balance = LeaveBalance::where('user_id', $user->id)
            ->where('leave_type_id', $leaveTypeId)
            ->where('year', $year)
            ->first();

        if (!$balance) {
            return false;
        }

        return $balance->available_days >= $days;
    }

    /**
     * Update balance when a leave request is submitted (add to pending).
     */
    public function addPendingDays(LeaveRequest $leaveRequest): void
    {
        $balance = $this->getOrCreateBalance(
            $leaveRequest->user_id,
            $leaveRequest->leave_type_id,
            $leaveRequest->start_date->year
        );

        $balance->increment('pending_days', $leaveRequest->total_days);
    }

    /**
     * Update balance when a leave request is approved (move from pending to used).
     */
    public function approveDays(LeaveRequest $leaveRequest): void
    {
        $balance = $this->getOrCreateBalance(
            $leaveRequest->user_id,
            $leaveRequest->leave_type_id,
            $leaveRequest->start_date->year
        );

        $balance->decrement('pending_days', $leaveRequest->total_days);
        $balance->increment('used_days', $leaveRequest->total_days);
    }

    /**
     * Update balance when a leave request is rejected or cancelled (remove pending).
     */
    public function removePendingDays(LeaveRequest $leaveRequest): void
    {
        $balance = $this->getOrCreateBalance(
            $leaveRequest->user_id,
            $leaveRequest->leave_type_id,
            $leaveRequest->start_date->year
        );

        $balance->decrement('pending_days', $leaveRequest->total_days);
    }

    private function getOrCreateBalance(int $userId, int $leaveTypeId, int $year): LeaveBalance
    {
        $leaveType = LeaveType::findOrFail($leaveTypeId);

        return LeaveBalance::firstOrCreate(
            [
                'user_id' => $userId,
                'leave_type_id' => $leaveTypeId,
                'year' => $year,
            ],
            [
                'total_days' => $leaveType->days_per_year,
                'used_days' => 0,
                'pending_days' => 0,
            ]
        );
    }
}
