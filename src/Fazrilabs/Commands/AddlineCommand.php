<?php 
namespace Fazrilabs\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;
use Fazrilabs\Util\TgNote;

class AddlineCommand extends Command
{
    protected $name = 'addline';                      //your command's name
    protected $description = 'Add a new line to the buffer'; //Your command description
    protected $usage = '/addline <text>';                    // Usage of your command
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

        if(empty($text))
        {
            $text = "Please specify text you want to add as a line in format: /addline <text>"; 
        }
        else {
            $id = TgNote::buffer($chat_id, $text);

            if($id)
            {
                $note = TgNote::getDraft($chat_id);
                $line_count = TgNote::getLineCount($note['id']);
                $text = "Coool, one line added to buffer.\nTotal line on buffer: {$line_count}";
            }    
        }

        $data = array();                             // prepapre $data
        $data['chat_id'] = $chat_id;                 //set chat Id
        $data['reply_to_message_id'] = $message_id;  //set message Id
        $data['text'] = $text;    //set reply message

        $result = Request::sendMessage($data);       //send message
        return $result->isOk();
     }
}
