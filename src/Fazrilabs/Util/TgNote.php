<?php
namespace Fazrilabs\Util;

class TgNote
{
    static function createDraft($chat_id) 
    {
        $db = DbHelper::getDb();
        $note_count = self::getNoteCount($chat_id);

        $data = Array(
            "chat_id" => $chat_id,
            "seq" => $note_count + 1,
            "title" => "Untitled",
            "status" => "draft",
            "created" => date('Y-m-d H:i:s')
        ); 

        $id = $db->insert("note", $data);

        return $id;
    }

    static function getNoteCount($chat_id)
    {
        $db = DbHelper::getDb();

        $db->where("chat_id", $chat_id);
        return $db->getValue("note", "COUNT(id)");
    }

    static function getNote($note_id)
    {
        $db = DbHelper::getDb();

        $db->where("id", $note_id);
        $db->where("status", "saved");
        return $db->getOne("note");
    }

    static function getNoteBySeq($chat_id, $seq)
    {
        $db = DbHelper::getDb();
        $db->where('chat_id', $chat_id);
        $db->where('seq', $seq);
        $db->where('status', "saved");
        return $db->getOne('note');
    }

    static function getDraft($chat_id)
    {
        $db = DbHelper::getDb();

        $db->where("chat_id", $chat_id);
        $db->where("status", "draft");
        $note = $db->getOne("note");

        return $note;
    }

    static function appendLine($note_id, $text)
    {
        $db = DbHelper::getDb();
        $data = Array(
            "note_id" => $note_id,
            "line" => $text
        ); 

        $id = $db->insert("note_line", $data);

        if(!$id)
        {
            error_log($db->getLastError());
        }

        return $id;
    }

    static function buffer($chat_id, $text) 
    {
        $db = DbHelper::getDb();
        $note = self::getDraft($chat_id);
        $note_id = 0;

        if(empty($note))
        {
            $note_id = self::createDraft($chat_id);
        }
        else {
            $note_id = $note['id'];
        }

        $data = Array(
            "note_id" => $note_id,
            "line" => $text
        );

        $id = $db->insert("note_line", $data);

        if(!$id) {
            error_log($db->getLastError());
        }
        return $id;
    }    

    static function clearBuffer($chat_id)
    {
        $db = DbHelper::getDb();
        $note = self::getDraft($chat_id);
        $success = false;

        if(!empty($note)) {
            $db->where("note_id", $note['id']);
            $success = $db->delete("note_line");

            if($success) {
                $db->where("id", $note['id']);
                $success = $db->delete("note");
            }
        } 

        return $success;
    }

    static function getLine($note_id)
    {
        $db = DbHelper::getDb();

        $db->where("note_id", $note_id);
        return $db->getOne("note_line");
    }

    static function getLineCount($note_id)
    {
        $db = DbHelper::getDb();

        $db->where("note_id", $note_id);
        return $db->getValue("note_line", "count(id)");
    }

    static function getLines($note_id) 
    {
        $db = DbHelper::getDb();

        $db->where("note_id", $note_id);
        $lines = $db->get("note_line");

        return $lines;
    }

    static function getLinesString($note_id)
    {
        $lines = self::getLines($note_id);
        $text = "";

        foreach($lines as $line)
        {
            $text .= $line['line']."\n";
        }

        return $text;
    }

    static function save($chat_id, $title)
    {
        $db = DbHelper::getDb();
        $note = self::getDraft($chat_id);
        $success = false;

        if(!empty($note))
        {
            $lines = self::getLinesString($chat_id);
            $excerpt = StringUtil::getExcerpt($lines, 0, 100);
            $data = Array(
                "title" => $title,
                "status" => "saved",
                "excerpt" => $excerpt,
                "created" => date("Y-m-d H:i:s")
            );

            $db->where('id', $note['id']);
            $success = $db->update("note", $data);
        }

        return $success;
    }

    static function changeTitle($note_id, $title)
    {
        $db = DbHelper::getDb();
        $data = Array(
            'title' => $title
        );

        $db->where('id', $note_id);
        $success = $db->update("note", $data);
        return $success;
    }

    static function getNotes($chat_id)
    {
        $db = DbHelper::getDb();

        $db->where('chat_id', $chat_id);
        $db->where('status', 'saved');
        $notes = $db->get("note");

        return $notes;
    }

    static function deleteNote($note_id)
    {
        $db = DbHelper::getDb();
        $data = Array("status" => "deleted");

        $db->where('id', $note_id);
        $db->update("note", $data);
    }
}
