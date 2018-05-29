<?php

$up = new \Dashboard\UpdateDashboard();

if($up->getResult()) {
    $data['response'] = 3;
    $data['data'] = HOME . "dashboard";
}