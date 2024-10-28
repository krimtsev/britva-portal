<?php

namespace App\Jobs;

use App\Http\Controllers\Staff\StaffController;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class StaffJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $company_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($company_id)
    {
        $this->company_id = $company_id;
    }

    /**
     * Количество попыток выполнения задания.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions = 1;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

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
            StaffController::update($this->company_id);
        } catch (Throwable $e) {
            report($e);
        }
    }
}
