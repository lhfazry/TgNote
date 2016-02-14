<?php
namespace Fazrilabs\Util;

use MysqliDb;

class DbHelper {
    private $db;

    static function getDb() {
        if(empty($db)) {
            $db = new MysqliDb ('localhost', 'app', 'ju*75309L.-+-', 'tgnote'); 
        }

        return $db;
    }
}
