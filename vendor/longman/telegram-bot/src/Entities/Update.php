<?php

/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/
namespace Longman\TelegramBot\Entities;

use Longman\TelegramBot\Exception\TelegramException;

class Update extends Entity
{

    protected $update_id;
    protected $message;
    protected $inline_query;
    protected $chosen_inline_result;
    private $update_type;

    public function __construct(array $data, $bot_name, $let_update_id_empty = 0)
    {

        $this->bot_name = $bot_name;

        $update_id = isset($data['update_id']) ? $data['update_id'] : null;
        $this->update_id = $update_id;

        $this->message = isset($data['message']) ? $data['message'] : null;
        if (!empty($this->message)) {
            $this->message = new Message($this->message, $bot_name);
            $this->update_type = 'message';
        }

        if (empty($update_id) && !$let_update_id_empty) {
            throw new TelegramException('update_id is empty!');
        }


        $this->inline_query = isset($data['inline_query']) ? $data['inline_query'] : null;
        if (!empty($this->inline_query)) {
            $this->inline_query = new InlineQuery($this->inline_query);
            $this->update_type = 'inline_query';
        }
        $this->chosen_inline_result = isset($data['chosen_inline_result']) ? $data['chosen_inline_result'] : null;
        if (!empty($this->chosen_inline_result)) {
            $this->chosen_inline_result = new ChosenInlineResult($this->chosen_inline_result);
            $this->update_type = 'chosen_inline_result';
        }
    }

    public function getUpdateId()
    {
        return $this->update_id;
    }

    public function getMessage()
    {
        return $this->message;
    }
    public function getInlineQuery()
    {
        return $this->inline_query;
    }
    public function getChosenInlineResult()
    {
        return $this->chosen_inline_result;
    }
    public function getUpdateType()
    {
        return $this->update_type;
    }
}
