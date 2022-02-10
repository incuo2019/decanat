<?php

use App\Models\Department;
use App\System\Notification;

$subject = $this->subject ?? null;

if (empty($subject)) {
    Notification::add('Произошла ошибка (отсутствуют данные)');
}
?>

<section class="my-5">
    <div class="container">
        <?php
        if ($subject) {
        ?>
            <h1>Изменение данных учебной дисциплины <?php echo $subject->name; ?></h1>
        <?php
        } else {
        ?>
            <h1>Добавление новой учебной дисциплины</h1>
        <?php
        }
        ?>
        <form class="row my-5 was-validated" method="post">

            <div class="col-md-12 form-group my-3">
                <label for="name">Название учебной дисциплины</label>
                <input type="text" name="name" id="name" class="form-control is-invalid" required placeholder="" value="<?php if ($subject) {
                                                                                                                            echo $subject->name ?? '';
                                                                                                                        } ?>">
                <small id="helpId" class="text-muted">Например: Начертательная геометрия</small>
            </div>

            <?php if (!empty($subject->id)) { ?>
                <input type="hidden" name="id" value="<?php echo $subject->id; ?>">
            <?php } ?>

            <div class="col-md-12 form-group my-3">
                <?php
                if ($subject) {
                ?>
                    <button class="btn btn-success" type="submit">Изменить данные учебной дисциплины</button>
                <?php
                } else {
                ?>
                    <button class="btn btn-success" type="submit">Создать учебную дисциплину</button>
                <?php
                }
                ?>
            </div>
        </form>
    </div>
</section>