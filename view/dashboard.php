<?php
if (!LOGGED)
    header("Location: " . HOME . "login");
?>

<!-- Navbar -->
<div class="top card" style="z-index: 3">
    <div class="bar theme-d2 left-align font-large">

        <div class="right padding-tiny">
            <button onclick="logoutDashboard();" style="height: 38px;width: 38px; margin:1px 10px 0 0!important" class="right color-white opacity hover-shadow margin-0 hover-opacity-off btn-floating">
                <i class="material-icons color-hover-text-red" style="font-size:0.9em">power_settings_new</i>
            </button>
        </div>
        <button class="bar-item z-depth-0 button hide-large right padding-large color-hover-white font-large theme-d2"
                href="javascript:void(0);" onclick="w3_open()">
            <i class="fa fa-bars"></i>
        </button>

        <div class="bar-item theme-d4 padding-0 upper" style="width: 300px;">
            <a href="#" class="button upper padding-small">
                <?php
                if (LOGO && !empty(LOGO)) {
                    echo '<img src="' . LOGO . '" class="left" style="height: 35px; width: auto" height="35" />';

                } else {
                    if (FAVICON && !empty(FAVICON))
                        echo '<img src="' . FAVICON . '" class="left padding-right" style="height: 35px; width: auto" height="35" />';
                    else
                        echo '<i class="material-icons left padding-small">home</i>';

                    echo '<span class="left padding-small">' . SITENAME . '</span>';
                }
                ?>
            </a>

            <a href="<?=HOME?>" target="_blank" class="right btn hover-shadow opacity theme-d4 hover-opacity-off" style="padding: 10px 15px 5px 15px!important;margin-right: 0;">
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
</div>

<!-- Sidebar/menu -->
<nav class="sidebar card collapse color-white animate-left" style="z-index:3;width:300px;top: 47px;" id="mySidebar"><br>
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
        <a href="#" class="bar-item button padding-16 hide-large dark-grey color-hover-black" onclick="w3_close()"
           title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
        <button id="btn-geral" class="bar-item hover-theme button z-depth-0 padding"><i
                    class="material-icons left padding-right">timeline</i><span
                    class="left">Geral</span></button>

        <?php
        switch ($_SESSION['userlogin']['setor']) {
            case 1:
                include_once 'inc/setor/admin.php';
                break;
            case 2:
                include_once 'inc/setor/gerente.php';
                break;
            default:
                include_once 'inc/setor/usuario.php';
        }

        require_once 'inc/menu.php';
        ?>

        <button id="btn-settings" class="bar-item hover-theme button z-depth-0 padding"><i
                    class="material-icons left padding-right">settings</i><span
                    class="left">Settings</span></button>
        <br><br>
    </div>
</nav>


<!-- Overlay effect when opening sidebar on small screens -->
<div class="overlay hide-large animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu"
     id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="main color-grey-light" style="margin-left:300px;margin-top:47px;">

    <div id="dashboard" class="dashboard-tab panel row">
        <?php include_once 'inc/geral.php' ?>
    </div>

    <div id="entity" class="dashboard-tab panel row hide">

    </div>
</div>

<script>
    // Get the Sidebar
    var mySidebar = document.getElementById("mySidebar");

    // Get the DIV with overlay effect
    var overlayBg = document.getElementById("myOverlay");

    // Toggle between showing and hiding the sidebar, and add overlay effect
    function w3_open() {
        if (mySidebar.style.display === 'block') {
            mySidebar.style.display = 'none';
            overlayBg.style.display = "none";
        } else {
            mySidebar.style.display = 'block';
            overlayBg.style.display = "block";
        }
    }

    // Close the sidebar with the close button
    function w3_close() {
        mySidebar.style.display = "none";
        overlayBg.style.display = "none";
    }
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">