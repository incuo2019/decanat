<?php

use App\Models\Department;

?>
<section class="my-5 w-100">
    <div class="container">
        <h1>Управление учебными группами</h1>
        <div class="table-responsive my-5">
            <table class="table" id="datatable">
                <thead class="thead-inverse">
                    <tr>
                        <th>ID</th>
                        <th>Факультет</th>
                        <th>Название</th>
                        <th>Семестр</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($this->groups)) {
                        foreach ($this->groups as $group) { ?>
                            <tr>
                                <td><?php echo $group->id ?? ''; ?></td>
                                <td><?php echo Department::getById($group->department_id)->short_name ?? ''; ?></td>
                                <td><?php echo $group->name ?? ''; ?></td>
                                <td><?php echo $group->semester ?? ''; ?></td>
                                <td>
                                    <a class="btn btn-outline-warning btn-sm m-1" href="/groups/edit/<?php echo $group->id ?? '' ?>">Изменить</a>
                                    <a class="btn btn-outline-danger btn-sm m-1" href="/groups/remove/<?php echo $group->id ?? '' ?>">Удалить</a>
                                </td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-md-4">
                <a class="btn btn-outline-success" href="/groups/add">Добавить учебную группу</a>
            </div>
        </div>
    </div>
</section>