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

                <div id="type_document_block" class="col-lg-12 form-group my-3">
                    <label for="phone">Комментарий</label>
                    <textarea class="form-control mt-2" name="comment" id="comment" cols="3"></textarea>
                </div>
            </div>

            <div class="form-group my-3">
                <button class="btn btn-success" type="submit">Обработать</button>
            </div>
        </form>
    </div>
</section>