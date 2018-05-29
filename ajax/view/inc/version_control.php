<?php

if(new \Dashboard\UpdateDashboard()) {
    $data['response'] = 3;
    $data['data'] = HOME . "dashboard";
}