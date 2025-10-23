<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Payement\MvolaAPI;
use Illuminate\Support\Facades\Http;
use Ramsey\Uuid\Uuid;

class MvolaAPITest extends TestCase
{
    /**
     * 🔹 Test de la méthode createPayment()
     */
    public function test_create_payment_successful()
    {
        // Simule une réponse API de succès
        Http::fake([
            'https://devapi.mvola.mg/*' => Http::response([
                'transactionStatus' => 'completed',
                'transactionReference' => '12345',
            ], 201)
        ]);

        $api = new MvolaAPI();
        $api->setAccessToken('eyJ4NXQiOiJaREUzWW1RNFkyRmtZekprTmpNMk5EVmtZVE5oTkRSak16azFObVEyWXprelkyUTFaVFZqWVEiLCJraWQiOiJNVGRsTXpneFpqZGtNakk0WmpKbVlUZ3dNRFJpWWpNMU1tUmhOamxoTUdNME1XTmtPV05tT1RobU16VXlNMlUxTkRZNE5UWXhOMk01TW1SbU5XUTRPQV9SUzI1NiIsInR5cCI6ImF0K2p3dCIsImFsZyI6IlJTMjU2In0.eyJzdWIiOiJOYXJpaHkubWdAZ21haWwuY29tIiwiYXV0IjoiQVBQTElDQVRJT04iLCJhdWQiOiJwb0kyVUxWSTNqcWF5eTAyYzFXOHUzTnFOYklhIiwibmJmIjoxNzYxMjIyNTQ4LCJhenAiOiJwb0kyVUxWSTNqcWF5eTAyYzFXOHUzTnFOYklhIiwic2NvcGUiOiJFWFRfSU5UX01WT0xBX1NDT1BFIiwiaXNzIjoiaHR0cHM6XC9cL2RldmVsb3Blci5tdm9sYS5tZ1wvb2F1dGgyXC90b2tlbiIsInJlYWxtIjp7InNpZ25pbmdfdGVuYW50IjoiY2FyYm9uLnN1cGVyIn0sImV4cCI6MTc2MTIyNjE0OCwiaWF0IjoxNzYxMjIyNTQ4LCJqdGkiOiJiOTdhNzgxOC04YTY0LTRhNzgtYmNkMC1kNjkxMjEwNjNjMjEifQ.XusJ4fxzsfmemRCQ74n2sF022yNOS41EOeMP2cCquVp9KlIMpqhcbruwm_Kb4kH_jUKQqANk3DzsYMoRqPvpePiDTA876WzV1huTUPCpKc7FfgK8A2_MgagxYYrh1M9Tj7Ybks27Wi-EGIge-HyO4Zbs7C2t40QHTOs3eFM84ezkDznuRmo5GQdeM7VCSIXuE7Y0g3XHhojYxU6uDm8vHmmELfTOTBC7HKVqu2YO1d2DIaYqafR1KW9mxxL76q9VpQSHP3kxxi9c2N3OZdd7dGvBux2xDLOVljGW8cge7gcaYHvrsAXJYnRVuY6E9lq3xkML4ZnR_CpHvQOOljyxlg');

        $result = $api->createPayment('10000000', '0343500003', 'Test Payment');

        // ✅ Assertions
        $this->assertEquals(201, $result['status_code']);
        $this->assertArrayHasKey('response', $result);
        $this->assertEquals('completed', $result['response']['transactionStatus']);
    }

    /**
     * 🔹 Test d’un échec de paiement (ex. erreur de validation)
     */
    public function test_create_payment_failure()
    {
        Http::fake([
            'https://devapi.mvola.mg/*' => Http::response([
                'errorCategory' => 'validation',
                'errorCode' => 'formatError',
            ], 400)
        ]);

        $api = new MvolaAPI();
        $api->setAccessToken('fake_token');

        $result = $api->createPayment('invalid', '0341234567');

        $this->assertEquals(400, $result['status_code']);
        $this->assertEquals('validation', $result['response']['errorCategory']);
    }

    /**
     * 🔹 Test du format ISO de la date générée
     */
    public function test_create_valid_iso_date_format()
    {
        $api = new MvolaAPI();

        $method = new \ReflectionMethod(MvolaAPI::class, 'createValidISODATE');
        $method->setAccessible(true);

        $isoDate = $method->invoke($api);

        $this->assertMatchesRegularExpression(
            '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{3}Z$/',
            $isoDate,
            "Format ISO invalide : {$isoDate}"
        );
    }

    /**
     * 🔹 Test de la vérification du statut
     */
    public function test_check_payment_status()
    {
        Http::fake([
            'https://devapi.mvola.mg/*' => Http::response([
                'transactionStatus' => 'pending'
            ], 200)
        ]);

        $api = new MvolaAPI();
        $api->setAccessToken('fake_token');

        $result = $api->checkPaymentStatus('abc-123');

        $this->assertEquals(200, $result['status_code']);
        $this->assertEquals('pending', $result['response']['transactionStatus']);
    }
}
