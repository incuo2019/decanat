<?php

use App\Models\Appeal;
use App\Models\Certificate;
use App\Models\Characteristic;
use App\Models\Document;
use App\Models\DocumentClass;
use App\Models\Sheet;
use App\System\Auth;
use App\System\System;

$certificates_count = Certificate::getCountByStatusId(1);
$characteristics_count = Characteristic::getCountByStatusId(1);
$sheets_count = Sheet::getCountByStatusId(1);

$is_auth = Auth::isAuth();
$is_admin = Auth::isAdmin();
$is_moderator = Auth::isModerator();
$access_level = Auth::getAccessLevel();
?>

<header id="header">
    <nav class="navbar navbar-expand-lg <?php echo ($is_moderator) ? 'navbar-dark bg-danger' : 'navbar-light bg-light'; ?> bg-gradient border-bottom">
        <div class="container-fluid">
            <div class="mx-3">
                <a class="navbar-brand" href="/">
                    <img src="<?php echo ($is_moderator) ? '/admin-logo.svg' : '/logo.svg' ?>" alt="" width="30" height="30" class="me-1 d-inline-block align-text-top">
                    <?php global $app_settings;
                    echo $app_settings['project_name']; ?></a>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse text-center justify-content-end" id="navbarSupportedContent">
                <div class="">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <?php
                        $current_url = $_SERVER['REQUEST_URI'];

                        if ($is_moderator) {
                        ?>
                            <li class="nav-item mx-1 my-1">
                                <a class="nav-link text-white <?php if (stripos($current_url, 'certificates')) echo 'active' ?>" href="/certificates/">
                                    Справки
                                    <span class="badge rounded-pill bg-light text-dark"><?php echo $certificates_count; ?></span>
                                </a>
                            </li>
                            <li class="nav-item mx-1 my-1">
                                <a class="nav-link text-white <?php if (stripos($current_url, 'characteristics')) echo 'active' ?>" href="/characteristics/">
                                    Характеристики
                                    <span class="badge rounded-pill bg-light text-dark"><?php echo $characteristics_count; ?></span>
                                </a>
                            </li>
                            <li class="nav-item mx-1 my-1">
                                <a class="nav-link text-white <?php if (stripos($current_url, 'sheets')) echo 'active' ?>" href="/sheets/">
                                    Инд. экз. ведомости
                                    <span class="badge rounded-pill bg-light text-dark"><?php echo $sheets_count; ?></span>
                                </a>
                            </li>
                            <li class="nav-item mx-1 my-1">
                                <a class="nav-link text-white <?php if (stripos($current_url, 'curriculum')) echo 'active' ?>" href="/curriculum/">
                                    Учебный план
                                </a>
                            </li>
                        <?php
                        }
                        if ($is_admin) {
                        ?>
                            <li class="nav-item mx-1 my-1">
                                <a class="nav-link text-white <?php if (stripos($current_url, 'settings')) echo 'active' ?>" href="/admin/settings">Настройки системы</a>
                            </li>
                        <?php
                        }
                        if (!$is_moderator && !$is_admin) {
                            $current_url = $_SERVER['REQUEST_URI'];
                        ?>
                            <li class="nav-item mx-1 my-1">
                                <a class="nav-link <?php if (stripos($current_url, 'main') || ($current_url == '/')) echo 'active' ?>" href="/">Главная</a>
                            </li>
                            <li class="nav-item dropdown mx-1 my-1">
                                <?php if ($is_auth) { ?>
                                    <div class="btn-group">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Сервисы
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdownMenuLink">
                                            <li><a class="dropdown-item" href="/appeals">Мои обращения</a></li>
                                            <li><a class="dropdown-item" href="/certificates">Справки об обучении</a></li>
                                            <li><a class="dropdown-item" href="/sheets">Инд. экз. ведомости</a></li>
                                            <li><a class="dropdown-item" href="/characteristics">Характеристики</a></li>
                                        </ul>
                                    </div>
                                <?php } else { ?>
                                    <a class="nav-link <?php if (stripos($current_url, 'services')) echo 'active' ?>" href="/services">Сервисы</a>
                                <?php } ?>
                            </li>
                            <li class="nav-item mx-1 my-1">
                                <a class="nav-link <?php if (stripos($current_url, 'informations')) echo 'active' ?>" href="/informations/">Информация</a>
                            </li>
                            <li class="nav-item mx-1 my-1">
                                <a class="nav-link <?php if (stripos($current_url, 'contacts')) echo 'active' ?>" href="/contacts/">Контакты</a>
                            </li>
                            <li class="nav-item mx-1 my-1">
                                <a class="nav-link <?php if (stripos($current_url, 'help')) echo 'active' ?>" href="/help/">Помощь</a>
                            </li>
                        <?php
                        }
                        ?>

                        <?php
                        if ($is_auth) {
                        ?>
                            <li class="nav-item dropdown ms-3 my-1">
                                <a class="btn <?php echo ($is_moderator) ? 'btn-outline-light' : 'btn-outline-secondary'; ?> dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Аккаунт
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="/appeals/"><?php echo $is_moderator ? 'Новые обращения' : 'Мои обращения' ?></a></li>
                                    <li><a class="dropdown-item" href="/users/edit/<?php echo Auth::getId(); ?>">Настройки аккаунта</a></li>
                                    <?php
                                    if ($access_level > 0) {
                                    ?>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                    <?php
                                    }
                                    ?>

                                    <?php
                                    if ($access_level == 1) {
                                        if ($is_moderator) {
                                    ?>
                                            <li><a class="dropdown-item" href="/admin/">Режим Пользователя</a></li>
                                        <?php
                                        } else {
                                        ?>
                                            <li><a class="dropdown-item" href="/admin/">Режим Модератора</a></li>
                                        <?php
                                        }
                                        ?>
                                    <?php
                                    }
                                    ?>

                                    <?php
                                    if ($access_level == 2) {
                                        if ($is_admin) {
                                    ?>
                                            <li><a class="dropdown-item" href="/admin/">Режим Пользователя</a></li>
                                        <?php
                                        } else {
                                        ?>
                                            <li><a class="dropdown-item" href="/admin/">Режим Администратора</a></li>
                                        <?php
                                        }
                                        ?>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </li>
                        <?php
                        }
                        ?>

                        <li class="nav-item mx-3 my-1">
                            <?php
                            if ($is_auth) {
                            ?>
                                <a class="btn <?php echo ($is_moderator) ? 'btn-outline-light' : 'btn-outline-primary'; ?>" href="/auth/logout/">Выйти</a>
                            <?php
                            } else {
                            ?>
                                <a class="btn btn-outline-primary" href="/auth/login">Войти</a>
                            <?php
                            }
                            ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
<div class="my-4"></div>

<div <?php if ($is_flex) { ?> class="d-flex align-items-center flex-wrap justify-content-center" style="min-height: 80vh;" <?php } ?>>