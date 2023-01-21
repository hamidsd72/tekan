<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Invoice extends Notification {

    use Queueable;

    public $id;
    public $date;
    
    public function __construct($id, $date) {
        $this->id   = $id; 
        $this->date = $date; 
    }

    public function via($notifiable) {
        // return ['mail'];
        return ['database'];
    }

    public function toMail($notifiable) {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable) {  
        
        $item = '{"item_id": "'.$this->id.'","date": "'.$this->date.'"}';
        return $item;
        // $item = [
        //     'list' => $this->date
        // ];
    }
}
