<?php

use App\Models\Department;
use App\System\Notification;

$teacher = $this->teacher ?? null;

if (empty($teacher)) {
    Notification::add('Произошла ошибка (отсутствуют данные)');
}
?>

<section class="my-5">
    <div class="container">
        <?php
        if ($teacher) {
        ?>
            <h1>Изменение данных преподавателя <?php echo $teacher->lastname . ' ' . $teacher->firstname . ' ' . $teacher->middlename; ?></h1>
        <?php
        } else {
        ?>
            <h1>Добавление нового преподавателя</h1>
        <?php
        }
        ?>
        <form class="row my-5 was-validated" method="post">
            <div class="col-md-4 form-group my-3">
                <label for="lastname">Фамилия</label>
                <input type="text" name="lastname" id="lastname" class="form-control is-invalid" required placeholder="" value="<?php if ($teacher) {
                                                                                                                                        echo $teacher->lastname ?? '';
                                                                                                                                    } ?>">
            </div>

            <div class="col-md-4 form-group my-3">
                <label for="firstname">Имя</label>
                <input type="text" name="firstname" id="firstname" class="form-control is-invalid" required placeholder="" value="<?php if ($teacher) {
                                                                                                                                        echo $teacher->firstname ?? '';
                                                                                                                                    } ?>">
            </div>

            <div class="col-md-4 form-group my-3">
                <label for="middlename">Отчество</label>
                <input type="text" name="middlename" id="middlename" class="form-control is-invalid" required placeholder="" value="<?php if ($teacher) {
                                                                                                                                        echo $teacher->middlename ?? '';
                                                                                                                                    } ?>">
            </div>

            <?php if (!empty($teacher->id)) { ?>
                <input type="hidden" name="id" value="<?php echo $teacher->id; ?>">
            <?php } ?>

            <div class="col-md-12 form-group my-3">
                <?php
                if ($teacher) {
                ?>
                    <button class="btn btn-success" type="submit">Изменить данные преподавателя</button>
                <?php
                } else {
                ?>
                    <button class="btn btn-success" type="submit">Создать преподавателя</button>
                <?php
                }
                ?>
            </div>
        </form>
    </div>
</section>