<?php

namespace App\Jobs;

use App\Mail\TravelOrderNotificationMail;
use App\Models\TravelOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTravelOrderNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;

    public function __construct(TravelOrder $order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        Mail::to($this->order->user->email)
            ->send(new TravelOrderNotificationMail($this->order));
    }
}
