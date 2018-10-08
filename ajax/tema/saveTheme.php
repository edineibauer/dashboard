<?php

use MatthiasMullie\Minify;

$txt = trim(strip_tags(filter_input(INPUT_POST, "txt", FILTER_DEFAULT)));

//Cria backup
copy(PATH_HOME . "assetsPublic/theme.min.css", PATH_HOME . "assetsPublic/theme-recovery.min.css");

//Cria novo theme
$mini = new Minify\CSS($txt);
$mini->minify(PATH_HOME . "assetsPublic/theme.min.css");

new \Dashboard\UpdateDashboard(['assets']);

$data['data'] = "ok";