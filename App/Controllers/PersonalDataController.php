<?php

namespace App\Controllers;

use App\System\View;

class PersonalDataController
{
    public function __invoke()
    {
    }

    public function consent()
    {
        $file_path = APP . 'Files/consent_of_persanal_data.pdf';
        if (file_exists($file_path)) {
            header('Content-type:application/pdf');
            header('Content-disposition: inline; filename="' . $file_path . '"');
            header('content-Transfer-Encoding:binary');
            header('Accept-Ranges:bytes');
            ob_clean();
            flush();
            readfile($file_path);
            exit;
        }
        $view = new View();
        $view->view('Exceptions/404');
    }
}
