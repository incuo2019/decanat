<?php

use App\Models\Department;
use App\Models\Group;
use App\System\Output;

?>
<section class="my-5 w-100">
    <div class="container">
        <h1>Управление пользователями</h1>
        <div class="table-responsive my-5">
            <table class="table" id="datatable">
                <thead class="thead-inverse">
                    <tr>
                        <th>ID</th>
                        <th>Номер зачётки</th>
                        <th>Фамилия</th>
                        <th>Имя</th>
                        <th>Отчество</th>
                        <th>Дата рождения</th>
                        <th>Номер телефона</th>
                        <th>Факультет</th>
                        <th>Группа</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($this->users)) {
                        foreach ($this->users as $user) { ?>
                            <tr>
                                <td><?php echo $user->id ?? ''; ?></td>
                                <td><?php echo $user->number_card ?? ''; ?></td>
                                <td><?php echo $user->lastname ?? ''; ?></td>
                                <td><?php echo $user->firstname ?? ''; ?></td>
                                <td><?php echo $user->middlename ?? ''; ?></td>
                                <td><?php echo $user->birth_date ? Output::toDate($user->birth_date) : ''; ?></td>
                                <td><?php echo $user->phone ? Output::toPhone($user->phone) : ''; ?></td>
                                <td><?php echo Department::getById($user->department_id)->short_name ?? ''; ?></td>
                                <td><?php echo Group::getById($user->group_id)->name ?? ''; ?></td>
                                <td>
                                    <a class="btn btn-outline-warning btn-sm m-1" href="/users/edit/<?php echo $user->id ?? '' ?>">Изменить</a>
                                    <a class="btn btn-outline-danger btn-sm m-1" href="/users/remove/<?php echo $user->id ?? '' ?>">Удалить</a>
                                </td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-md-4">
                <a class="btn btn-outline-success" href="/users/add">Добавить пользователя</a>
            </div>
        </div>
    </div>
</section>