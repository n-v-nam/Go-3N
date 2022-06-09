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
use App\Notifications\SuggestTruckForDriver;
use App\Models\CustomerNotification;

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
        if ($this->orderInformation->status == OrderInformations::STATUS_DRIVER_DELIVERED ||
            $this->orderInformation->status == OrderInformations::STATUS_COMPLETED) {
            $customer = $this->orderInformation->bookTruckInformation->customer;
            $oldBalance = $this->driver->balance;
            DB::beginTransaction();
            try {
                $this->driver->update([
                    "balance" => $oldBalance + 200000,
                ]);
                //send notification
                $title = "Bạn đã nhận được 200000đ tiền đặt cọc từ " . $customer->name . " sđt " . $customer->phone;
                $link = "http://localhost:8080/page/profile";
                CustomerNotification::create([
                    'title' => $title,
                    'notification_avatar' => $customer->avatar,
                    'link' => $link,
                    'customer_id' => $this->driver->id,
                ]);
                //send mail to driver if verified mail
                if (!empty($this->driver->email)) {
                    $this->driver->notify(new SuggestTruckForDriver($link, $title));
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return [false, $e->getMessage()];
            }
        }
    }
}
