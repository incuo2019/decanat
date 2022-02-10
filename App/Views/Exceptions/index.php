<?php

use App\System\System;

?>
<h1>
    Exceptions index
</h1>
<?php
if (System::isDebugMode()) {
    if (!empty($this->description)) {
        echo '<pre>';
        print_r($this->description);
        echo '</pre>';
    }
}
?>