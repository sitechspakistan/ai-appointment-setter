<?php

namespace App\Jobs;

use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Notifies the n8n backend that a customer just booked, so it can place the
 * Vapi confirmation call. Fired from AppointmentObserver for web/embed
 * bookings only (never for appointments n8n itself created via the API).
 *
 * Dispatched afterResponse() so the customer never waits on n8n.
 */
class SendBookingWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [10, 60, 300];

    public function __construct(public int $appointmentId)
    {
    }

    public function handle(): void
    {
        $url = Setting::get('n8n_booking_webhook_url');

        if (! $url) {
            return;
        }

        $appointment = Appointment::withoutGlobalScopes()
            ->with(['tenant', 'service'])
            ->find($this->appointmentId);

        if (! $appointment) {
            return;
        }

        $payload = [
            'event' => 'appointment.booked',
            'sent_at' => now()->toIso8601String(),
            'data' => (new AppointmentResource($appointment))->resolve(request()),
        ];

        $body = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $secret = config('services.n8n.webhook_secret');

        $response = Http::withHeaders(array_filter([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-Webefy-Event' => 'appointment.booked',
            'X-Webefy-Signature' => $secret ? 'sha256='.hash_hmac('sha256', $body, $secret) : null,
        ]))->timeout(10)->withBody($body, 'application/json')->post($url);

        if ($response->failed()) {
            Log::warning('n8n booking webhook failed', [
                'appointment_id' => $this->appointmentId,
                'status' => $response->status(),
            ]);
            $response->throw(); // let the queue retry with backoff
        }
    }
}
