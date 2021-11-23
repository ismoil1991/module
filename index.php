<?php
require_once 'Database.php';

//$users = Database::getInstance()->query("SELECT * FROM posts WHERE title title = ?", ["do it"]);
$users = Database::getInstance()->get('posts', ['title', '=', 'do it']);
//Database::getInstance()->delete('posts', ['title', '=', 'title from create.php']);

if ($users->error()) {
    echo "we have an error";
} else {
    foreach ($users->results() as $result) {
        echo $result->title . '<br>';
    }
}