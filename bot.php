<?php

if( ! isset($argv[1])) exit('No input received. Please try again.' . PHP_EOL);

include 'init.php';

use GuzzleHttp\Client;

$url = "https://language.googleapis.com/v1beta2/documents:analyzeEntitySentiment?key=" . API_KEY;

$client = new Client;

$response = $client->request('POST', $url, [
    'json' => [
    	'document' => [
    		'content' => $argv[1],
    		'type' => 'PLAIN_TEXT'
    	],
    	'encodingType' => 'UTF8'
    ]
]);

$contents = json_decode($response->getBody()->getContents());

echo get_reply($contents) . PHP_EOL;