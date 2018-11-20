<?php
$setor = filter_input(INPUT_POST, 'session', FILTER_VALIDATE_INT);
$entity = trim(strip_tags(filter_input(INPUT_POST, 'entity', FILTER_DEFAULT)));
$action = filter_input(INPUT_POST, 'action', FILTER_VALIDATE_BOOLEAN);
$fileName = PATH_HOME . "public/dash/-menu.json";
$file = [];
if (file_exists($fileName))
    $file = json_decode(file_get_contents($fileName), true);

if ($action) {
    if (!isset($file[$setor]) || !in_array($entity, $file[$setor]))
        $file[$setor][] = $entity;

} elseif (in_array($entity, $file[$setor])) {
    $file[$setor] = array_diff($file[$setor], [$entity]);
}

\Helpers\Helper::createFolderIfNoExist(PATH_HOME . "public/dash");
$f = fopen($fileName, "w");
fwrite($f, json_encode($file));
fclose($f);
