<?php

namespace App\Observers;

use App\Jobs\SendBookingWebhook;
use App\Models\Appointment;

class AppointmentObserver
{
    /**
     * Push new customer bookings to n8n so it can place the confirmation call.
     * Only web/embed bookings — appointments created through the n8n API
     * (source: manual/phone) must not echo back and cause a loop.
     */
    public function created(Appointment $appointment): void
    {
        if (in_array($appointment->source, ['web', 'embed'], true)) {
            SendBookingWebhook::dispatchAfterResponse($appointment->id);
        }
    }
}
