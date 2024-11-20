<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL | E_STRICT);

$html = file_get_contents('home.html');

$nrOfViews = 0;
$file = './material/visitorCounter.txt';

if (is_writable($file)) {
    $content = file_get_contents($file);
    $nrOfViews = intval($content);
    $nrOfViews = $nrOfViews + 1;
    file_put_contents($file, $nrOfViews, LOCK_EX);
} else {
    print "Ett fel uppstod!";
}

$html = str_replace('---$views---', $nrOfViews, $html);
echo $html;
