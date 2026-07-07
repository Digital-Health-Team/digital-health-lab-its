<?php

namespace App\Policies;

use App\Enums\ReportStatus;
use App\Models\IssueReport;
use App\Models\User;

class IssueReportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /** Warehouse and super admin see everything; other admins only their own. */
    public function view(User $user, IssueReport $report): bool
    {
        return $this->resolve($user, $report) || $report->reporter_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /** Reporters may edit their own report only while it is still open. */
    public function update(User $user, IssueReport $report): bool
    {
        return $report->reporter_id === $user->id
            && $report->status === ReportStatus::Open;
    }

    public function delete(User $user, IssueReport $report): bool
    {
        return $this->update($user, $report);
    }

    /** Only the warehouse admins (and super admin) process reports. */
    public function resolve(User $user, IssueReport $report): bool
    {
        return in_array($user->activeRoleName(), ['super_admin', 'admin_gudang']);
    }
}
