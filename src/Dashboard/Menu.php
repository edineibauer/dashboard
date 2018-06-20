<?php

namespace Dashboard;

use ConnCrud\Read;
use EntityForm\Metadados;
use \Helpers\Check;
use Helpers\Helper;
use Helpers\Template;

class Menu
{
    private $notShow;
    private $menu;

    public function __construct()
    {
        $this->menu = [];
        $this->notShow = \Helpers\Check::getMenuNotAllow();
        $this->start();
    }

    /**
     * @return string
     */
    public function getMenu(): string
    {
        $menu = "";
        $tpl = new Template("dashboard");
        $template = (count($this->menu) < 6 ? "menu-card" : "menu-li");
        foreach ($this->menu as $m)
            $menu .= $tpl->getShow($template, $m);

        return $menu;
    }

    public function showMenu()
    {
        echo $this->getMenu();
    }

    private function start()
    {
        $this->geral();
        $this->gerenciarEntidades();
        $this->listEntity();
        $this->listRelationContent();
        $this->custom();
    }

    private function geral()
    {
        $this->menu['geralNotCopy'] = ["icon" => "timeline", "title" => "Dashboard", "action" => "page", "file" => "dash/geral", "lib" => "dashboard"];
    }

    /**
     * Editor de Entidades para Adm
     */
    private function gerenciarEntidades()
    {
        if ($_SESSION['userlogin']['setor'] === '1')
            $this->menu[] = ["icon" => "accessibility", "title" => "Gerenciar Entidades", "action" => "link", "link" => HOME . "entidades"];
    }

    private function listRelationContent()
    {
        foreach (Helper::listFolder(PATH_HOME . "entity/cache") as $item) {
            if (preg_match('/\.json$/i', $item) && $item !== "login_attempt.json") {
                $entity = str_replace('.json', '', $item);
                $metadados = Metadados::getDicionario($entity);
                foreach ($metadados as $id => $dic) {
                    if ($dic['relation'] === "usuarios" && in_array($dic['format'], ['extend', 'list', 'selecao'])) {
                        $this->getMenuListRelationContent($entity, $metadados, $id);
                        break;
                    }
                }
            }
        }
    }

    private function getMenuListRelationContent(string $entity, array $metadados, int $id)
    {
        $read = new Read();
        $read->exeRead($entity, "WHERE {$metadados[$id]['column']} = :ui", "ui={$_SESSION['userlogin']['id']}");
        if ($read->getResult()) {
            //            $idU = $read->getResult()[0]['id'];
            if ($metadados[$id]['format'] === "extend") {
                // único linkamento, é parte desta entidade (busca seus dados relacionados)

                foreach ($metadados as $metadado) {
                    if ($metadado['format'] === 'extend_mult') {
                        //table owner (exibe tabela com os registros linkados apenas)
                        $this->menu[$metadado['relation']] = [
                            "icon" => "storage",
                            "title" => $metadado['nome'],
                            "action" => "table",
                            "entity" => $metadado['relation']
                        ];

                    } elseif ($metadado['format'] === 'list_mult') {
                        //table publisher (exibe tabela com todos os registros, mas só permite editar os linkados)

                    } elseif ($metadado['format'] === 'selecao_mult') {
                        //form para ediçaõ das seleções apenas

                    } elseif ($metadado['format'] === 'extend') {
                        //form para edição do registro único (endereço por exemplo)

                    } elseif ($metadado['format'] === 'list') {
                    }
                }

            } else {
                // multiplos linkamentos, se relaciona ocm a entidade (pode ser autor)

            }
        }
    }

    /**
     * Opção para cada entidade
     */
    private function listEntity()
    {
        foreach (Helper::listFolder(PATH_HOME . "entity/cache") as $item) {
            $entity = str_replace('.json', '', $item);
            if (!isset($this->menu[$entity]) && (empty($this->notShow[$_SESSION['userlogin']['setor']]) || !in_array($entity, $this->notShow[$_SESSION['userlogin']['setor']])) && preg_match('/\.json$/i', $item) && $item !== "login_attempt.json" && $item !== "info")
                $this->menu[$entity] = ["icon" => "account_balance_wallet", "title" => ucwords(trim(str_replace(['-', '_'], [' ', ' '], $entity))), "action" => "table", "entity" => $entity];
        }
    }

    /**
     * Menu Customizado Extra
     */
    private function custom()
    {
        foreach (Helper::listFolder(PATH_HOME . "vendor/conn") as $lib)
            $this->customMenuCheck(PATH_HOME . "vendor/conn/{$lib}/dashboard/menu.json");

        if (DEV)
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
     */
    private function addMenuNotShow(string $menuDir)
    {
        foreach (Helper::listFolder($menuDir . "entity/menu") as $menu) {
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
    }

    /**
     * Mostra Menu
     * @param array $incMenu
     */
    private function showMenuOption(array $incMenu)
    {
        if (!empty($incMenu)) {
            foreach ($incMenu as $menu) {
                if (empty($menu['setor']) || $menu['setor'] >= $_SESSION['userlogin']['setor']) {
                    $this->menu[] = [
                        'lib' => Check::words(trim(strip_tags($menu['lib'])), 1),
                        'file' => Check::words(trim(strip_tags($menu['file'])), 1),
                        'action' => "page",
                        'title' => ucwords(Check::words(trim(strip_tags($menu['title'])), 3)),
                        'icon' => Check::words(trim(strip_tags($menu['icon'])), 1)
                    ];
                }
            }
        }
    }
}