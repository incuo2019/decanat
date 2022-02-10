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
        <div class="row">
            <div class="col-md-6">
                <h1><?php echo $characteristics_title ?? ($title ?? ''); ?></h1>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="my-3">
                    <a class="text-decoration-none text-secondary" href="/characteristics/templates" target="_blank">
                        <p class="fs-5">Перейти к шаблонам характеристик</p>
                    </a>
                </div>
            </div>
        </div>
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
                        <th>Тип документа</th>
                        <th>Дата создания</th>
                        <th>Дата предзаказа</th>
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
                                if ($is_moderator) {
                                    $user = User::getById($characteristic->creator_id ?? '');
                                ?>
                                    <td><?php echo ($user->lastname ?? '') . ' ' . ($user->firstname ?? '') . ' ' . ($user->middlename ?? ''); ?></td>
                                <?php
                                }
                                ?>
                                <td><?php echo DocumentType::getById($characteristic->document_type_id)->name ?? ''; ?></td>
                                <td>
                                    <?php echo Output::toDate($characteristic->date_create) ?? ''; ?>
                                </td>
                                <td>
                                    <?php if (DocumentType::isPaper($characteristic->document_type_id)) { ?>
                                        <ins>
                                            <?php echo Output::toDate($characteristic->date_preorder) ?? ''; ?>
                                        </ins>
                                    <?php } ?>
                                </td>
                                <td><?php echo $characteristic->count ?? ''; ?></td>
                                <td>
                                    <nobr>
                                        <?php echo DocumentStatus::getById($characteristic->status_id ?? '')->name ?? ''; ?>
                                    </nobr>
                                </td>
                                <td>
                                    <?php if (DocumentStatus::isCompleted($characteristic->status_id ?? '')) { ?>
                                        <a class="btn btn-outline-primary btn-sm m-1" href="/characteristics/view/<?php echo $characteristic->id ?? '' ?>">Перейти</a>
                                    <?php } else { ?>
                                        <?php if (!$is_moderator && DocumentStatus::isAccepted($characteristic->status_id ?? '')) { ?>
                                            <button class="btn btn-sm btn-outline-primary" disabled>
                                                Принято в обработку
                                            </button>
                                        <?php } else { ?>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" data-boundary="viewport" aria-expanded="false">
                                                    Действия
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <?php if (DocumentStatus::isNotProcessed($characteristic->status_id ?? '')) { ?>
                                                        <li>
                                                            <a class="dropdown-item" href="/characteristics/edit/<?php echo $characteristic->id ?? '' ?>">Изменить</a>
                                                        </li>
                                                        <li class="text-danger">
                                                            <a class="dropdown-item" href="/characteristics/remove/<?php echo $characteristic->id ?? '' ?>">Удалить</a>
                                                        </li>
                                                    <?php } ?>
                                                    <?php if ($is_moderator && DocumentStatus::isNotProcessed($characteristic->status_id ?? '')) { ?>
                                                        <li>
                                                            <a class="dropdown-item" href="/characteristics/accept/<?php echo $characteristic->id ?? '' ?>">Принять в обработку</a>
                                                        </li>
                                                    <?php } ?>
                                                    <?php if ($is_moderator && DocumentStatus::isAccepted($characteristic->status_id ?? '')) { ?>
                                                        <li>
                                                            <a class="dropdown-item" href="/characteristics/accept/<?php echo $characteristic->id ?? '' ?>">Вернуть к новым</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="/characteristics/response/<?php echo $characteristic->id ?? '' ?>">Обработать</a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        <?php } ?>
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
                <a class="btn btn-outline-secondary mx-2" href="/characteristics/">Перейти к новым запросам</a>
            <?php } else { ?>
                <a class="btn btn-outline-secondary mx-2" href="/characteristics/completed">Перейти к завершённым запросам</a>
            <?php } ?>
        <?php } else { ?>
            <a class="btn btn-outline-success mx-2" href="/characteristics/new">Заказать характеристику</a>
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