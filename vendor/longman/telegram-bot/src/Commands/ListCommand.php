<?php 
namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;
use Fazrilabs\Util\TgNote;

class ListCommand extends Command
{
    protected $name = 'list';                      //your command's name
    protected $description = 'List all your notes'; //Your command description
    protected $usage = '/list';                    // Usage of your command
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

        $notes = TgNote::getNotes($chat_id);
        $size = sizeof($notes);

        if($size > 0) 
        {
            $header = "You have {$size} note(s) so far.\n\nID    Title\n";
            $rows = "";

            foreach($notes as $note){
                $rows .= $note['seq'] . "    {$note['title']}\n";
            }

            $text = $header.$rows;
            $text .= "\n\nTo open a note, use command /open <note_id>.\nTo delete a note, use command /delete <note_id>";
        }
        else {
            $text = "You don't have any note. Please create one using /addpar <text> and then save it using /save <title>";
        }

        $data = array();                             // prepapre $data
        $data['chat_id'] = $chat_id;                 //set chat Id
        $data['reply_to_message_id'] = $message_id;  //set message Id
        $data['text'] = $text;    //set reply message

        $result = Request::sendMessage($data);       //send message
        return $result->isOk();
     }
}
