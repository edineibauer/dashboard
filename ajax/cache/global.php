<?php

$dir = PATH_HOME . (DEV ? "assetsPublic" : "assets");
foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::CHILD_FIRST) as $file) {
    if ($file->isDir())
        rmdir($file->getRealPath());
    else
        unlink($file->getRealPath());
}
rmdir($dir);

$data['data'] = "1";