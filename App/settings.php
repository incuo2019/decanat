<?php 
    return json_decode(file_get_contents(APP . 'settings.json'), JSON_UNESCAPED_UNICODE);
 ?>