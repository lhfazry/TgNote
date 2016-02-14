<?php
//Composer Loader
$loader = require __DIR__.'/vendor/autoload.php';

$API_KEY = '172983710:AAHENd7L63WVNWIremrNMfupT5xt5UCm9rk';
$BOT_NAME = 'tgnote_bot';
$link = 'https://tgnote.fazrilabs.com/hook.php';
try {
    // create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($API_KEY, $BOT_NAME);
    // set webhook
    $result = $telegram->setWebHook($link);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e;
}
