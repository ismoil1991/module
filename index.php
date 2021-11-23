<?php
require_once 'Database.php';

$users = Database::getInstance()->insert('posts', [
    'title' => 'do it1',
    'description' => 'do it1 do it1 do it1'
]);