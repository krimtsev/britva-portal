<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ClientsVisitsJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $subDay;
    protected $company_id;
    protected $type;
    protected $tg_chat_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($subDay, $type, $company_id, $tg_chat_id)
    {
        $this->subDay = $subDay;
        $this->type = $type;
        $this->company_id = $company_id;
        $this->tg_chat_id = $tg_chat_id;
    }

    /**
     * Количество попыток выполнения задания.
     *
     * @var int
     */
    public $tries = 5;

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
        return sprintf("%s_%s", $this->type, $this->company_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
           /* TableReport::get(
                $this->isSync,
                $this->start_date,
                $this->end_date,
                $this->company_id,
            );*/
        } catch (Throwable $exception) {
            $this->release($this->attempts() * 5);
        }
    }
}
