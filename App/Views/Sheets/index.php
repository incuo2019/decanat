<?php

use App\Models\Department;
use App\Models\DocumentStatus;
use App\Models\DocumentType;
use App\Models\Group;
use App\Models\User;
use App\System\Auth;
use App\System\Output;

$is_moderator = Auth::isModerator();
?>

<section class="my-5 w-100">
    <div class="container">
        <h1><?php echo $sheets_title ?? ($title ?? ''); ?></h1>
        <div class="table-responsive my-5">
            <table class="table table-hover position-static" id="datatable">
                <thead class="thead-inverse">
                    <tr>
                        <th>ID</th>
                        <?php
                        if ($is_moderator) {
                        ?>
                            <th>Пользователь</th>
                        <?php
                        }
                        ?>
                        <th>Учебная дисциплина</th>
                        <th>Дата создания</th>
                        <th>Дата предзаказа</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($this->sheets)) {
                        foreach ($this->sheets as $sheet) { ?>
                            <tr>
                                <td><?php echo $sheet->id ?? ''; ?></td>
                                <?php
                                if ($is_moderator) {
                                    $user = User::getById($sheet->creator_id ?? '');
                                ?>
                                    <td><?php echo ($user->lastname ?? '') . ' ' . ($user->firstname ?? '') . ' ' . ($user->middlename ?? ''); ?></td>
                                <?php
                                }
                                ?>
                                <td><?php echo $subjects[$sheet->subject_id]->name ?? ''; ?></td>
                                <td>
                                    <?php echo Output::toDate($sheet->date_create) ?? ''; ?>
                                </td>
                                <td>
                                    <ins>
                                        <?php echo Output::toDate($sheet->date_preorder) ?? ''; ?>
                                    </ins>
                                </td>
                                <td>
                                    <nobr>
                                        <?php echo DocumentStatus::getById($sheet->status_id ?? '')->name ?? ''; ?>
                                    </nobr>
                                </td>
                                <td>
                                    <?php if (DocumentStatus::isCompleted($sheet->status_id ?? '')) { ?>
                                        <a class="btn btn-outline-primary btn-sm m-1" href="/sheets/view/<?php echo $sheet->id ?? '' ?>">Перейти</a>
                                    <?php } else { ?>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" data-boundary="viewport" aria-expanded="false">
                                                Действия
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <?php if (DocumentStatus::isNotProcessed($sheet->status_id ?? '')) { ?>
                                                    <li>
                                                        <a class="dropdown-item" href="/sheets/edit/<?php echo $sheet->id ?? '' ?>">Изменить</a>
                                                    </li>
                                                    <li class="text-danger">
                                                        <a class="dropdown-item" href="/sheets/remove/<?php echo $sheet->id ?? '' ?>">Удалить</a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_moderator && DocumentStatus::isNotProcessed($sheet->status_id ?? '')) { ?>
                                                    <li>
                                                        <a class="dropdown-item" href="/sheets/accept/<?php echo $sheet->id ?? '' ?>">Принять в обработку</a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_moderator && DocumentStatus::isAccepted($sheet->status_id ?? '')) { ?>
                                                    <li>
                                                        <a class="dropdown-item" href="/sheets/accept/<?php echo $sheet->id ?? '' ?>">Вернуть к новым</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="/sheets/response/<?php echo $sheet->id ?? '' ?>">Обработать</a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                </td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>

        <?php if ($is_moderator) { ?>
            <?php if (!empty($is_completed)) { ?>
                <a class="btn btn-outline-secondary mx-2" href="/sheets/">Перейти к новым запросам</a>
            <?php } else { ?>
                <a class="btn btn-outline-secondary mx-2" href="/sheets/completed">Перейти к завершённым запросам</a>
            <?php } ?>
        <?php } else { ?>
            <a class="btn btn-outline-success mx-2" href="/sheets/new">Заказать инд. экз. ведомость</a>
        <?php } ?>

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.table-responsive').on('show.bs.dropdown', function() {
            $('.table-responsive').css("overflow", "inherit");
        });

        $('.table-responsive').on('hide.bs.dropdown', function() {
            $('.table-responsive').css("overflow", "auto");
        })

        if ($('#datatable').length) {
            if (!$.fn.DataTable.isDataTable($('#datatable'))) {
                var table = $('#datatable').DataTable({
                    responsive: true,
                    "paging": false,
                    "info": false,
                    "searching": false,
                    "order": [
                        [0, "desk"]
                    ],
                    "language": {
                        "emptyTable": "Данных в таблице ещё нет"
                    }
                });

            }
        }
    });
</script>