<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class BrevoDirectChannel
{
    public function send(object $notifiable, Notification $notification): void
    {

        $message = $notification->toMail($notifiable);

        $recipientEmail = $notifiable->routeNotificationFor('mail', $notification);
        $recipientName = $notifiable->name ?? '';

        if (empty($recipientName)) {
            Log::warning('[BrevoDirectChannel] Recipient name is empty for ' . $recipientEmail . '. Using email as name.');
            $recipientName = $recipientEmail;
        }

        $apiKey = config('mail.mailers.brevo.key');
        if (!$apiKey) {
            Log::error('[BrevoDirectChannel] BREVO_KEY not found in configuration.');
            return;
        }

        $htmlBody = "";
        if (!empty($message->greeting)) {
            $htmlBody .= "<p>" . $message->greeting . "</p>";
        }
        foreach ($message->introLines as $line) {
            $htmlBody .= "<p>" . $line . "</p>"; 
        }

        foreach ($message->outroLines as $line) {
            $htmlBody .= "<p>" . $line . "</p>";
        }
         if (!empty($message->salutation)) {

            $htmlBody .= "<p>" . nl2br(e($message->salutation)) . "</p>";
        }

        $postData = [
            'sender' => [
                'name' => config('mail.from.name'),
                'email' => config('mail.from.address'),
            ],
            'to' => [
                ['email' => $recipientEmail, 'name' => $recipientName],
            ],
            'subject' => $message->subject,
            'htmlContent' => $htmlBody, 
        ];

        Log::debug('[BrevoDirectChannel] JSON Payload being sent: ' . json_encode($postData));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.brevo.com/v3/smtp/email');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'api-key: ' . $apiKey,
            'content-type: application/json',
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            Log::info('[BrevoDirectChannel] Email sent successfully to ' . $recipientEmail . '. Response: ' . $response);
        } else {
            Log::error('[BrevoDirectChannel] Failed to send email to ' . $recipientEmail . '. Code: ' . $httpCode . '. cURL Error: ' . $curlError . '. Response: ' . $response);
        }
    }
}