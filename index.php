<?php
require_once 'Database.php';

//get one
$users = Database::getInstance()->get('posts', ['id', '=', '2']);
echo $users->first()->title;

// update
//$id = 2;
//
//$users = Database::getInstance()->update('posts', $id, [
//    'title' => 'do it2',
//    'description' => 'do it2 do it2 do it2'
//]);
