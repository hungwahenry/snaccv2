<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Report;
use App\Models\ReportCategory;
use App\Models\Snacc;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getReportableModel(string $type, string $slug)
    {
        return match($type) {
            'snacc' => Snacc::where('slug', $slug)->firstOrFail(),
            'comment' => Comment::where('slug', $slug)->firstOrFail(),
            'user' => User::whereHas('profile', function($q) use ($slug) {
                $q->where('username', $slug);
            })->firstOrFail(),
            default => throw new \InvalidArgumentException("Invalid reportable type: {$type}"),
        };
    }

    public function hasUserAlreadyReported(int $userId, string $reportableType, int $reportableId): bool
    {
        return Report::where('user_id', $userId)
            ->where('reportable_type', $reportableType)
            ->where('reportable_id', $reportableId)
            ->exists();
    }

    public function createReport(
        int $userId,
        string $reportableType,
        int $reportableId,
        string $categorySlug,
        ?string $description = null
    ): Report {
        $category = ReportCategory::where('slug', $categorySlug)->firstOrFail();

        return Report::create([
            'user_id' => $userId,
            'report_category_id' => $category->id,
            'reportable_type' => $reportableType,
            'reportable_id' => $reportableId,
            'description' => $description,
            'status' => 'pending',
        ]);
    }

    public function getUserReports(int $userId, int $perPage = 20)
    {
        return Report::with(['reportable', 'category'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    public function getReportForUser(string $reportSlug, int $userId): Report
    {
        $report = Report::where('slug', $reportSlug)->firstOrFail();

        if ($report->user_id !== $userId) {
            abort(403, 'Unauthorized');
        }

        return $report->load(['reportable', 'category', 'reviewer']);
    }
}
