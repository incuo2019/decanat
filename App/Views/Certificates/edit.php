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
        <h1><?php echo $title ?? ''; ?></h1>
        <form class="my-5 was-validated" method="post">
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
                                <option value='<?php echo $document_type->id ?? ''; ?>' <?php echo (($document_type->id ?? '') == ($certificate->document_type_id ?? '')) ? 'selected' : ''; ?>><?php echo $document_type->name ?? ''; ?></option>
                        <?php }
                        } ?>
                    </select>
                    <small id="helpId" class="text-muted">Изменить при необходимости</small>
                </div>

                <div id="count_block" class="col-lg-4 form-group my-3" data-bs-toggle="tooltip" title="Доступно для бумажных документов">
                    <label for="phone">Количество экземпляров</label>
                    <input class="form-control" type="number" name="count" id="count" min="1" max="5" value="<?php echo $certificate->count ?? ''; ?>" required autocomplete="off">
                </div>

                <div id="date_block" class="col-lg-4 form-group my-3" data-bs-toggle="tooltip" title="Доступно для бумажных документов">
                    <label for="date">Когда забрать справку</label>
                    <input type="text" name="date" id="date" class="form-control is_invalid" required placeholder="" max="<?php echo $dates['max_date'] ?? ''; ?>" min="<?php echo $dates['min_date'] ?? ''; ?>" value="<?php echo Output::toDate($certificate->date_preorder ?? '') ?? ''; ?>" autocomplete="off">
                    <small id="helpId" class="text-muted">Выберите удобную дату</small>
                </div>
            </div>
            <div class="form-group my-3">
                <button class="btn btn-success" type="submit"><?php echo (empty($certificate)) ? 'Заказать' : 'Изменить'; ?></button>
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