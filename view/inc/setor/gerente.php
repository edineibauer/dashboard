<?php

if($_SESSION['userlogin']['nivel'] > 1)
    $restrict = ["login"];
