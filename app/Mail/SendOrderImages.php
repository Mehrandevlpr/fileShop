<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOrderImages extends Mailable
{
    use Queueable, SerializesModels;

    private $images ;
    private $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $images ,User $user)
    {
        $this->images = $images ;
        $this->user   = $user ;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('mail.mail')->with([
            'user'=>$this->user,
            'images' =>$this->images,
            'path'=> env('APP_URL')
        ]);

        foreach($this->images as $file){
            $email->attach(storage_path('app/local_storage/'.$file));

        }
        

        return $email;
    }
}
