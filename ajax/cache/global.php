<?php

$dir = PATH_HOME . (DEV ? "assetsPublic" : "assets");
foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::CHILD_FIRST) as $file) {
    if(!in_array($file->getFileName(), ["theme.css", "theme.min.css", "theme"])) {
        if ($file->isDir())
            rmdir($file->getRealPath());
        elseif ($file->getFileName())
            unlink($file->getRealPath());
    }
}

$data['data'] = "1";