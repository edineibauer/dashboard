<?php

$tpl = new \Helpers\Template("dashboard");
$dados['dominio'] = DEV && DOMINIO === "dashboard" ? "" : "vendor/conn/dashboard/";

$data['data'] = $tpl->getShow('dashboard', $dados);