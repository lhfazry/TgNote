<?php 
namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;
use Fazrilabs\Util\TgNote;

class ClearCommand extends Command
{
    protected $name = 'clear';                      //your command's name
    protected $description = 'Clear draft'; //Your command description
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
        $text = $message->getText(true);           // Get recieved text

        $draft = TgNote::getDraft($chat_id);

        if(!empty($draft)) {
            $success = TgNote::clearBuffer($chat_id);

            if($success)
            {
                $text = "Great, draft is now clear";
            }
            else 
            {
                $text = "Oooops, operation failed, something was wrong";
            }
        }
        else
        {
            $text = "Your draft is empty. Don't wasting your time clearing an empty draft";
        }
        
        $data = array();                             // prepapre $data
        $data['chat_id'] = $chat_id;                 //set chat Id
        $data['reply_to_message_id'] = $message_id;  //set message Id
        $data['text'] = $text;    //set reply message

        $result = Request::sendMessage($data);       //send message
        return $result->isOk();
     }
}
