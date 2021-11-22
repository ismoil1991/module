<?php
require_once 'Database.php';

$users = Database::getInstance()->query("SELECT * FROM posts");

if ($users->error()){
    echo "we have an error";
} else {
    foreach ($users->results() as $result) {
        echo $result->title .'<br>';
    }
}