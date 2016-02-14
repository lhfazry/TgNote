<?php

/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/
namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;

class NewchatparticipantCommand extends Command
{
    protected $name = 'Newchatparticipant';
    protected $description = 'New Chat Participant';
    protected $usage = '/';
    protected $version = '1.0.0';
    protected $enabled = true;

    public function execute()
    {
        $update = $this->getUpdate();
        $message = $this->getMessage();

        $participant = $message->getNewChatParticipant();

        $chat_id = $message->getChat()->getId();
        $title = $message->getChat()->getTitle();
        $bot_name = $this->getTelegram()->getBotName();

        $data = [];
        $data['chat_id'] = $chat_id;

        if (strtolower($participant->getUsername()) == strtolower($bot_name)) {
            $text = "What group is this? Hmmm, \"{$title}\", sound good.\n\nOk, I'm TgNote, a simple cloud note on Telegram. I am created to extend the Telegram functionality. Now you can create a personal or group note on telegram so you can access it easily in the future.\n\nStart by using this command /addpar <text>, this action will create a draft note.\nTo finish your note, send me command /save <title>.\n\nOk just it for now, for a complete command, typing /help.\nHappy noting!!\n\n\n\nPowered by fazrilabs";
        } else {
            $text = 'Hi '.$participant->tryMention()."! Welcome to \"{$title}\". You can access to the group note using this command /list";
        }

        $data['text'] = $text;
        $result = Request::sendMessage($data);
        return $result->isOk();
    }
}
