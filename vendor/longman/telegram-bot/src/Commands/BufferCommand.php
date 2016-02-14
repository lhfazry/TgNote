<?php 
namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;
use Fazrilabs\Util\TgNote;

class BufferCommand extends Command
{
    protected $name = 'buffer';                      //your command's name
    protected $description = 'Display all text on the buffer'; //Your command description
    protected $usage = '/buffer';                    // Usage of your command
    protected $version = '1.0.0';                  
    protected $enabled = true;
    protected $public = false;
    
    public function execute()
    {
        $update = $this->getUpdate();                //get Updates
        $message = $this->getMessage();              // get Message info

        $chat_id = $message->getChat()->getId();     //Get Chat Id
        $message_id = $message->getMessageId();      //Get message Id
        $text = $message->getText(true);           // Get recieved text

        /*
        $draft = TgNote::getDraft($chat_id);

        if(!empty($draft))
        {
            $text = TgNote::getLinesString($draft['id']);
        }
        else 
        {
            $text = "Oooo, your buffer is clean. No content to display";
        } */
        
        $data = array();                             // prepapre $data
        $data['chat_id'] = $chat_id;                 //set chat Id
        $data['reply_to_message_id'] = $message_id;  //set message Id
        $data['text'] = "This command was deprecated, please use /draft instead";    //set reply message

        $result = Request::sendMessage($data);       //send message
        return $result->isOk();
     }
}
