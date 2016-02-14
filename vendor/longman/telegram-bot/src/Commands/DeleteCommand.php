<?php 
namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;
use Fazrilabs\Util\TgNote;

class DeleteCommand extends Command
{
    protected $name = 'delete';                      //your command's name
    protected $description = 'Delete a note'; //Your command description
    protected $usage = '/delete <note_id>';                    // Usage of your command
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
            $text = "Please specify a note_id in format: /delete <note_id>"; 
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
                TgNote::deleteNote($note['id']);
                $text = "Great, this note has been deleted";
            }
            else {
                $text = "Oooops, can't delete this note, an error occured";
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
