<?php

use App\Models\Department;
use App\Models\DocumentType;
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

<section class="my-5">
    <div class="container">
        <h1>Обработка заказа характеристики</h1>

        <form class="my-5" method="post" enctype="multipart/form-data">
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

                <div class="col-md-12 form-group my-3">
                    <label for="group_id">Тип справки</label>
                    <p class="border-bottom p-2"><strong><?php echo DocumentType::getById($characteristic->document_type_id)->name; ?></strong></p>
                </div>

                <?php
                if ($characteristic->document_type_id != 2) {
                ?>
                    <div id="file_block" class="col-lg-12 form-group my-3">
                        <label for="phone">Файл</label>
                        <br>
                        <input type="file" class="form-control-file mt-2" name="file" id="file" placeholder="Выберите файл">
                    </div>
                <?php
                }
                ?>

                <div id="type_document_block" class="col-lg-12 form-group my-3">
                    <label for="phone">Комментарий</label>
                    <textarea class="form-control mt-2" name="comment" id="comment" cols="3"></textarea>
                </div>
            </div>

            <div id="error" class="form-group py-3 d-none">
                <p class="text-danger">Выберите файл или оставьте комментарий</p>
            </div>

            <div class="form-group my-3">
                <button class="btn btn-success" type="submit">Обработать</button>
            </div>
        </form>
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