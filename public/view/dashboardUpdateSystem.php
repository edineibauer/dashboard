<?php

$force = filter_input(INPUT_GET, 'force', FILTER_VALIDATE_INT);
if(!empty($force) && $force === 1 && file_exists(PATH_HOME . "_config/updates/version.txt"))
    unlink(PATH_HOME . "_config/updates/version.txt");

$up = new \Dashboard\UpdateDashboard();

$data['response'] = 3;
$data['data'] = HOME;
