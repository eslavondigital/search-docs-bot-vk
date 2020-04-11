<?php
require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use Eslavon\VkBotApi\VkBotApi;
use Dotenv\Dotenv;

Dotenv::createImmutable (__DIR__,'.env')->load();
$vk = new VkBotApi(new Client(), $_ENV['ACCESS_TOKEN']);
$event = $vk->callback()->run();

switch ($vk->callback()->getEventType()) {
    case "confirmation";
        $confirm = $vk->groups()->getCallbackConfirmationCode($vk->callback()->getGroupId());
        $confirm = json_decode($confirm);
        echo $confirm->response->code;
        break;
    case "message_new";
        echo "ok";
        $result = $vk->docs()->search($event->getText(), false, 10);
        $data = json_decode($result);
        if ($data->response->count == 0) {
            $send_text = '&#128269 По вашему запросу «' . $event->getText() . '» ничего не найдено!';
        } else {
            foreach ($data->response->items as $key => $value) {
                $attach = 'doc' . $value->owner_id . '_' . $value->id;
                $vk->helper()->messages()->addAttachment($attach);
            }
            $send_text = '&#128269; Вот что я нашел по вашему запросу «' . $event->getText() . '»';
        }
        $vk->helper()->messages()->setMessage($send_text);
        $vk->helper()->messages()->setPeerId($event->getPeerId());
        $vk->messages()->send($vk->helper()->messages()->getParameters());
        break;
}
