<?php

if ($_SESSION['userlogin']['nivel'] === '1')
    echo "<a href='" . HOME . "entidades' target='_blank' class='btn-entity hover-theme bar-item button z-depth-0 padding'><i class='material-icons left padding-right'>accessibility</i><span class='left'>Gerenciar Entidades</span></a>";