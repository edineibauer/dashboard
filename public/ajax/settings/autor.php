<?php

$autor = filter_input(INPUT_POST, 'autor', FILTER_VALIDATE_INT);

foreach (\Helpers\Helper::listFolder(PATH_HOME . "entity/cache") as $item) {
    if($item !== "info" && preg_match('/.json$/i', $item)) {
        $entity = str_replace('.json', "", $item);
        $info = \Entity\Metadados::getInfo($entity);
        if(!empty($info['publisher'])) {
            $dic = \Entity\Metadados::getDicionario($entity);
            $field = $dic[$info['publisher']]['column'];
            $up = new \ConnCrud\Update();
            $up->exeUpdate(PRE . $entity, [$field => $autor], "WHERE {$field} IS NULL && id > :fi", "fi=0");
        }
    }
}