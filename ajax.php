<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $password = $_POST['password'] ?? '';
    $section = $_POST['section'] ?? ''; 
    $time = $_POST['time'] ?? '';
    $timePeriod = $_POST['timePeriod'] ?? '';

    if (!ctype_digit($id)) {
        echo 'Invalid ID: Only numbers are allowed.';
        exit;
    }

    if (preg_match('/@|\.com$/i', $name)) {
        echo 'Invalid Name: Name should not contain email-like text or ".com".';
        exit;
    }

    $entry = "$id,$name,$section,$time $timePeriod\n";
    file_put_contents($filename, $entry, FILE_APPEND | LOCK_EX);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>
