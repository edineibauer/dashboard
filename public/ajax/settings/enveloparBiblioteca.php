<?php

use Helpers\Helper;

try {
//copia entidades
    if (file_exists(PATH_HOME . "entity/cache")) {

        \Helpers\Helper::createFolderIfNoExist(PATH_HOME . "public/entity");
        \Helpers\Helper::createFolderIfNoExist(PATH_HOME . "public/entity/cache");
        \Helpers\Helper::createFolderIfNoExist(PATH_HOME . "public/entity/cache/info");

        foreach (Helper::listFolder(PATH_HOME . "entity/cache") as $entity) {
            if (preg_match('/\.json$/i', $entity)) {

                //Para cada Entidade
                $isMyEntity = true;
                foreach (Helper::listFolder(PATH_HOME . VENDOR) as $lib) {
                    if ($isMyEntity && file_exists(PATH_HOME . VENDOR . "{$lib}/public/entity/cache/{$entity}"))
                        $isMyEntity = false;
                }

                if ($isMyEntity) {
                    copy(PATH_HOME . "entity/cache/{$entity}", PATH_HOME . "public/entity/cache/{$entity}");
                    copy(PATH_HOME . "entity/cache/info/{$entity}", PATH_HOME . "public/entity/cache/info/{$entity}");
                }
            }
        }
        $data['data'] = "1";
    }
} catch (Exception $e) {

}