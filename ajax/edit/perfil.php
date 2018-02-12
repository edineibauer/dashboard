<?php
$form = new \FormCrud\Form("login");
$data['data'] = $form->getForm($_SESSION['userlogin']['id'], ["nome", "nome_usuario", "email", "imagem", "password"]);