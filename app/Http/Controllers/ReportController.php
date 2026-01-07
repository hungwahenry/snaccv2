<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Models\ReportCategory;
use App\Services\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $reportService
    ) {}

    public function categories(string $type): JsonResponse
    {
        $categories = ReportCategory::active()
            ->forType($type)
            ->ordered()
            ->get(['slug', 'name', 'description']);

        return response()->json([
            'categories' => $categories,
        ]);
    }

    public function store(StoreReportRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $reportable = $this->reportService->getReportableModel(
                $validated['reportable_type'],
                $validated['reportable_slug']
            );

            if ($this->reportService->hasUserAlreadyReported(
                auth()->id(),
                get_class($reportable),
                $reportable->id
            )) {
                return response()->json([
                    'success' => false,
                    'message' => 'you have already reported this',
                ], 422);
            }

            $report = $this->reportService->createReport(
                userId: auth()->id(),
                reportableType: get_class($reportable),
                reportableId: $reportable->id,
                categorySlug: $validated['category_slug'],
                description: $validated['description'] ?? null
            );

            return response()->json([
                'success' => true,
                'message' => 'report submitted successfully',
                'report' => $report->load('category'),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'failed to submit report',
            ], 500);
        }
    }

    public function index(): View
    {
        $reports = $this->reportService->getUserReports(auth()->id());

        return view('reports.index', compact('reports'));
    }

    public function show(string $reportSlug): View
    {
        $report = $this->reportService->getReportForUser($reportSlug, auth()->id());

        return view('reports.show', compact('report'));
    }
}
