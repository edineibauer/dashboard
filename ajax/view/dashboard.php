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
                <div class="row" style="padding-bottom:15px">
                    <button id="btn-editLogin"
                            class="right color-white opacity hover-shadow hover-opacity-off btn-floating">
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

        <div class="main color-grey-light dashboard-main space-header">
            <div id="dashboard" class="dashboard-tab container row"></div>
        </div>

        <?php
        $data['data']['content'] = ob_get_contents();
    }
    ob_end_clean();
}