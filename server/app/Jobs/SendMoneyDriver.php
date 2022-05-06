<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\OrderInformations;
use Illuminate\Support\Facades\DB;

class SendMoneyDriver implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $orderInformation;
    protected $driver;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($orderInformation, $driver)
    {
        $this->orderInformation = $orderInformation;
        $this->driver = $driver;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->orderInformation->status == OrderInformations::STATUS_CUSTOMER_PAID) {
            $oldBalance = $this->driver->balance;
            DB::beginTransaction();
            try {
                $this->orderInformation->update([
                    "status" => OrderInformations::STATUS_DRIVER_DELIVERED,
                ]);
                $this->driver->update([
                    "balance" => $oldBalance + 200000,
                ]);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return [false, $e->getMessage()];
            }
        }
    }
}
