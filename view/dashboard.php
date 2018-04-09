<?php
if (!LOGGED)
    header("Location: " . HOME . "login");
?>

<!-- Navbar -->
<div class="top card dashboard-header bar theme-d2 left-align font-large">
    <div class="right padding-tiny hide-small">
        <button onclick="logoutDashboard();"
                class="logout-button right color-white opacity hover-shadow margin-0 hover-opacity-off btn-floating">
            <i class="material-icons color-hover-text-red" style="font-size:0.9em">power_settings_new</i>
        </button>
    </div>
    <button class="open-menu bar-item z-depth-0 button hide-large right padding-large color-hover-white font-large theme-d2"
            href="javascript:void(0);" onclick="open_sidebar()">
        <i class="material-icons">menu</i>
    </button>

    <div class="bar-item theme-d4 padding-0 upper dashboard-header-sidebar">
        <a href="#" class="button upper padding-small dashboard-header-logo">
            <?php
            if (LOGO && !empty(LOGO)) {
                echo '<img src="' . HOME . 'image/' . LOGO . '&h=35" class="left" style="height: 35px; width: auto" height="35" />';

            } else {
                if (FAVICON && !empty(FAVICON))
                    echo '<img src="' . HOME . FAVICON . '" class="left padding-right" style="height: 35px; width: auto" height="35" />';
                else
                    echo '<i class="material-icons left padding-small">home</i>';

                echo '<span class="left padding-small">' . SITENAME . '</span>';
            }
            ?>
        </a>

        <a href="<?= HOME ?>" target="_blank" class="right btn hover-shadow opacity theme-d4 hover-opacity-off"
           style="padding: 10px 15px 5px 15px!important;margin-right: 0;">
            <i class="material-icons">launch</i>
        </a>
    </div>

    <!--
    <a href="#" class="bar-item button hide-small padding-large hover-white" title="News"><i
                class="fa fa-globe"></i></a>
    <a href="#" class="bar-item button hide-small padding-large hover-white" title="Messages"><i
                class="fa fa-envelope"></i></a>
    <div class="dropdown-hover hide-small">
        <button class="button padding-large z-depth-0" title="Notifications">
            <i class="fa fa-bell"></i>
            <span class="badge right small theme-l1 z-depth-2">3</span>
        </button>
        <div class="dropdown-content card-4 bar-block" style="width:300px">
            <a href="#" class="bar-item button">One new friend request</a>
            <a href="#" class="bar-item button">John Doe posted on your wall</a>
            <a href="#" class="bar-item button">Jane likes your post</a>
        </div>
    </div>
    -->
</div>

<!-- Sidebar/menu -->
<nav class="sidebar card collapse color-white animate-left dashboard-nav" id="mySidebar"><br>
    <div class="container row">
        <?php
        if (isset($_SESSION['userlogin']['imagem']) && !empty($_SESSION['userlogin']['imagem'])) {
            echo '<div class="col s4"><img src="' . $_SESSION['userlogin']['imagem'] . '" style="margin-bottom:0!important" class="card radius-small margin-right"></div><div class="col s8 bar">';
        } else {
            echo '<div class="col s12 bar">';
        }
        ?>

        <strong class="padding"><?= $_SESSION['userlogin']['nome'] ?></strong><br>
        <div class="row" style="padding-bottom:15px">
            <button id="btn-editLogin" class="right color-white opacity hover-shadow hover-opacity-off btn-floating">
                <i class="material-icons">edit</i>
            </button>
        </div>
    </div>
    </div>
    <hr>
    <div class="bar-block">
        <?php
        require_once 'inc/menu.php';
        ?>
        <br><br>
    </div>
</nav>

<div class="overlay hide-large animate-opacity" onclick="close_sidebar()" title="close side menu" id="myOverlay"></div>

<div class="main color-grey-light dashboard-main">
    <div id="dashboard" class="dashboard-tab panel row"></div>
</div>