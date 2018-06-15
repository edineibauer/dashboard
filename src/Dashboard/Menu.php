<?php

namespace Dashboard;

use \Helpers\Check;

class Menu
{
    private $result = "";
    private $notShow;
    
    public function __construct()
    {
        $setor = $_SESSION['userlogin']['setor'];
        $tpl = new \Helpers\Template("dashboard");
        $this->readMenuNotShow();
        
        $this->start();
    }
    
    public function show() {
        $this->result .= $this->result;
    }

    private function start()
    {
        $this->geral();
        $this->gerenciarEntidades();
        $this->listEntity();
        $this->custom();
    }

    private function geral()
    {
        $this->result .= $tpl->getShow("menu-li", ["icon" => "timeline", "title" => "Dashboard", "file" => "dash/geral", "lib" => "dashboard"]);
    }

    /**
     * Editor de Entidades para Adm
     */
    private function gerenciarEntidades()
    {
        if ($setor === '1')
            $this->result .= "<a href='" . HOME . "entidades' target='_blank' class='btn-entity hover-theme bar-item button z-depth-0 padding'><i class='material-icons left padding-right'>accessibility</i><span class='left'>Gerenciar Entidades</span></a>";
    }

    /**
     * Opção para cada entidade
     */
    private function listEntity()
    {
        foreach (\Helpers\Helper::listFolder(PATH_HOME . "entity/cache") as $item) {
            $entity = str_replace('.json', '', $item);
            if ((empty($this->notShow[$setor]) || !in_array($entity, $this->notShow[$setor])) && preg_match('/\.json$/i', $item) && $item !== "login_attempt.json" && $item !== "info") {
                $dados['lib'] = "";
                $dados['file'] = $entity;
                $dados['icon'] = 'account_balance_wallet';
                $dados['title'] = ucwords(trim(str_replace(['-', '_'], [' ', ' '], $entity)));
                $this->result .= $tpl->getShow("menu-li", $dados);
            }
        }
    }

    /**
     * Menu Customizado Extra
     */
    private function custom()
    {
        foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn") as $lib)
            $this->customMenuCheck(PATH_HOME . "vendor/conn/{$lib}/dashboard/menu.json");

        if(DEV)
            $this->customMenuCheck(PATH_HOME . "dashboard/menu.json");
    }

    /**
     * ############################
     * Acessos Privados de Methodos
     * ############################
     */

    /**
     * @param string $dir
     */
    private function customMenuCheck(string $dir)
    {
        if (file_exists($dir)) {
            $incMenu = json_decode(file_get_contents($dir), true);
            $this->showMenuOption($incMenu);
        }
    }

    /**
     * @param string $menuDir
     * @return array
     */
    private function addMenuNotShow(string $menuDir): array
    {
        foreach (\Helpers\Helper::listFolder($menuDir . "entity/menu") as $menu) {
            $m = json_decode(file_get_contents($menuDir . "entity/menu/{$menu}"), true);
            foreach (["*", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10"] as $nivel) {
                if (!empty($m[$nivel])) {
                    foreach ($m[$nivel] as $entity) {
                        if (file_exists($menuDir . "entity/cache/{$entity}.json")) {
                            if ($nivel === "*") {
                                for ($i = 1; $i < 10; $i++)
                                    $this->notShow[$i][] = $entity;
                            } else {
                                $this->notShow[$nivel][] = $entity;
                            }
                        }
                    }
                }
            }
        }

        return $this->notShow;
    }

    /**
     * Retorna as Entidades que não devem aparecer no menu
     * @return array
     */
    private function readMenuNotShow() :array
    {
        $this->notShow = ["1" => [], "2" => [], "3" => [], "4" => [], "5" => [], "6" => [], "7" => [], "8" => [], "9" => [], "10" => []];
        if (DEV && file_exists(PATH_HOME . "entity/menu"))
            $this->notShow = $this->addMenuNotShow(PATH_HOME, $this->notShow);

        foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn") as $lib) {
            if (file_exists(PATH_HOME . "vendor/conn/{$lib}/entity/menu"))
                $this->notShow = $this->addMenuNotShow(PATH_HOME . "vendor/conn/{$lib}/", $this->notShow);
        }
    }

    /**
     * Mostra Menu
     * @param array $incMenu
     */
    private function showMenuOption(array $incMenu) {
        if(!empty($incMenu)){
            $tpl = new \Helpers\Template("dashboard");
            foreach ($incMenu as $menu) {
                if(empty($menu['setor']) || $menu['setor'] >= $_SESSION['userlogin']['setor']) {
                    $menu = [
                        'lib' => Check::words(trim(strip_tags($menu['lib'])), 1),
                        'file' => Check::words(trim(strip_tags($menu['file'])), 1),
                        'title' => ucwords(Check::words(trim(strip_tags($menu['title'])), 3)),
                        'icon' => Check::words(trim(strip_tags($menu['icon'])), 1)
                    ];

                    $this->result .= $tpl->getShow("menu-li", $menu);
                }
            }
        }
    }
}