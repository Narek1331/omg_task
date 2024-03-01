<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CurrencyControllerTest extends TestCase
{
    /**
     * Test getting latest currency rates.
     *
     * @return void
     */
    public function testGetLatestRates()
    {
        // Mock successful response from the external API
        $this->mockHttpResponses([200, $this->mockCurrencyApiResponse()]);

        // Send a request to the endpoint
        $response = $this->get('/currency/latest');

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert the response contains the expected data
        $response->assertJsonStructure([
            'ValCurs' => [
                'Date',
                'Valute' => [
                    '*' => [
                        'ID',
                        'NumCode',
                        'CharCode',
                        'Nominal',
                        'Name',
                        'Value',
                        'VunitRate',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Test getting historical currency rates.
     *
     * @return void
     */
    public function testGetHistoricalRates()
    {
        // Send a request to the endpoint without required parameters
        $response = $this->get('/currency/history');

        // Assert the response status is 400 (Bad Request)
        $response->assertStatus(400);

        // Mock successful response from the external API
        $this->mockHttpResponses([200, $this->mockCurrencyApiResponse()]);

        // Send a request to the endpoint with valid parameters
        $response = $this->get('/currency/history?start_date=2024-01-01&end_date=2024-01-31');

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert the response contains the expected data
        // Add your assertions for historical rates data structure here
    }

    /**
     * Helper method to mock HTTP responses.
     *
     * @param array $responses
     * @return void
     */
    protected function mockHttpResponses($responses)
    {
        Http::fakeSequence()->pushStatus($responses);
    }

    /**
     * Helper method to mock currency API response.
     *
     * @return string
     */
    protected function mockCurrencyApiResponse()
    {
        return file_get_contents(__DIR__.'/mocks/currency_response.xml');
    }
}
