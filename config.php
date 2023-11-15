<?php
$configUrl = 'https://pastebin.com/raw/P44Qn5aq'; // 
$configData = file_get_contents($configUrl);

if ($configData === false) {
    die("Failed to retrieve configuration.");
}

$config = json_decode($configData, true);

if ($config === null || !isset($config['encryption_key'])) {
    die("Invalid configuration format or missing encryption key.");
}

define('ENCRYPTION_KEY', $config['encryption_key']);
