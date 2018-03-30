<?php

$session = filter_input(INPUT_POST, 'session', FILTER_VALIDATE_INT);
$entity = trim(strip_tags(filter_input(INPUT_POST, 'entity', FILTER_DEFAULT)));
$action = filter_input(INPUT_POST, 'action', FILTER_VALIDATE_BOOLEAN);
$fileName = PATH_HOME . "_config/" . ($session === 0 ? "create_entity_allow_anonimos.json" : "create_entity_not_allow_logged.json");

if (file_exists($fileName)) {
    $file = json_decode(file_get_contents($fileName), true);
    if ($action) {
        if ($session === 0) {
            if (!in_array($entity, $file))
                $file[] = $entity;
        } else {
            if(empty($file[$session]))
                $file[$session] = [];

            if (!in_array($entity, $file[$session]))
                $file[$session][] = $entity;
        }
    } else {
        if ($session === 0) {
            foreach ($file as $i => $v) {
                if($v === $entity) {
                    unset($file[$i]);
                    break;
                }
            }
            sort($file);
        } else {
            foreach ($file[$session] as $i => $v) {
                if($v === $entity) {
                    unset($file[$session][$i]);
                    break;
                }
            }
            sort($file[$session]);
        }
    }

    $f = fopen($fileName, "w+");
    fwrite($f, json_encode($file));
    fclose($f);

} elseif ($action) {
    $f = fopen($fileName, "w+");
    if ($session === 0)
        fwrite($f, '["' . $entity . '"]');
    else
        fwrite($f, '{"' . $session . '": ["' . $entity . '"]}');
    fclose($f);
}