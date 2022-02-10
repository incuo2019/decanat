<?php

namespace App\System;

class View
{
    protected array $fields = [];
    protected array $sections = [];

    public function __set($key, $value)
    {
        $this->fields[$key] = $value;
    }

    public function __get($key)
    {
        return $this->fields[$key] ?? null;
    }

    public function __isset($key)
    {
        return isset($this->fields[$key]);
    }

    public function addSection($section)
    {
        $template_path = APP . 'Views/' . $section . '.php';
        array_push($this->sections, $template_path);
    }

    public function view($template = null)
    {
        if (!empty($template)) {
            $template_path = APP . 'Views/' . $template . '.php';

            if (!file_exists($template_path)) {
                //TODO redirect
                echo "Error views <br>";
                echo '<pre>';
                print_r($template_path);
                echo '</pre>';
                exit;
                (new View())->view('Exceptions/403');
                exit;
            }
        }

        foreach ($this->fields as $key => $value) {
            ${$key} = $value;
        }

        if (!isset($is_flex)) {
            $is_flex = true;
        }

        global $app_settings;
        require_once(APP . 'Views/Base/header.php');
        if ($app_settings['is_enabled'] == true) {
            require_once(APP . 'Views/Base/page_header.php');
        }

        if (!empty($template)) {
            require_once($template_path);
        }

        if (!empty($this->sections)) {
            foreach ($this->sections as $section) {
                if (file_exists($section)) {
                    require_once($section);
                }
            }
        }

        require_once(APP . 'Views/Base/page_footer.php');
        if ($app_settings['is_enabled'] == true) {
            require_once(APP . 'Views/Base/footer.php');
        }
    }
}
