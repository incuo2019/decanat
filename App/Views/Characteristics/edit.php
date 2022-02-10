<?php

use App\Models\Department;
use App\Models\DocumentType;
use App\Models\Group;
use App\Models\User;
use App\System\Auth;
use App\System\Notification;
use App\System\Output;
?>

<section class="my-5">
    <div class="container">
        <?php if (isset($is_new) && $is_new == true) { ?>
            <div class="row">
                <div class="col-md-6">
                    <h1><?php echo $title ?? ''; ?></h1>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="my-3">
                        <a class="text-decoration-none text-secondary" href="/characteristics/templates" target="_blank"><p class="fs-5">Перейти к шаблонам характеристик</p></a>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <h1><?php echo $title ?? ''; ?></h1>
        <?php } ?>

        <form class="my-5 was-validated" method="post" enctype="multipart/form-data">
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
                    <p class="border-bottom p-2"><strong><?php echo Group::getById($user->group_id ?? 0)->name ?? ''; ?></strong></p>
                </div>

                <div id="type_document_block" class="col-lg-4 form-group my-3">
                    <label for="phone">Тип документа</label>
                    <select class="form-control" name="document_type_id" id="document_type_id" required>
                        <?php if (!empty($document_type_list = DocumentType::getAll())) {
                            foreach ($document_type_list as $document_type) { ?>
                                <option value='<?php echo $document_type->id ?? ''; ?>' <?php echo (($document_type->id ?? '') == ($characteristic->document_type_id ?? '')) ? 'selected' : ''; ?>><?php echo $document_type->name ?? ''; ?></option>
                        <?php }
                        } ?>
                    </select>
                    <small id="helpId" class="text-muted">Изменить при необходимости</small>
                </div>

                <div id="count_block" class="col-lg-4 form-group my-3" data-bs-toggle="tooltip" title="Доступно для бумажных документов">
                    <label for="phone">Количество экземпляров</label>
                    <input class="form-control" type="number" name="count" id="count" min="1" max="5" value="<?php echo $characteristic->count ?? ''; ?>" required autocomplete="off">
                </div>

                <div id="date_block" class="col-lg-4 form-group my-3" data-bs-toggle="tooltip" title="Доступно для бумажных документов">
                    <label for="date">Когда забрать справку</label>
                    <input type="text" name="date" id="date" class="form-control is_invalid" required placeholder="" max="<?php echo $dates['max_date'] ?? ''; ?>" min="<?php echo $dates['min_date'] ?? ''; ?>" value="<?php echo Output::toDate($characteristic->date_preorder ?? '') ?? ''; ?>" autocomplete="off">
                    <small id="helpId" class="text-muted">Выберите удобную дату</small>
                </div>
                <div id="file_block" class="col-lg-12 form-group my-3">
                    <label for="date">Файл</label>
                    <input type="file" class="form-control-file mt-2" name="file" id="file" placeholder="Выберите файл">
                    <br>
                    <small id="helpId" class="text-muted">Вы можете прикрепить файл с характеристикой для проверки/исправления</small>

                    <?php if (!empty($file_hash)) { ?>
                        <div class="mt-2">
                            <a href="/files/get/<?php echo $file_hash; ?>">Загруженный файл</a>
                            <a class="mx-1" href="/Files/remove/<?php echo $file_hash; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#dc3545" class="bi bi-x-lg" viewBox="0 0 16 16">
                                    <path d="M1.293 1.293a1 1 0 0 1 1.414 0L8 6.586l5.293-5.293a1 1 0 1 1 1.414 1.414L9.414 8l5.293 5.293a1 1 0 0 1-1.414 1.414L8 9.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L6.586 8 1.293 2.707a1 1 0 0 1 0-1.414z" />
                                </svg>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="form-group my-3">
                <button class="btn btn-success" type="submit"><?php echo (empty($characteristic)) ? 'Заказать' : 'Изменить'; ?></button>
            </div>
        </form>
    </div>
</section>

<script>
    document.querySelector("#document_type_id").addEventListener("change", function() {
        $document_type_id = document.querySelector("#document_type_id").value;
        $count = document.querySelector("#count");
        $date = document.querySelector("#date");

        if ($document_type_id == 1) {
            $count.value = "";
            $date.value = "";

            count.setAttribute('disabled', 'disabled');
            date.setAttribute('disabled', 'disabled');

            $('[data-bs-toggle="tooltip"]').tooltip('enable');

        } else {
            $count.removeAttribute('disabled');
            $date.removeAttribute('disabled');

            $('[data-bs-toggle="tooltip"]').tooltip('disable');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            $document_type_id = document.querySelector("#document_type_id").value;
            $count = document.querySelector("#count");
            $date = document.querySelector("#date");

            if ($document_type_id == 1) {
                $count.value = "";
                $date.value = "";

                count.setAttribute('disabled', 'disabled');
                date.setAttribute('disabled', 'disabled');

                $('[data-bs-toggle="tooltip"]').tooltip('enable');

            } else {
                $count.removeAttribute('disabled');
                $date.removeAttribute('disabled');

                $('[data-bs-toggle="tooltip"]').tooltip('disable');
            }
        }, 50);
    });
</script>