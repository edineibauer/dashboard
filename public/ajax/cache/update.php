<?php

unlink(PATH_HOME . "_config/updates/version.txt");
$up = new \Dashboard\UpdateDashboard();
