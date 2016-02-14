<?php 
namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;
use Fazrilabs\Util\TgNote;

class AppendparCommand extends Command
{
    protected $name = 'appendpar';                      //your command's name
    protected $description = 'Append a new paragraph to an existing note'; //Your command description
    protected $usage = '/appendpar <noteId> <text>';                    // Usage of your command
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
            $text = "Please specify a note_id and text in format: /appendpar <note_id> <text>";
        }
        else {
            $text = trim($text);
            $texts = explode(" ", $text);
            
            if(sizeof($texts) > 1) {
                $pos = strpos($text, " ");
                $seq = substr($text, 0, $pos);
                $paragraph = trim(substr($text, $pos));

                $note = TgNote::getNoteBySeq($chat_id, $seq);

                if(!empty($note))
                {
                    $id = TgNote::appendLine($note['id'], $paragraph);

                    if($id > 0) {
                        $text = "Success. One paragraph appendded to note \"{$note['title']}\"";
                    }
                    else {
                        $text = "Error, something went wrong";
                    }    
                }
                else {
                    $text = "Invalid note";
                }
            }
            else {
                $text = "Please specify a note_id and text in format /appendpar <note_id> <text>";
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
