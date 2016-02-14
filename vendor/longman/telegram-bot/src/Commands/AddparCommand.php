<?php 
namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;
use Fazrilabs\Util\TgNote;

class AddparCommand extends Command
{
    protected $name = 'addpar';                      //your command's name
    protected $description = 'Add a new paragraph to draft'; //Your command description
    protected $usage = '/addpar <text>';                    // Usage of your command
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
            $text = "Please specify text you want to add as a paragraph in format: /addpar <text>"; 
        }
        else {
            $id = TgNote::buffer($chat_id, $text);

            if($id)
            {
                $note = TgNote::getDraft($chat_id);
                $line_count = TgNote::getLineCount($note['id']);
                $text = "Coool, one paragraph added to draft.\nTotal paragraph on draft: {$line_count}.\nYou can add another paragraph or save your draft using /save <title>";
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
