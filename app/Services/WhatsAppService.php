<?php

namespace App\Services;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private $apiUrl;
    private $apiKey;

    public function __construct()
    {
        $this->apiUrl = 'https://sender.fluidpay.link/api/whatsapp/send';
        // Récupérer la clé API depuis vos settings
        $this->apiKey = $this->getApiKey();
    }

    private function getApiKey()
    {
        // Récupérer depuis la base de données
        $setting = \App\Models\SettingApp::where('key', 'token_fluid_sender')->first();
        return $setting ? $setting->value : config('services.whatsapp.api_key');
    }

    private function getSessionName()
    {
        $setting = \App\Models\SettingApp::where('key', 'session_name')->first();
        return $setting ? $setting->value : 'Default';
    }

    /**
     * Envoyer un message WhatsApp
     */
    public function sendMessage($number, $message, $url_file = null)
    {
        try {
            $response = Http::withHeaders([
                'Api-key' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->apiUrl, [
                'contact' => [
                    [
                        'number' => $number,
                        'message' => $message,
                        'session_name' => $this->getSessionName(),
                        'media' => "document",
                        'url' => $url_file,
                    ]
                ]
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully', [
                    'number' => $number,
                    'message' => $message,
                    'response' => $response->json()
                ]);
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                Log::error('WhatsApp API error', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return [
                    'success' => false,
                    'error' => 'Erreur API: ' . $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp service exception', [
                'error' => $e->getMessage(),
                'number' => $number
            ]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }


    /**
     * Envoyer un message WhatsApp
     */
    public function sendMessageWithoutDocument($number, $message)
    {
        try {
            $response = Http::withHeaders([
                'Api-key' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->apiUrl, [
                'contact' => [
                    [
                        'number' => $number,
                        'message' => $message,
                        'session_name' => $this->getSessionName(),
                    ]
                ]
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully', [
                    'number' => $number,
                    'message' => $message,
                    'response' => $response->json()
                ]);
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                Log::error('WhatsApp API error', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return [
                    'success' => false,
                    'error' => 'Erreur API: ' . $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp service exception', [
                'error' => $e->getMessage(),
                'number' => $number
            ]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Envoyer à plusieurs contacts
     */
    public function sendBulkMessages($contacts)
    {
        try {
            $contactsData = [];

            foreach ($contacts as $contact) {
                $contactsData[] = [
                    'number' => $contact['number'],
                    'message' => $contact['message'],
                    'session_name' => $this->getSessionName()
                ];
            }

            $response = Http::withHeaders([
                'Api-key' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->apiUrl, [
                'contact' => $contactsData
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Erreur API: ' . $response->status()
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
