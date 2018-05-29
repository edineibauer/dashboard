<?php
if (!LOGGED) {
    $data['response'] = 3;
    $data['data'] = HOME . "login";
} else {
    ob_start();
    include_once 'inc/version_control.php';
    if($data['response'] === 1) {
        ?>
        <nav class="sidebar card collapse color-white animate-left dashboard-nav space-header" id="mySidebar"><br>
            <div class="container row">
                <?php
                if (isset($_SESSION['userlogin']['imagem']) && !empty($_SESSION['userlogin']['imagem'])) {
                    echo '<div class="col s4"><img src="' . $_SESSION['userlogin']['imagem'] . '" style="margin-bottom:0!important" class="card radius-small margin-right"></div><div class="col s8 bar">';
                } else {
                    echo '<div class="col s12 bar">';
                }
                ?>

                <strong class="padding"><?= $_SESSION['userlogin']['nome'] ?></strong><br>
                <div class="row padding-medium div-btn-editLogin">
                    <button id="btn-editLogin"
                            class="left color-white opacity hover-shadow hover-opacity-off btn color-grey-light margin-0">
                        <i class="material-icons left font-large">edit</i>
                        <span class="left">editar</span>
                    </button>
                </div>
            </div>
            </div>
            <hr style="margin: 30px 0 0;">
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
        $data['data']['content'] = ob_get_contents();
    }
    ob_end_clean();
}