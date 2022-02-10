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
        <h1><?php echo $certificates_title ?? ($title ?? ''); ?></h1>
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
                    <?php if (!empty($this->certificates)) {
                        foreach ($this->certificates as $certificate) { ?>
                            <tr>
                                <td><?php echo $certificate->id ?? ''; ?></td>
                                <?php
                                if ($is_moderator) {
                                    $user = User::getById($certificate->creator_id ?? '');
                                ?>
                                    <td><?php echo ($user->lastname ?? '') . ' ' . ($user->firstname ?? '') . ' ' . ($user->middlename ?? ''); ?></td>
                                <?php
                                }
                                ?>
                                <td><?php echo DocumentType::getById($certificate->document_type_id)->name ?? ''; ?></td>
                                <td>
                                    <?php echo Output::toDate($certificate->date_create) ?? ''; ?>
                                </td>
                                <td>
                                    <?php if (DocumentType::isPaper($certificate->document_type_id)) { ?>
                                        <ins>
                                            <?php echo Output::toDate($certificate->date_preorder) ?? ''; ?>
                                        </ins>
                                    <?php } ?>
                                </td>
                                <td><?php echo $certificate->count ?? ''; ?></td>
                                <td>
                                    <nobr>
                                        <?php echo DocumentStatus::getById($certificate->status_id ?? '')->name ?? ''; ?>
                                    </nobr>
                                </td>
                                <td>
                                    <?php if (DocumentStatus::isCompleted($certificate->status_id ?? '')) { ?>
                                        <a class="btn btn-outline-primary btn-sm m-1" href="/certificates/view/<?php echo $certificate->id ?? '' ?>">Перейти</a>
                                    <?php } else { ?>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" data-boundary="viewport" aria-expanded="false">
                                                Действия
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <?php if (DocumentStatus::isNotProcessed($certificate->status_id ?? '')) { ?>
                                                    <li>
                                                        <a class="dropdown-item" href="/certificates/edit/<?php echo $certificate->id ?? '' ?>">Изменить</a>
                                                    </li>
                                                    <li class="text-danger">
                                                        <a class="dropdown-item" href="/certificates/remove/<?php echo $certificate->id ?? '' ?>">Удалить</a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_moderator && DocumentStatus::isNotProcessed($certificate->status_id ?? '')) { ?>
                                                    <li>
                                                        <a class="dropdown-item" href="/certificates/accept/<?php echo $certificate->id ?? '' ?>">Принять в обработку</a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($is_moderator && DocumentStatus::isAccepted($certificate->status_id ?? '')) { ?>
                                                    <li>
                                                        <a class="dropdown-item" href="/certificates/accept/<?php echo $certificate->id ?? '' ?>">Вернуть к новым</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="/certificates/response/<?php echo $certificate->id ?? '' ?>">Обработать</a>
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
                <a class="btn btn-outline-secondary mx-2" href="/certificates/">Перейти к новым запросам</a>
            <?php } else { ?>
                <a class="btn btn-outline-secondary mx-2" href="/certificates/completed">Перейти к завершённым запросам</a>
            <?php } ?>
        <?php } else { ?>
            <a class="btn btn-outline-success mx-2" href="/certificates/new">Заказать справку</a>
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