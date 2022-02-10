<?php

use App\System\System;

?>

</div>
<footer class="page-footer font-small blue bg-white border-top mt-auto position-sticky top-100" style="max-height: 50vh;">
    <?php
    if (System::isDebugMode()) {
    ?>
        <section id="debug">
            <p class="lead text-center text-danger">Обратите внимание, включён режим DEGUB</p>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4">
                        <?php
                        if (!empty($_SESSION)) {
                            echo '<p class="lead">SESSION:</p>';
                            echo '<pre>';
                            foreach ($_SESSION as $key => $session) {
                                if (is_array($session)) {
                                    echo '<p class="text-danger">' . $key . ":</p>";
                                    print_r($session);
                                } else {
                                    echo '<p class="text-danger">' . $key . ":</p>";
                                    var_dump($session);
                                }
                            }
                            echo '</pre>';
                        }
                        ?>
                    </div>
                    <div class="col-lg-4">
                        <p class="lead">GET:</p>
                        <?php
                        echo '<pre>';
                        if (!empty($_GET)) {
                            print_r($_GET);
                        }
                        echo '</pre>';
                        ?>
                    </div>
                    <div class="col-lg-4">
                        <p class="lead">POST:</p>
                        <?php
                        echo '<pre>';
                        if (!empty($_POST)) {
                            print_r($_POST);
                        }
                        echo '</pre>';
                        ?>
                    </div>
                </div>
            </div>
        </section>
    <?php
    }
    ?>
    <div class="text-center py-3">© incuo <?php echo Date("Y"); ?>
    </div>
</footer>