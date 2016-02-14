<?php
//README
//This configuration file is intented to run the bot with the webhook method.
//Uncommented parameters must be filled 
//Please notice that if you open this file with your browser you'll get the "Input is empty!" Exception.
//This is a normal behaviour because this address has to be reached only by Telegram server

//Composer Loader
$dir = realpath(__DIR__);
$loader = require $dir.'/vendor/autoload.php';

$API_KEY = '172983710:AAHENd7L63WVNWIremrNMfupT5xt5UCm9rk';
$BOT_NAME = 'tgnote_bot';
$COMMANDS_FOLDER = $dir.'/src/Fazrilabs/Commands/';
$credentials = [
    'host'=>'localhost',
    'user'=>'app',
    'password'=>'ju*75309L.-+-',
    'database'=>'tgnote'
];

try {
    // create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($API_KEY, $BOT_NAME);
    
    ////Options
    $telegram->enableMySQL($credentials);
     
    ////Enable mysql with table prefix
    //$telegram->enableMySQL($credentials, $BOT_NAME.'_');

    //$telegram->addCommandsPath($COMMANDS_FOLDER);

    ////Here you can enable admin interface ache the channel you want to manage
    $telegram->enableAdmins([12078408]);
    //$telegram->setCommandConfig('sendtochannel', ['your_channel'=>'@type_here_your_channel']);

    ////Here you can set some command specified parameters,
    ////for example, google geocode/timezone api key for date command:
    //$telegram->setCommandConfig('date', array('google_api_key'=>'your_google_api_key_here'));

    ////Logging
    $telegram->setLogRequests(true);
    $telegram->setLogPath($BOT_NAME.'.log');
    $telegram->setLogVerbosity(3);

    $telegram->setDownloadPath("downloads");
    $telegram->setUploadPath("uploads");

    // handle telegram webhook request
    $telegram->handle();

} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    //Silence is gold!
    // log telegram errors
    // echo $e;
}
