<?php
require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use Eslavon\VkBotApi\VkBotApi;
use Dotenv\Dotenv;

Dotenv::createImmutable(__DIR__, '.env')->load();
$vk = new VkBotApi(new Client(), $_ENV['ACCESS_TOKEN']);
$result = $vk->helper()->install()->start($_ENV['URL_BOT'], $_ENV['GROUP_ID'], $_ENV['NAME_BOT']);