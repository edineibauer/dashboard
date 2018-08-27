<?php
if (!LOGGED) {
    $data['response'] = 3;
    $data['data'] = HOME . "login";
} else {
    ob_start();
    include_once 'inc/version_control.php';
    ?>
    <nav class="sidebar card collapse color-white animate-left dashboard-nav space-header" id="mySidebar"><br>
        <div class="container row">
            <?php
            if (isset($_SESSION['userlogin']['imagem']) && !empty($_SESSION['userlogin']['imagem'])) {
                echo '<div class="col s4"><img src="' . HOME . 'image/' . $_SESSION['userlogin']['imagem'] . '&w=80&h=80" width="72" height="72" style="margin-bottom:0!important; width: 72px;height: 72px" class="card radius-circle margin-right"></div>';
            } else {
                echo '<div class="col s4"><i class="material-icons font-jumbo">people</i></div>';
            }
            ?>

            <div class="col s8 bar">
                <strong class="padding"><?= $_SESSION['userlogin']['email'] ?></strong><br>
                <div class="row padding-medium div-btn-editLogin">
                    <button id="btn-editLogin"
                            class="left color-white opacity z-depth-0 border hover-opacity-off radius padding-small color-grey-light margin-0">
                        <i class="material-icons left font-large">edit</i>
                        <span class="left" style="padding-right: 5px">perfil</span>
                    </button>
                </div>
            </div>
        </div>
        <hr style="margin: 15px 0 0;">
        <div class="bar-block">
            <?php
            require_once 'inc/menu.php';
            ?>
            <br><br>
        </div>
    </nav>

    <div class="main dashboard-main">
        <div id="dashboard" class="dashboard-tab container row"></div>
    </div>

    <?php
    $data['data'] = ob_get_contents();
    ob_end_clean();
}