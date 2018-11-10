<?php

//check if is the first time in the system to clear database
if (!file_exists(PATH_HOME . "entity/cache")) {
    //nenhuma entidade, zera banco
    $sql = new \ConnCrud\SqlCommand();
    $sql->exeCommand("SHOW TABLES");
    if ($sql->getResult()) {
        $sqlDelete = new \ConnCrud\SqlCommand();
        foreach ($sql->getResult() as $item) {
            if (!empty($item['Tables_in_' . DATABASE]))
                $sqlDelete->exeCommand("DROP TABLE IF EXISTS " . $item['Tables_in_' . DATABASE]);
        }
    }
}

$up = new \Dashboard\UpdateDashboard();

$data['response'] = 3;
$data['data'] = HOME;
