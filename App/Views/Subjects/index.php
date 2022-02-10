<?php

use App\Models\Department;

?>
<section class="my-5 w-100">
    <div class="container">
        <h1>Управление учебными дисциплинами</h1>
        <div class="table-responsive my-5">
            <table class="table" id="datatable">
                <thead class="thead-inverse">
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($this->subjects)) {
                        foreach ($this->subjects as $subject) { ?>
                            <tr>
                                <td><?php echo $subject->id ?? ''; ?></td>
                                <td><?php echo $subject->name ?? ''; ?></td>
                                <td>
                                    <a class="btn btn-outline-warning btn-sm m-1" href="/subjects/edit/<?php echo $subject->id ?? '' ?>">Изменить</a>
                                    <a class="btn btn-outline-danger btn-sm m-1" href="/subjects/remove/<?php echo $subject->id ?? '' ?>">Удалить</a>
                                </td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-md-4">
                <a class="btn btn-outline-success" href="/subjects/add">Добавить учебную дисциплину</a>
            </div>
        </div>
    </div>
</section>