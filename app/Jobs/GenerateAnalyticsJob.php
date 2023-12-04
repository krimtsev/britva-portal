<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\Analytics\TableReport;

class GenerateAnalyticsJob implements ShouldQueue, ShouldBeUnique
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

    /**
     * Количество попыток выполнения задания.
     *
     * @var int
     */
    public $tries = 2;

    public $sleep = 30;

    /**
     * Задать временной предел попыток выполнить задания.
     *
     * @return \DateTime
     */
    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    /**
     * Уникальный идентификатор задания.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->company_id;
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
        } catch (Exception $exception) {
            $this->release($this->attempts() * 5);
        }
    }
}
