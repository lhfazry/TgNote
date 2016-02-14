<?php 
namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;
use Fazrilabs\Util\TgNote;

class ChangelogCommand extends Command
{
    protected $name = 'changelog';                      //your command's name
    protected $description = 'Display latest changelog'; //Your command description
    protected $usage = '/clear';                    // Usage of your command
    protected $version = '1.0.0';                  
    protected $enabled = true;
    protected $public = true;
    
    public function execute()
    {
        $update = $this->getUpdate();                //get Updates
        $message = $this->getMessage();              // get Message info

        $chat_id = $message->getChat()->getId();     //Get Chat Id
        $message_id = $message->getMessageId();      //Get message Id
        $text = "v 0.8.1\n\n".
            "1. /addline command is now deprecated.\n".
            "2. /buffer command is now deprecated.\n".
            "3. Replace /addline command with /addpar command.\n".
            "4. Replace /buffer command with /draft command.\n".
            "5. Add new command: /appendpar.\n".
            "6. Add new command: /changetitle.\n".
            "7. Add new command: /changelog.";           // Get recieved text
        
        $data = array();                             // prepapre $data
        $data['chat_id'] = $chat_id;                 //set chat Id
        $data['reply_to_message_id'] = $message_id;  //set message Id
        $data['text'] = $text;    //set reply message

        $result = Request::sendMessage($data);       //send message
        return $result->isOk();
     }
}
