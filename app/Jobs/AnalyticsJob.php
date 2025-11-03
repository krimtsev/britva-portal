<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\Analytics\TableReport;
use Illuminate\Support\Facades\Log;
use Throwable;

class AnalyticsJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $isSync;
    protected $start_date;
    protected $end_date;
    protected $company_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($isSync, $start_date, $end_date, $company_id)
    {
        $this->isSync = $isSync;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->company_id = $company_id;
    }

    public $tries = 5;
    public $timeout = 3600;
    public $sleep = 5;
    //public $backoff = [60, 120, 300];
    //public $uniqueFor = 300;

    /**
     * Задать временной предел попыток выполнить задания.
     *
     * @return \DateTime
     */
    public function retryUntil()
    {
        return now()->addMinutes(100);
    }

    /**
     * Уникальный идентификатор задания.
     *
     * @return string
     */
    public function uniqueId(): string
    {
        $sync = $this->isSync ? 'sync' : 'root';
        return implode('_', [
            $this->company_id,
            $this->start_date,
            $this->end_date,
            $sync
        ]);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            TableReport::get(
                $this->isSync,
                $this->start_date,
                $this->end_date,
                $this->company_id,
            );
        } catch (Throwable $e) {
            Log::error('AnalyticsJob exception', [
                'company_id' => $this->company_id,
                'error'      => $e->getMessage(),
                'attempts'   => $this->attempts(),
            ]);

            $this->release($this->attempts() * 30);
        }
    }
}
