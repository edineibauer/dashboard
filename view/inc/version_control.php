<?php
function updateVersionTxt() {
    $f = fopen(PATH_HOME . "_config/updates/version.txt", "w+");
    fwrite($f, file_get_contents(PATH_HOME . "composer.lock"));
    fclose($f);
    updateDependenciesEntity();

    //Recarrega cache de assets excluindo tudo
    $dir = PATH_HOME . (DEV ? "assetsPublic" : "assets");
    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST) as $file) {
        if(!in_array($file->getFileName(), ["theme.css", "theme"])) {
            if ($file->isDir())
                rmdir($file->getRealPath());
            elseif ($file->getFileName())
                unlink($file->getRealPath());
        }
    }
    header("Location:" . HOME . "dashboard");
}

function updateDependenciesEntity() {
    foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn") as $lib)
        new \EntityForm\EntityImport($lib);
}

if(file_exists(PATH_HOME . "_config/updates/version.txt")) {
    $old = file_get_contents(PATH_HOME . "_config/updates/version.txt");
    $actual = file_get_contents(PATH_HOME . "composer.lock");
    if($old !== $actual) {
        $conf = file_get_contents(PATH_HOME . "_config/config.php");
        $version = explode("')", explode("'VERSION', '", $conf)[1])[0];
        $newVersion = $version + 0.01;
        $conf = str_replace("'VERSION', '{$version}')", "'VERSION', '{$newVersion}')", $conf);
        $f = fopen(PATH_HOME . "_config/config.php", "w");
        fwrite($f, $conf);
        fclose($f);
        updateVersionTxt();
    }

} else {
    \Helpers\Helper::createFolderIfNoExist(PATH_HOME . "_config/updates");
    updateVersionTxt();
}