<section class="container mb-5 w-100">
    <?php if (isset($certificate)) { ?>
        <div class="form-group">
            <?php if ($certificate->status_id == 1) { ?>
                <a class="btn btn-outline-warning btn-sm m-1" href="/certificates/edit/<?php echo $certificate->id ?? '' ?>">Изменить</a>
                <a class="btn btn-outline-danger btn-sm m-1" href="/certificates/remove/<?php echo $certificate->id ?? '' ?>">Удалить</a>
            <?php } ?>

            <?php if (isset($is_moderator) && $is_moderator && $certificate->status_id == 1) { ?>
                <a class="btn btn-outline-success btn-sm m-1" href="/certificates/accept/<?php echo $certificate->id ?? '' ?>">Принять в обработку</a>
            <?php } ?>
            <?php if (isset($is_moderator) && $is_moderator && $certificate->status_id == 2) { ?>
                <a class="btn btn-outline-secondary btn-sm m-1" href="/certificates/accept/<?php echo $certificate->id ?? '' ?>">Вернуть к новым</a>
                <a class="btn btn-outline-success btn-sm m-1" href="/certificates/response/<?php echo $certificate->id ?? '' ?>">Обработать</a>
            <?php } ?>
        </div>
    <?php } ?>
    <?php if (isset($characteristic)) { ?>
        <div class="form-group">
            <?php
            if ($characteristic->status_id == 1) {
            ?>
                <a class="btn btn-outline-warning btn-sm m-1" href="/characteristics/edit/<?php echo $characteristic->id ?? '' ?>">Изменить</a>
                <a class="btn btn-outline-danger btn-sm m-1" href="/characteristics/remove/<?php echo $characteristic->id ?? '' ?>">Удалить</a>
            <?php
            }
            if (isset($is_moderator) && $is_moderator && $characteristic->status_id == 1) {
            ?>
                <a class="btn btn-outline-success btn-sm m-1" href="/characteristics/accept/<?php echo $characteristic->id ?? '' ?>">Принять в обработку</a>
            <?php
            }
            if (isset($is_moderator) && $is_moderator && $characteristic->status_id == 2) {
            ?>
                <a class="btn btn-outline-secondary btn-sm m-1" href="/characteristics/accept/<?php echo $characteristic->id ?? '' ?>">Вернуть к новым</a>
                <a class="btn btn-outline-success btn-sm m-1" href="/characteristics/response/<?php echo $characteristic->id ?? '' ?>">Обработать</a>
            <?php
            }
            ?>
        </div>
    <?php } ?>
</section>