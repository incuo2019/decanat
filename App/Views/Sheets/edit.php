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

                <div class="col-lg-4 form-group my-3">
                    <label for="">Учебная дисциплина</label>
                    <select class="form-control" name="subject_id" id="subject_id" required>
                        <option value="">Выберите предмет</option>
                        <?php if (!empty($curriculum)) { ?>
                            <?php foreach ($curriculum as $row) { ?>
                                <option value="<?php echo $row->subject_id ?? ''; ?>" <?php echo (!empty($sheet->subject_id) && $sheet->subject_id == $row->subject_id) ? 'selected' : ''; ?>><?php echo $subjects[$row->subject_id]->name ?? ''; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>

                <!-- <div id="type_document_block" class="col-lg-4 form-group my-3">
                    <label for="phone">Тип документа</label>
                    <select class="form-control" name="document_type_id" id="document_type_id" required>
                        <?php if (!empty($document_type_list = DocumentType::getAll())) {
                            foreach ($document_type_list as $document_type) { ?>
                                <option value='<?php echo $document_type->id ?? ''; ?>' <?php echo (($document_type->id ?? '') == ($sheet->document_type_id ?? '')) ? 'selected' : ''; ?>><?php echo $document_type->name ?? ''; ?></option>
                        <?php }
                        } ?>
                    </select>
                    <small id="helpId" class="text-muted">Изменить при необходимости</small>
                </div>

                <div id="count_block" class="col-lg-4 form-group my-3" data-bs-toggle="tooltip" title="Доступно для бумажных документов">
                    <label for="phone">Количество экземпляров</label>
                    <input class="form-control" type="number" name="count" id="count" min="1" max="5" value="<?php echo $sheet->count ?? ''; ?>" required autocomplete="off">
                </div> -->

                <!-- <div id="date_block" class="col-lg-4 form-group my-3" data-bs-toggle="tooltip" title="Доступно для бумажных документов">
                    <label for="date">Когда забрать инд. экз. ведомость</label>
                    <input type="text" name="date" id="date" class="form-control is_invalid" required placeholder="" max="<?php echo $dates['max_date'] ?? ''; ?>" min="<?php echo $dates['min_date'] ?? ''; ?>" value="<?php echo Output::toDate($sheet->date_preorder ?? '') ?? ''; ?>" autocomplete="off">
                    <small id="helpId" class="text-muted">Выберите удобную дату</small>
                </div> -->

                <div id="date_block" class="col-lg-4 form-group my-3">
                    <label for="date">Когда забрать ведомость</label>
                    <input type="text" name="date" id="date" class="form-control is_invalid" required placeholder="" max="<?php echo $dates['max_date'] ?? ''; ?>" min="<?php echo $dates['min_date'] ?? ''; ?>" value="<?php echo Output::toDate($sheet->date_preorder ?? '') ?? ''; ?>" autocomplete="off">
                    <small id="helpId" class="text-muted">Выберите удобную дату</small>
                </div>
            </div>
            <div class="form-group my-3">
                <button class="btn btn-success" type="submit"><?php echo (empty($sheet)) ? 'Заказать' : 'Изменить'; ?></button>
            </div>
        </form>
    </div>
</section>