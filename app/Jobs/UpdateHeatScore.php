<?php

namespace App\Jobs;

use App\Models\Snacc;
use App\Services\HeatService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateHeatScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Snacc $snacc
    ) {}

    /**
     * Execute the job.
     */
    public function handle(HeatService $heatService): void
    {
        $heatService->updateHeat($this->snacc);
    }
}
