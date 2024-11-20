<?php

//include('documentary.html');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL | E_STRICT);

session_start();
$session_id = session_id();

$html = file_get_contents('documentary.html');
$pieces = explode("<!--===entries===-->", $html);
echo $pieces[0];

$connection = new mysqli('localhost', 'root', '', 'hemsida');
//$connection = new mysqli('mysql91.unoeuro.com', 'framtidensjordbruk_se', 'dnwc3tHhxAgrezEbfD6G', 'framtidensjordbruk_se_db');

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if (!$connection->select_db('framtidensjordbruk_se_db')) {
    die("Database selection failed: " . $connection->error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim(strip_tags($_POST['comment_author']));
    $comment = trim(strip_tags($_POST['comment']));

    $query = "INSERT INTO kommentarer (namn, kommentar, tid, session_id) VALUES ('$name', '$comment', NOW(), '$session_id')";

    mysqli_query($connection, $query);
}

$session_id = session_id();
$sql = "SELECT namn, kommentar, tid FROM kommentarer ORDER BY tid ASC";

$result = $connection->query($sql);

if ($result) {
    $row_number = 0;
    while ($row = $result->fetch_assoc()) {
        $entry = $pieces[1];
        $row_number++;
        $entry = str_replace('---no---', $row_number, $entry);
        $entry = str_replace('---time---', $row["tid"], $entry);
        $entry = str_replace('---name---', $row["namn"], $entry);
        $entry = str_replace('---comment---', $row["kommentar"], $entry);
        echo $entry;
    }
    $result->close();
} else {
    echo "Query error: " . $connection->error;
}

echo $pieces[2];

$connection->close();

