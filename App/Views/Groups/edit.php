<?php

use App\Models\Department;
use App\System\Notification;

$group = $this->group ?? null;

if (empty($group)) {
    Notification::add('Произошла ошибка (отсутствуют данные)');
}
?>

<section class="my-5">
    <div class="container">
        <?php
        if ($group) {
        ?>
            <h1>Изменение данных учебной группы <?php echo $group->name; ?></h1>
        <?php
        } else {
        ?>
            <h1>Добавление новой учебной группы</h1>
        <?php
        }
        ?>
        <form class="row my-5 was-validated" method="post">
            <div class="col-md-6 form-group my-3">
                <label for="phone">Факультет</label>
                <select class="form-control" name="department_id" id="department_id" required>
                    <option value="">Выберите факультет</option>
                    <?php if (!empty(Department::getAll())) {
                        foreach (Department::getAll() as $department) { ?>
                            <option value='<?php echo $department->id; ?>' <?php if (!empty($group->department_id) && $group->department_id == $department->id) echo 'selected'; ?>><?php echo $department->short_name; ?></option>
                    <?php }
                    } ?>
                </select>
            </div>

            <div class="col-md-6 form-group my-3">
                <label for="name">Название учебной группы</label>
                <input type="text" name="name" id="name" class="form-control is-invalid" required placeholder="" value="<?php if ($group) {
                                                                                                                            echo $group->name ?? '';
                                                                                                                        } ?>">
                <small id="helpId" class="text-muted">Например: АИБ-0-000</small>
            </div>

            <div class="col-md-6 form-group my-3">
                <label for="year">Текущий семестр</label>
                <input type="number" name="semester" id="semester" min="1" class="form-control" required value="<?php if ($group) {
                                                                                                    echo $group->semester ?? '';
                                                                                                } ?>">
            </div>
            <?php if (!empty($group->id)) { ?>
                <input type="hidden" name="id" value="<?php echo $group->id; ?>">
            <?php } ?>
            <div class="col-md-12 form-group my-3">
                <?php
                if ($group) {
                ?>
                    <button class="btn btn-success" type="submit">Изменить данные учебной группы</button>
                <?php
                } else {
                ?>
                    <button class="btn btn-success" type="submit">Создать учебную группу</button>
                <?php
                }
                ?>
            </div>
        </form>
    </div>
</section>