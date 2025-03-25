<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\ActivityLogService;

class CleanupActivityLogsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Le nombre de jours de logs à conserver.
     *
     * @var int
     */
    protected $days;

    /**
     * Création d'une nouvelle instance de job.
     *
     * @param int $days
     * @return void
     */
    public function __construct(int $days = 90)
    {
        $this->days = $days;
    }

    /**
     * Exécuter le job.
     *
     * @param ActivityLogService $activityLogService
     * @return void
     */
    public function handle(ActivityLogService $activityLogService)
    {
        $activityLogService->cleanup($this->days);
    }
}