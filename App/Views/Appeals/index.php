<?php

use App\Models\Department;
use App\Models\DocumentStatus;
use App\Models\DocumentType;
use App\Models\Group;
use App\Models\User;
use App\System\Auth;
use App\System\Output;

?>
<section class="my-5 w-100">
    <div class="container">
        <h1>Запросы справок</h1>
        <div class="table-responsive my-5">
            <table class="table" id="datatable">
                <thead class="thead-inverse">
                    <tr>
                        <th>ID</th>
                        <?php
                        if (Auth::isModerator()) {
                        ?>
                            <th>Пользователь</th>
                        <?php
                        }
                        ?>
                        <th>Тип документа</th>
                        <th>Дата запроса</th>
                        <th>Количество</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($this->certificates)) {
                        foreach ($this->certificates as $certificate) { ?>
                            <tr>
                                <td><?php echo $certificate->id ?? ''; ?></td>
                                <?php
                                if (Auth::isModerator()) {
                                    $user = User::getById($certificate->creator_id);
                                ?>
                                    <td><?php echo $user->lastname . ' ' . $user->firstname . ' ' . $user->middlename; ?></td>
                                <?php
                                }
                                ?>
                                <td><?php echo DocumentType::getById($certificate->document_type_id ?? '')->name ?? ''; ?></td>
                                <td><?php echo Output::toDate($certificate->date_create ?? '') ?? ''; ?></td>
                                <td><?php echo $certificate->count ?? ''; ?></td>
                                <td><?php echo DocumentStatus::getById($certificate->status_id ?? '')->name ?? ''; ?></td>
                                <td>
                                    <?php
                                    if (DocumentStatus::isNotProcessed($status_id)) {
                                    ?>
                                        <a class="btn btn-outline-warning btn-sm m-1" href="/certificates/edit/<?php echo $certificate->id ?? '' ?>">Изменить</a>
                                        <a class="btn btn-outline-danger btn-sm m-1" href="/certificates/remove/<?php echo $certificate->id ?? '' ?>">Удалить</a>
                                    <?php
                                    }
                                    if (Auth::isModerator() && DocumentStatus::isNotProcessed($certificate->status_id)) {
                                    ?>
                                        <a class="btn btn-outline-success btn-sm m-1" href="/certificates/accept/<?php echo $certificate->id ?? '' ?>">Принять в обработку</a>
                                    <?php
                                    }
                                    if (Auth::isModerator() && DocumentStatus::isAccepted($certificate->status_id)) {
                                    ?>
                                        <a class="btn btn-outline-secondary btn-sm m-1" href="/certificates/accept/<?php echo $certificate->id ?? '' ?>">Вернуть к новым</a>
                                        <a class="btn btn-outline-success btn-sm m-1" href="/certificates/response/<?php echo $certificate->id ?? '' ?>">Обработать</a>
                                    <?php
                                    }
                                    if (DocumentStatus::isCompleted($certificate->status_id)) {
                                    ?>
                                        <a class="btn btn-outline-primary btn-sm m-1" href="/certificates/view/<?php echo $certificate->id ?? '' ?>">Перейти</a>
                                    <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>

        <div class="container">
            <h1>Запросы характеристик</h1>
            <div class="table-responsive my-5">
                <table class="table" id="datatable">
                    <thead class="thead-inverse">
                        <tr>
                            <th>ID</th>
                            <?php
                            if (Auth::isModerator()) {
                            ?>
                                <th>Пользователь</th>
                            <?php
                            }
                            ?>
                            <th>Тип документа</th>
                            <th>Дата запроса</th>
                            <th>Количество</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($this->characteristics)) {
                            foreach ($this->characteristics as $characteristic) { ?>
                                <tr>
                                    <td><?php echo $characteristic->id ?? ''; ?></td>
                                    <?php
                                    if (Auth::isModerator()) {
                                        $user = User::getById($characteristic->creator_id);
                                    ?>
                                        <td><?php echo $user->lastname . ' ' . $user->firstname . ' ' . $user->middlename; ?></td>
                                    <?php
                                    }
                                    ?>
                                    <td><?php echo DocumentType::getById($characteristic->document_type_id)->name ?? ''; ?></td>
                                    <td><?php echo Output::toDate($characteristic->date_create) ?? ''; ?></td>
                                    <td><?php echo $characteristic->count ?? ''; ?></td>
                                    <td><?php echo DocumentStatus::getById($characteristic->status_id)->name ?? ''; ?></td>
                                    <td>
                                        <?php
                                        if (DocumentStatus::isNotProcessed($characteristic->$status_id)) {
                                        ?>
                                            <a class="btn btn-outline-warning btn-sm m-1" href="/characteristics/edit/<?php echo $characteristic->id ?? '' ?>">Изменить</a>
                                            <a class="btn btn-outline-danger btn-sm m-1" href="/characteristics/remove/<?php echo $characteristic->id ?? '' ?>">Удалить</a>
                                        <?php
                                        }
                                        if (Auth::isModerator() && DocumentStatus::isNotProcessed($characteristic->status_id)) {
                                        ?>
                                            <a class="btn btn-outline-success btn-sm m-1" href="/characteristics/accept/<?php echo $characteristic->id ?? '' ?>">Принять в обработку</a>
                                        <?php
                                        }
                                        if (Auth::isModerator() && DocumentStatus::isAccepted($characteristic->status_id)) {
                                        ?>
                                            <a class="btn btn-outline-secondary btn-sm m-1" href="/characteristics/accept/<?php echo $characteristic->id ?? '' ?>">Вернуть к новым</a>
                                            <a class="btn btn-outline-success btn-sm m-1" href="/characteristics/response/<?php echo $characteristic->id ?? '' ?>">Обработать</a>
                                        <?php
                                        }
                                        if (DocumentStatus::isCompleted($characteristic->status_id)) {
                                        ?>
                                            <a class="btn btn-outline-primary btn-sm m-1" href="/characteristics/view/<?php echo $characteristic->id ?? '' ?>">Перейти</a>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>

            <?php
            if (!Auth::isModerator()) {
            ?>
                <div class="form-group">
                    <a class="btn btn-outline-success mx-2" href="/certificates/new">Запросить справку</a>
                    <a class="btn btn-outline-success mx-2" href="/characteristics/new">Запросить характеристику</a>
                </div>
            <?php
            }
            ?>
        </div>
</section>