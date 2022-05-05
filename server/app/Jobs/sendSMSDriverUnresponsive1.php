<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Models\OrderInformations;
use Illuminate\Support\Facades\Auth;
use App\Models\BookTruckInformation;
use App\Models\City;
use App\Models\Post;
use App\Models\SuggestTruck;
use App\Models\CustomerNotification;
use App\Notifications\SuggestTruckForDriver;

class sendSMSDriverUnresponsive1 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $orderInformation;
    protected $driverIdSuggestTrucks;
    protected $customer;
    // protected $post;
    // protected $bookTruckInformationLastest;

    /**
     * Create a new job instance.
     *@param OrderInformations $orderInformation
     * @return void
     */
    public function __construct($orderInformation, $driverIdSuggestTrucks, $customer)
    {
        $this->orderInformation = $orderInformation;
        $this->driverIdSuggestTrucks = $driverIdSuggestTrucks;
        $this->customer = $customer;
        $this->bookTruckInformation = new BookTruckInformation();
        $this->post = new Post();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $bookTruckInformationLastest = BookTruckInformation::where('customer_id', $this->customer->id)->first() ?? null;
        $title = "Hệ thống đề xuất chuyến xe của khách hàng " . $this->customer->name . $this->customer->phone . " từ " . City::findOrFail($bookTruckInformationLastest->from_city_id)->name . " đến " . City::findOrFail($bookTruckInformationLastest->to_city_id)->name;
        $customerAvatar = $this->customer->avatar;
        if ($this->orderInformation->status === OrderInformations::STATUS_WATTING_DRIVER_RECIEVE ||
            $this->orderInformation->status === OrderInformations::STATUS_DRIVER_REFUSE) {
                foreach($this->driverIdSuggestTrucks as $k => $driverIdSuggestTruck) {
                    $customer = Post::findOrFail($driverIdSuggestTruck)->truck->customer;
                    $suggetTruck = SuggestTruck::create([
                        'book_truck_information_id' => $bookTruckInformationLastest->book_truck_information_id,
                        'post_id' => $driverIdSuggestTruck,
                    ]);
                    $link = "http://localhost:8080/driver/?suggest-truck-id=" . $suggetTruck->suggest_truck_id;
                    CustomerNotification::create([
                        'title' => $title,
                        'notification_avatar' => $customerAvatar,
                        'link' => $link,
                        'customer_id' => $customer->id,
                    ]);
                    //send mail
                    if (!empty($customer->email_verified_at)) {
                        $customer->notify(new SuggestTruckForDriver($link, $title));
                    }
                }

        }
    }
}
