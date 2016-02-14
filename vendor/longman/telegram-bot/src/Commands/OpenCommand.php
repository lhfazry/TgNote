<?php 
namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;
use Fazrilabs\Util\TgNote;

class OpenCommand extends Command
{
    protected $name = 'open';                      //your command's name
    protected $description = 'Open a note'; //Your command description
    protected $usage = '/open <note_id>';                    // Usage of your command
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
            $text = "Please specify a note_id in format: /open <note_id>"; 
        }
        else {
            $pos = strpos($text, " ");

            if($pos !== false) {
                $seq = substr($text, 0, strpos($text, " "));
            }
            else {
                $seq = $text;
            }

            $note = TgNote::getNoteBySeq($chat_id, $seq);

            if(!empty($note))
            {
                $note_lines = TgNote::getLinesString($note['id']);

                if(!empty($note_lines))
                {
                    $text = "{$note['title']}\n\n{$note_lines}";
                }
                else 
                {
                    $text = "Oooops, can't open this note, an error occured";
                }
            }
            else {
                $text = "Oooops, can't open this note, an error occured";
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
