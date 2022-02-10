<?php

use App\Models\Department;
use App\Models\DocumentStatus;
use App\Models\DocumentType;
use App\Models\Files;
use App\Models\Group;
use App\Models\User;
use App\System\Auth;
use App\System\Notification;
use App\System\Output;

?>

<section class="my-5 w-100">
    <div class="container w-100">
        <h1><?php echo $title ?? ''; ?></h1>

        <div class="row">
            <div class="col-xl-3 col-md-6 form-group my-3">
                <label for="lastname">Фамилия</label>
                <p class="border-bottom p-2"><strong><?php echo $user->lastname ?? ''; ?></strong></p>
            </div>

            <div class="col-xl-3 col-md-6 form-group my-3">
                <label for="firstname">Имя</label>
                <p class="border-bottom p-2"><strong><?php echo $user->firstname ?? ''; ?></strong></p>
            </div>

            <div class="col-xl-3 col-md-6 form-group my-3">
                <label for="middlename">Отчество</label>
                <p class="border-bottom p-2"><strong><?php echo $user->middlename ?? ''; ?></strong></p>
            </div>

            <div class="col-xl-3 col-md-6 form-group my-3">
                <label for="group_id">Учебная группа</label>
                <p class="border-bottom p-2"><strong><?php echo Group::getById($user->group_id ?? '')->name ?? ''; ?></strong></p>
            </div>

            <div class="col-xl-3 col-md-6 form-group my-3">
                <label for="group_id">Учебная дисциплина</label>
                <p class="border-bottom p-2"><strong><?php echo $subject->name ?? ''; ?></strong></p>
            </div>

            <div class="col-xl-3 col-md-6 form-group my-3">
                <label for="group_id">Дата предзаказа</label>
                <p class="border-bottom p-2"><strong><?php echo Output::toDate($sheet->date_preorder ?? '') ?? ''; ?></strong></p>
            </div>

            <div class="col-xl-3 col-md-6 form-group my-3">
                <label for="group_id">Статус</label>
                <p class="border-bottom p-2"><strong><?php echo DocumentStatus::getById($sheet->status_id ?? '')->name ?? ''; ?></strong></p>
            </div>

            <?php
            if (!empty($sheet->comment ?? '')) {
            ?>
                <div id="type_document_block" class="col-lg-12 form-group my-3">
                    <label for="phone">Комментарий модератора</label>
                    <p class="border-bottom p-2"><strong><?php echo $sheet->comment ?? ''; ?></strong></p>
                </div>
            <?php
            }
            ?>
            <p class="border-bottom p-2 text-center"><strong>Индивидуальная экзаменационная ведомость ждёт вас в деканате <?php echo Department::getById($user->department_id ?? '')->short_name ?? ''; ?> в будний день с 9:00 до 18:00</strong></p>
        </div>
    </div>
</section>

<script>
    document.querySelector("#file").addEventListener("change", function() {
        if ($("#file").val()) {
            $('#error').addClass('d-none');
            $('#comment').removeClass('is-invalid');
        }
    });

    document.querySelector("#comment").addEventListener("change", function() {
        if ($("#comment").val()) {
            $('#error').addClass('d-none');
            $('#comment').removeClass('is-invalid');
        }
    });

    document.querySelector("form").addEventListener("submit", function(event) {
        if (!$('#file').val() && !$('#comment').val()) {
            $('#error').removeClass('d-none');
            $('#comment').addClass('is-invalid');
            event.preventDefault();
        }
    });
</script>