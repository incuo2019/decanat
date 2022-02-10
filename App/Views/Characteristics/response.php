<?php

use App\Models\Department;
use App\Models\DocumentStatus;
use App\Models\DocumentType;
use App\Models\Group;
use App\Models\User;
use App\System\Auth;
use App\System\Notification;
use App\System\Output;
?>

<section class="my-5">
    <div class="container">
        <h1><?php echo $title ?? ''; ?></h1>

        <form class="my-5" method="post" enctype="multipart/form-data">
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
                    <label for="group_id">Тип справки</label>
                    <p class="border-bottom p-2"><strong><?php echo DocumentType::getById($characteristic->document_type_id ?? '')->name ?? ''; ?></strong></p>
                </div>

                <?php if (DocumentType::isPaper($characteristic->document_type_id ?? '')) { ?>
                    <div class="col-xl-3 col-md-6 form-group my-3">
                        <label for="group_id">Дата предзаказа</label>
                        <p class="border-bottom p-2"><strong><?php echo Output::toDate($characteristic->date_preorder ?? '') ?? ''; ?></strong></p>
                    </div>
                    <div class="col-xl-3 col-md-6 form-group my-3">
                        <label for="group_id">Количество</label>
                        <p class="border-bottom p-2"><strong><?php echo $characteristic->count ?? ''; ?></strong></p>
                    </div>
                <?php } ?>

                <div class="col-xl-3 col-md-6 form-group my-3">
                    <label for="group_id">Статус</label>
                    <p class="border-bottom p-2"><strong><?php echo DocumentStatus::getById($characteristic->status_id ?? '')->name ?? ''; ?></strong></p>
                </div>
                
                <?php
                if (!empty($file_hash)) {
                ?>
                    <div class="col-xl-3 col-md-6 form-group my-3">

                        <p class="border-bottom p-2">
                            <label for="file">Файл</label>
                            <br>
                            <strong>
                                <a class="" href="/files/get/<?php echo $file_hash ?? ''; ?>">Скачать загруженный документ</a>
                            </strong>
                        </p>
                    </div>
                <?php
                }
                ?>

                <?php
                if (DocumentType::isDigital($characteristic->document_type_id ?? '')) {
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