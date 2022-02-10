<!DOCTYPE html>
<html lang="ru">

<?php

use App\System\Auth;
use App\System\Notification;
use App\System\System;

?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Цифровой деканат<?php if (isset($title)) echo ' - ' . $title; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/css/datepicker.min.css" integrity="sha512-Ujn3LMQ8mHWqy7EPP32eqGKBhBU8v39JRIfCer4nTZqlsSZIwy5g3Wz9SaZrd6pp3vmjI34yyzguZ2KQ66CLSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Resources/main.css">
    <link rel="icon" href="<?php echo (Auth::isModerator()) ? '/admin-logo.svg' : '/logo.svg' ?>">
</head>

<body class="min-vh-100">
    <?php
    $notifications = [];
    if (System::isDebugMode()) {
        Notification::add('Обратите внимание, включён режим DEGUB');
    }
    ?>
    <?php if ($notifications = Notification::get()) { ?>
        <div class="position-fixed bottom-0 end-0 m-3" style="z-index: 99">
            <?php
            foreach ($notifications as $notification) {
            ?>
                <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header bg-primary text-white">
                        <strong class="me-auto">Уведомление</strong>
                        <button type="button" class="btn-close btn-close-white text-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        <strong class="me-auto"><?php echo $notification; ?></strong>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    <?php } ?>