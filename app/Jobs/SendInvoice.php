<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use App\Mail\gmailMail;

class SendInvoice implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected $email;
    protected $subject;
    protected $data;
    public function __construct($email,$subject,$data)
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {   
        try{
            Mail::to($this->email)
            ->send(new gmailMail($this->subject,$this->data));
        }catch(Exception $e){
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
    
            throw $e; // keep the job marked as failed
        }
         
    }
}
