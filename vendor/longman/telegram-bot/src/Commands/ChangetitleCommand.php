<?php 
namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;
use Fazrilabs\Util\TgNote;

class ChangetitleCommand extends Command
{
    protected $name = 'changetitle';                      //your command's name
    protected $description = 'Change the title of existing note'; //Your command description
    protected $usage = '/changetitle <noteId> <title>';                    // Usage of your command
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
            $text = "Please specify a note_id and title in format: /changetitle <note_id> <title>";
        }
        else {
            $text = trim($text);
            $texts = explode(" ", $text);
            
            if(sizeof($texts) > 1) {
                $pos = strpos($text, " ");
                $seq = substr($text, 0, $pos);
                $title = trim(substr($text, $pos));

                $note = TgNote::getNoteBySeq($chat_id, $seq);

                if(!empty($note))
                {
                    $success = TgNote::changeTitle($note['id'], $title);

                    if($success) {
                        $text = "Success, the title was changed to \"{$title}\"";
                    }
                    else {
                        $text = "Error, something wrong";
                    }    
                }
                else {
                    $text = "Invalid note";
                }
            }
            else {
                $text = "Please specify a note_id and title in format /changetitle <note_id> <title>";
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
