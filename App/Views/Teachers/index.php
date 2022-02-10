<?php

use App\Models\Department;

?>
<section class="my-5 w-100">
    <div class="container">
        <h1>Управление данными преподавателей</h1>
        <div class="table-responsive my-5">
            <table class="table" id="datatable">
                <thead class="thead-inverse">
                    <tr>
                        <th>ID</th>
                        <th>ФИО</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($this->teachers)) {
                        foreach ($this->teachers as $teacher) { ?>
                            <tr>
                                <td><?php echo $teacher->id ?? ''; ?></td>
                                <td><?php echo ($teacher->lastname ?? '') . ' ' . ($teacher->firstname ?? '') . ' ' . ($teacher->middlename ?? ''); ?></td>
                                <td>
                                    <a class="btn btn-outline-warning btn-sm m-1" href="/teachers/edit/<?php echo $teacher->id ?? '' ?>">Изменить</a>
                                    <a class="btn btn-outline-danger btn-sm m-1" href="/teachers/remove/<?php echo $teacher->id ?? '' ?>">Удалить</a>
                                </td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-md-4">
                <a class="btn btn-outline-success" href="/teachers/add">Добавить преподавателя</a>
            </div>
        </div>
    </div>
</section>