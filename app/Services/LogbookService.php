<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Logbook;
use App\Models\InternshipPeriod;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class LogbookService
{
    /**
     * Get logbooks for a student with filters.
     */
    public function getLogbooks(User $user, array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $period = $user->studentProfile?->internshipPeriods()
            ->where('status', 'active')
            ->first();

        if (!$period) {
            // Return empty paginator if no active period
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage);
        }

        $query = Logbook::where('internship_period_id', $period->id);

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('date', [$filters['start_date'], $filters['end_date']]);
        }

        if (!empty($filters['search'])) {
            $query->where('activity', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('date', 'desc')->paginate($perPage);
    }

    /**
     * Get logbook stats for the student's active period.
     */
    public function getStats(User $user, array $filters = []): array
    {
        $stats = [
            'pending' => 0,
            'validated' => 0,
            'rejected' => 0,
        ];

        $period = $user->studentProfile?->internshipPeriods()
            ->where('status', 'active')
            ->first();

        if (!$period) {
            return $stats;
        }

        $query = Logbook::where('internship_period_id', $period->id);

        // Apply same filters as list if needed, or maybe just date range for stats?
        // Usually stats reflect the current view context
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('date', [$filters['start_date'], $filters['end_date']]);
        }
        
        if (!empty($filters['search'])) {
            $query->where('activity', 'like', '%' . $filters['search'] . '%');
        }

        $statsRaw = $query->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $stats['pending'] = $statsRaw['pending'] ?? 0;
        $stats['validated'] = $statsRaw['validated'] ?? 0;
        $stats['rejected'] = $statsRaw['rejected'] ?? 0;

        return $stats;
    }

    /**
     * Create a new logbook entry.
     */
    public function create(User $user, array $data, ?UploadedFile $proof = null): Logbook
    {
        $period = $user->studentProfile?->internshipPeriods()
            ->where('status', 'active')
            ->first();

        if (!$period) {
            throw new \Exception("No active internship period found.");
        }

        $path = null;
        if ($proof) {
            $path = $proof->store('logbooks', 'public');
        }

        return Logbook::create([
            'internship_period_id' => $period->id,
            'date' => $data['date'],
            'activity' => $data['activity'],
            'proof_file_path' => $path,
            'status' => 'pending',
        ]);
    }

    /**
     * Update an existing logbook entry.
     */
    public function update(Logbook $logbook, array $data, ?UploadedFile $proof = null): Logbook
    {
        if ($logbook->status === 'validated') {
             // Optional: Prevent editing validated logbooks? 
             // For now, let's allow it but maybe reset status to pending?
             // Guidelines don't specify, but usually editing validated data might require re-validation.
        }

        $path = $logbook->proof_file_path;
        if ($proof) {
            // Delete old file if exists
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $path = $proof->store('logbooks', 'public');
        }

        $logbook->update([
            'date' => $data['date'],
            'activity' => $data['activity'],
            'proof_file_path' => $path,
            // If updated, should it go back to pending? Let's assume yes for safety.
            'status' => 'pending', 
        ]);

        return $logbook;
    }

    /**
     * Delete a logbook entry.
     */
    public function delete(Logbook $logbook): void
    {
        if ($logbook->proof_file_path && Storage::disk('public')->exists($logbook->proof_file_path)) {
            Storage::disk('public')->delete($logbook->proof_file_path);
        }

        $logbook->delete();
    }
}
