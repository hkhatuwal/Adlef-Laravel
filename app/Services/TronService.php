<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TronService
{
    private const TRON_WALLET_ADDRESSES = [
        'TETRZNcZwB4RAneyBytqm6KFdMuC6Ug47C',
        // Add more wallet addresses here as needed
    ];
    private const TRON_API_KEY = 'a840beed-cc41-45de-9e58-c538671ed053';
    private const BASE_URL = 'https://api.trongrid.io';

    private string $api_key;
    private string $tron_node_base_url;

    public function __construct()
    {
        $this->api_key = config('keys.tron_node_secret');
        $this->tron_node_base_url = config('keys.tron_node_url');


        if (!$this->api_key) {
            Log::error("TRON API key not configured in keys.tron_node_secret");
            return null;
        }

        if (!$this->tron_node_base_url) {
            Log::error("TRON API URL not configured in keys.tron_node_url");
            return null;
        }
    }

    /**
     * Create a transfer using the transfer API
     *
     * @param string $toAddress The destination address
     * @param float $amount The amount to transfer
     * @return array|null
     */
    public function createTransfer(string $toAddress, float $amount): ?array
    {
        try {

            $data = [
                'toAddress' => $toAddress,
                'amount' => $amount
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->api_key,
                'Content-Type' => 'application/json',
                'accept' => 'application/json'
            ])->timeout(30)->post($this->tron_node_base_url . '/api/transfer', $data);

            if ($response->successful()) {
                Log::info("TRON transfer created successfully", [
                    'to_address' => $toAddress,
                    'amount' => $amount
                ]);
                return $response->json();
            }

            Log::error("TRON transfer API HTTP error: " . $response->status() . ", Response: " . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error("TRON transfer API request error: " . $e->getMessage());
            return null;
        }
    }


    /**
     * Create a tron account using the node api
     *
     * @return array|null
     */
    public function createTronAccount(): ?array
    {
        try {

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->api_key,
                'Content-Type' => 'application/json',
                'accept' => 'application/json'
            ])->timeout(30)->post($this->tron_node_base_url . '/api/account/create');

            if ($response->successful()) {
                Log::info("TRON account created successfully", [
                    'wallet' => $response->json(),
                ]);
                return $response->json()['data']['wallet'];
            }

            Log::error("TRON transfer API HTTP error: " . $response->status() . ", Response: " . $response->body() . " Url: " . $this->tron_node_base_url . '/api/account/create');
            return null;

        } catch (\Exception $e) {
            Log::error("TRON transfer API request error: " . $e->getMessage());
            return null;
        }
    }




    /**
     * Fetch transactions from TRON API
     *
     * @param string $walletAddress The wallet address to fetch transactions for
     * @param int $minTimestamp Minimum timestamp to fetch transactions from
     * @return array|null
     */
    public function fetchTransactions(string $walletAddress, int $minTimestamp,$apiKey=self::TRON_API_KEY): ?array
    {
        try {
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'TRON-PRO-API-KEY' => $apiKey
            ])->timeout(30)->get(self::BASE_URL . '/v1/accounts/' . $walletAddress . '/transactions/trc20', [
                'min_timestamp' => $minTimestamp
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error("TRON API HTTP error: " . $response->status() . ", Response: " . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error("TRON API request error: " . $e->getMessage());
            return null;
        }
    }



    /**
     * Get the configured TRON wallet addresses
     *
     * @return array
     */
    public function getWalletAddresses(): array
    {
        return self::TRON_WALLET_ADDRESSES;
    }

    /**
     * Get the configured TRON API key
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return self::TRON_API_KEY;
    }

    /**
     * Example usage of creating a TRON transaction
     * You can call this method from controllers or other services
     *
     * @param string $ownerAddress
     * @param string $toAddress
     * @param float $trxAmount Amount in TRX (will be converted to SUN)
     * @return array|null
     */
    public function sendTrx(string $ownerAddress, string $toAddress, float $trxAmount): ?array
    {
        // Convert TRX to SUN (1 TRX = 1,000,000 SUN)
        $amountInSun = (int)($trxAmount * 1000000);

        Log::info("Creating TRON transaction", [
            'owner_address' => $ownerAddress,
            'to_address' => $toAddress,
            'trx_amount' => $trxAmount,
            'sun_amount' => $amountInSun
        ]);

        return $this->createTransaction($ownerAddress, $toAddress, $amountInSun);
    }
}
