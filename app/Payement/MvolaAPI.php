<?php

namespace App\Payement;

use DateTime;
use DateTimeZone;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Http;

class MvolaAPI
{
    private string $apiKey;
    private string $apiSecret;
    private string $baseUrl;
    private string $accessToken;
    private string $partnerName;

    public string $currency = 'Ar';
    private string $version = '1.0';

    private string $userLanguage = 'mg';
    private string $cacheControl = 'no-cache';

    private string $userAccountIdentifierPrefix = 'msisdn;';

    private string $creditPartnerPhoneNumber;

    public function __construct()
    {
        $this->apiKey = getenv('MVOLA_API_KEY') ?: '';
        $this->apiSecret = getenv('MVOLA_API_SECRET') ?: '';
        $this->accessToken = getenv('MVOLA_ACCESS_TOKEN') ?: '';
        $this->partnerName = getenv('MVOLA_PARTNER_NAME') ?: 'TESTPARTNER';
        $this->baseUrl = rtrim("https://devapi.mvola.mg/mvola/mm/transactions/type/merchantpay/1.0.0/", '/');
        $this->creditPartnerPhoneNumber = getenv('MVOLA_CREDIT_PARTNER_PHONE_NUMBER') ?: '';
    }

    public function __destruct()
    {
        // Rien à libérer ici, mais garde la structure propre
    }

    /**
     * 🔐 Permet de définir dynamiquement le token d’accès MVola.
     */
    public function setAccessToken(string $token): void
    {
        $this->accessToken = $token;
    }

    /**
     * 💰 Crée un paiement marchand MVola (identique à la version Python)
     */
    public function createPayment(string $amount, string $senderMsisdn, string $description = 'Test MVola'): array
    {
        $transactionId = Uuid::uuid4()->toString();

        // 🧾 Corps de la requête
        $payload = [
            'currency' => $this->currency,
            'amount' => $amount,
            'requestingOrganisationTransactionReference' => $transactionId,
            'requestDate' => $this->createValidISODATE(),
            'descriptionText' => $description,
            'originalTransactionReference' => $transactionId,
            'debitParty' => [['key' => 'msisdn', 'value' => $senderMsisdn]],
            'creditParty' => [['key' => 'msisdn', 'value' => $this->creditPartnerPhoneNumber]],
            'metadata' => [['key' => 'partnerName', 'value' => $this->partnerName]],
        ];

        // 📦 En-têtes HTTP (strictement alignés avec le Python)
        $headers = [
            'Version' => $this->version,
            'X-CorrelationID' => Uuid::uuid4()->toString(),
            'UserLanguage' => $this->userLanguage,
            'UserAccountIdentifier' => "msisdn;{$this->creditPartnerPhoneNumber}",
            'partnerName' => $this->partnerName,
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$this->accessToken}",
            'Cache-Control' => $this->cacheControl,
        ];

        // 🚀 Envoi de la requête
        $response = Http::withHeaders($headers)->post($this->baseUrl, $payload);

        return [
            'status_code' => $response->status(),
            'response' => $response->json() ?? $response->body(),
        ];
    }

    /**
     * 📦 Vérifie le statut d’un paiement existant.
     */
    public function checkPaymentStatus(string $transactionReference): array
    {
        $url = "{$this->baseUrl}/{$transactionReference}";
        $response = Http::withToken($this->accessToken)->get($url);

        return [
            'status_code' => $response->status(),
            'response' => $response->json() ?? $response->body(),
        ];
    }

    /**
     * 🕒 Format ISO 8601 avec millisecondes et suffixe 'Z' (obligatoire pour MVola)
     */
    private function createValidISODATE(): string
    {
        $dt = new DateTime('now', new DateTimeZone('UTC'));
        return $dt->format('Y-m-d\TH:i:s.v\Z');
    }
}
