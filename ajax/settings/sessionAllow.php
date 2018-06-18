<?php
$setor = filter_input(INPUT_POST, 'session', FILTER_VALIDATE_INT);
$entity = trim(strip_tags(filter_input(INPUT_POST, 'entity', FILTER_DEFAULT)));
$action = filter_input(INPUT_POST, 'action', FILTER_VALIDATE_BOOLEAN);
$fileName = PATH_HOME . "_config/entity_not_show.json";
$file = [];
if (file_exists($fileName))
    $file = json_decode(file_get_contents($fileName), true);

if($action) {
    if (!in_array($entity, $file[$setor]))
        $file[$setor][] = $entity;
} else {
    if(in_array($entity, $file[$setor]))
        $file[$setor] = array_diff($file[$setor], [$entity]);
}

$f = fopen($fileName, "w");
fwrite($f, json_encode($file));
fclose($f);