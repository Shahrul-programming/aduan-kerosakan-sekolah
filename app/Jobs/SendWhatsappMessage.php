<?php

namespace App\Jobs;

use App\Services\WhatsappService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWhatsappMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3; // total attempts

    public $backoff = [10, 30, 120]; // seconds between retries

    protected string $to;

    protected string $message;

    public function __construct(string $to, string $message)
    {
        $this->to = $to;
        $this->message = $message;
    }

    public function handle(): void
    {
        $ok = WhatsappService::sendMessageSync($this->to, $this->message);
        if (! $ok) {
            // Throwing will trigger retry
            throw new \RuntimeException('Failed to send WhatsApp message');
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('SendWhatsappMessage failed permanently: '.$exception->getMessage(), [
            'to' => $this->to,
        ]);
    }
}
