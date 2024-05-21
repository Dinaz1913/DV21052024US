<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

function getShortUrl(string $longUrl): string
{
    $client = new Client();

    try {
        $response = $client->request('POST', 'https://cleanuri.com/api/v1/shorten', [
            'form_params' => ['url' => $longUrl]
        ]);

        if ($response->getStatusCode() === 200) {
            $body = $response->getBody();
            $data = json_decode($body);

            if (isset($data->result_url)) {
                return $data->result_url;
            }

            if (isset($data->error)) {
                return 'Error: ' . $data->error;
            }
        }
    } catch (GuzzleException $e) {
        return 'An error output: ' . $e->getMessage();
    }

    return 'Failed to shorten URL.';
}

function run(): void
{
    echo 'Enter the long URL: ';
    $handle = fopen('php://stdin', 'r');
    $longUrl = trim(fgets($handle));
    fclose($handle);

    if (filter_var($longUrl, FILTER_VALIDATE_URL)) {
        $shortUrl = getShortUrl($longUrl);
        echo "Short URL: $shortUrl\n";
    } else {
        echo "Invalid URL. Please enter a valid URL.\n";
    }
}

run();
