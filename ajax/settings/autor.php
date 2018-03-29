<?php

$autor = filter_input(INPUT_POST, 'autor', FILTER_VALIDATE_INT);

foreach (\Helpers\Helper::listFolder(PATH_HOME . "entity/cache") as $item) {
    if($item !== "info" && preg_match('/.json$/i', $item)) {
        $entity = str_replace('.json', "", $item);
        $info = \EntityForm\Metadados::getInfo($entity);
        if(!empty($info['publisher'])) {
            $dic = \EntityForm\Metadados::getDicionario($entity);
            $field = $dic[$info['publisher']]['column'];
            $null = NULL;
            $up = new \ConnCrud\Update();
            $up->exeUpdate(PRE . $entity, [$field => $autor], "WHERE {$field} = :fi", "fi={$null}");
        }
    }
}