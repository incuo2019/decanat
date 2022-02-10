<?php

use App\Models\Department;
use App\Models\Group;
use App\System\Notification;
use App\System\Output;

$user = $this->user ?? null;

if (empty($user)) {
    Notification::add('Произошла ошибка (отсутствуют данные)');
}

$date_now = Date("Y-m-d");

$t = strtotime('-80 year 00:00:00');
$min_date = Date('Y-m-d', $t);

$t = strtotime('-15 year 00:00:00');
$max_date = Date('Y-m-d', $t);

?>

<section class="my-5">
    <div class="container">
        <?php
        if ($user) {
        ?>
            <h1>Изменение данных пользователя <?php echo $user->lastname . ' ' . $user->firstname . ' ' . $user->middlename; ?></h1>
        <?php
        } else {
        ?>
            <h1>Добавление нового пользователя</h1>
        <?php
        }
        ?>
        <form class="row my-5 was-validated" method="post">
            <div class="col-md-4 form-group my-3">
                <label for="lastname">Фамилия</label>
                <input type="text" name="lastname" id="lastname" class="form-control is-invalid" required placeholder="" value="<?php if ($user) {
                                                                                                                                    echo $user->lastname ?? '';
                                                                                                                                } ?>">
                <small id="helpId" class="text-muted">Введите настоящую Фамилию пользователя</small>
            </div>

            <div class="col-md-4 form-group my-3">
                <label for="firstname">Имя</label>
                <input type="text" name="firstname" id="firstname" class="form-control is-invalid" required placeholder="" value="<?php if ($user) {
                                                                                                                                        echo $user->firstname ?? '';
                                                                                                                                    } ?>">
                <small id="helpId" class="text-muted">Введите настроящее Имя пользователя</small>
            </div>

            <div class="col-md-4 form-group my-3">
                <label for="middlename">Отчество</label>
                <input type="text" name="middlename" id="middlename" class="form-control is-invalid" required placeholder="" value="<?php if ($user) {
                                                                                                                                        echo $user->middlename ?? '';
                                                                                                                                    } ?>">
                <small id="helpId" class="text-muted">Введите настоящее Отчество пользователя</small>
            </div>

            <div class="col-md-4 form-group my-3">
                <label for="number_card">Номер зачёрной книжки</label>
                <input type="text" name="number_card" id="number_card" class="form-control is-invalid" required placeholder="" value="<?php if ($user) {
                                                                                                                                            echo $user->number_card ?? '';
                                                                                                                                        } ?>">
                <small id="helpId" class="text-muted">Незарегистрированный номер</small>
            </div>

            <div class="col-md-4 form-group my-3">
                <label for="birth_date">Дата рождения</label>
                <input type="text" name="birth_date" id="date" class="form-control" placeholder="" max="<?php echo $max_date; ?>" min="<?php echo $min_date; ?>" value="<?php if ($user) {
                                                                                                                                                                            echo $user->birth_date ? Output::toDate($user->birth_date) : '';
                                                                                                                                                                        } ?>" required>
            </div>

            <div class="col-md-4 form-group my-3">
                <label for="phone">Номер телефона</label>
                <input type="text" name="phone" id="phone" class="form-control" placeholder="" value="<?php if ($user) {
                                                                                                            echo $user->phone ? Output::toPhone($user->phone) : '';
                                                                                                        }; ?>">
                <small id="helpId" class="text-muted">Незарегистрированный номер</small>
            </div>

            <div class="col-md-4 form-group my-3">
                <label for="phone">Факультет</label>
                <select class="form-control" name="department_id" id="department_id" required>
                    <option value="">Выберите факультет</option>
                    <?php if (!empty(Department::getAll())) {
                        foreach (Department::getAll() as $department) { ?>
                            <option value='<?php echo $department->id; ?>' <?php if ($user) {
                                                                                if ($department->id == $user->department_id) echo 'selected';
                                                                            } ?>><?php echo $department->short_name; ?></option>
                    <?php }
                    } ?>
                </select>
            </div>
            <div class="col-md-4 form-group my-3">
                <label for="phone">Группа</label>
                <select class="form-control" name="group_id" id="group_id" required>
                    <option value="">Выберите группу</option>
                    <?php if (!empty(Group::getAll())) {
                        foreach (Group::getAll() as $group) { ?>
                            <option class="d-none" value='<?php echo $group->id; ?>' <?php if ($user) {
                                                                                            if ($group->id == $user->group_id) echo 'selected';
                                                                                        } ?> data-department_id="<?php echo $group->department_id; ?>"><?php echo $group->name; ?></option>
                    <?php }
                    } ?>
                </select>
            </div>

            <div class="col-md-12 form-group my-3">
                <?php
                if ($user) {
                ?>
                    <button class="btn btn-success" type="submit">Изменить данные пользователя</button>
                <?php
                } else {
                ?>
                    <button class="btn btn-success" type="submit">Создать пользователя</button>
                <?php
                }
                ?>
            </div>
        </form>
    </div>
</section>

<script>
    document.querySelector('#department_id').addEventListener('change', function() {
        var department_id = document.querySelector('#department_id');
        var group_id = document.querySelector('#group_id');
        group_id.selectedIndex = 0;

        var group_options = group_id.options;
        for (i = 0; i < group_options.length; i++) {
            var data_department_id = group_options[i].dataset.department_id;
            if (data_department_id == department_id.value) {
                group_options[i].classList.remove('d-none');
            } else {
                if (group_options[i].value == "") {
                    continue;
                }
                group_options[i].classList.add('d-none');
            }

        }
    });
</script>