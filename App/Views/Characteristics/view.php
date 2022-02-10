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

if (empty($user)) {
    Notification::add('Произошла ошибка (отсутствуют данные)');
}

$date_now = Date("Y-m-d");

$t = strtotime('+1 day 00:00:00');
$min_date = Date('Y-m-d', $t);

$t = strtotime('+7 day 00:00:00');
$max_date = Date('Y-m-d', $t);
?>

<section class="my-5 w-100">
    <div class="container w-100">
        <div class="row">
            <div class="col-md-8">
                <h1>Характеристика об обучении</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 col-md-6 form-group my-3">
                <label for="lastname">Фамилия</label>
                <p class="border-bottom p-2"><strong><?php echo $user->lastname; ?></strong></p>
            </div>

            <div class="col-xl-3 col-md-6 form-group my-3">
                <label for="firstname">Имя</label>
                <p class="border-bottom p-2"><strong><?php echo $user->firstname; ?></strong></p>
            </div>

            <div class="col-xl-3 col-md-6 form-group my-3">
                <label for="middlename">Отчество</label>
                <p class="border-bottom p-2"><strong><?php echo $user->middlename; ?></strong></p>
            </div>

            <div class="col-xl-3 col-md-6 form-group my-3">
                <label for="group_id">Учебная группа</label>
                <p class="border-bottom p-2"><strong><?php echo Group::getById($user->group_id)->name; ?></strong></p>
            </div>

            <div class="col-xl-3 col-md-6 form-group my-3">
                <label for="group_id">Тип характеристики</label>
                <p class="border-bottom p-2"><strong><?php echo DocumentType::getById($characteristic->document_type_id)->name; ?></strong></p>
            </div>

            <div class="col-xl-3 col-md-6 form-group my-3">
                <label for="group_id">Статус</label>
                <p class="border-bottom p-2"><strong><?php echo DocumentStatus::getById($characteristic->status_id)->name; ?></strong></p>
            </div>

            <?php
            if (!empty($first_file_hash)) {
            ?>
                <div class="col-xl-3 col-md-6 form-group my-3">

                    <p class="border-bottom p-2">
                        <label for="file">Файл</label>
                        <br>
                        <strong>
                            <a class="" href="/files/get/<?php echo $first_file_hash ?? ''; ?>">Скачать загруженный документ</a>
                        </strong>
                    </p>
                </div>
            <?php
            }
            if (!empty($second_file_hash)) {
            ?>
                <div class="col-xl-3 col-md-6 form-group my-3">

                    <p class="border-bottom p-2">
                        <label for="file">Файл</label>
                        <br>
                        <strong>
                            <a class="" href="/files/get/<?php echo $second_file_hash ?? ''; ?>">Скачать характеристику</a>
                        </strong>
                    </p>
                </div>
            <?php
            }
            ?>

            <?php
            if (!empty($characteristic->comment)) {
            ?>
                <div id="type_document_block" class="col-lg-12 form-group my-3">
                    <label for="phone">Комментарий модератора</label>
                    <p class="border-bottom p-2"><strong><?php echo $characteristic->comment; ?></strong></p>
                </div>
            <?php
            }
            ?>
            <?php
            if (!Auth::isModerator() && DocumentType::isPaper($characteristic->document_type_id ?? '') && DocumentStatus::isCompleted($characteristic->status_id ?? '')) {
            ?>
                <p class="border-bottom p-2 text-center"><strong>Бумажная версия ждёт вас в деканате <?php echo Department::getById($user->department_id ?? '')->short_name ?? ''; ?> в будний день с 9:00 до 18:00</strong></p>
            <?php
            }
            ?>
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