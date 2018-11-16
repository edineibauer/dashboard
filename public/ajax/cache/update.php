<?php

try {
    if (file_exists(PATH_HOME . "_config/updates/version.txt"))
        unlink(PATH_HOME . "_config/updates/version.txt");

    $up = new \Dashboard\UpdateDashboard([]);
} catch (Exception $e) {

}