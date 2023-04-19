<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Model\Meet;
use App\Model\Notification;
use Carbon\Carbon;

class MeetUpdater implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct() {}

    public function handle() {
        $open_meets = Meet::where('total','>',0)->where('reply','>',0)->where('addDays','>',0)->where('ready_date','<',Carbon::today())->get();

        foreach ($open_meets as $item) {
            $item->total    -= 1;
            $start_date     = Carbon::parse($item->date);
            $add            = ($item->reply - $item->total) * $item->addDays;
            $item->ready_date = $start_date->addDay($add);
            $item->update();

            Notification::setItem(
                "App\Notifications\Meet",
                "App\User",
                $item->id,
                ('{"date": "'.$item->slug.'"}')
            );

        }


    }
}