<?php
$form = new \FormCrud\Form("usuarios");
$data['data'] = $form->getForm($_SESSION['userlogin']['id'], ["nome", "nome_usuario", "email", "imagem", "password"]);