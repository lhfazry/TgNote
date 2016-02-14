<?php 
namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;
use Fazrilabs\Util\TgNote;

class SaveCommand extends Command
{
    protected $name = 'save';                      //your command's name
    protected $description = 'Save draft'; //Your command description
    protected $usage = '/save <title>';                    // Usage of your command
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
            $text = "Please specify title in format: /save <title>"; 
        }
        else {
            $draft = TgNote::getDraft($chat_id);

            if(!empty($draft)) {
                $success = TgNote::save($chat_id, $text);

                if($success)
                {
                    $text = "Yuhuuu, your note has been saved. Use /list to display your notes";
                }    
                else 
                {
                    $text = "Oooops, something went wrong";
                }
            }
            else 
            {
                $text = "Wait, wait, wait. Your draft is empty. Create your note using /addpar <text>";    
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
