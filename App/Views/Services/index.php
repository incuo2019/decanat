<div class="container-fluid">
    <div class="row align-items-md-stretch">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="h-100 p-5 <?php echo ($is_auth) ? '' : 'bg-light'; ?> border rounded-3">
                <h2>Справки</h2>
                <p>Запросить справку о том, что ты студент факультета ИТУ, не выходя из дома</p>
                <?php
                if ($is_auth) {
                    if ($is_moderator) {
                ?>
                        <a href="/certificates/" class="btn btn-outline-danger">Новые справки</a>
                    <?php
                    } else {
                    ?>
                        <a href="/certificates/new/" class="btn btn-outline-primary">Запросить справку</a>
                    <?php
                    }
                } else {
                    ?>
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Авторизуйтесь">
                        <button class="btn btn-outline-secondary" type="button" disabled>Запросить справку</button>
                    </span>
                <?php
                }
                ?>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="h-100 p-5 <?php echo ($is_auth) ? '' : 'bg-light'; ?> border rounded-3">
                <h2>Инд. экз. ведомости</h2>
                <p>Получить индивидуальную экзаменационную ведомость (направление) в несколько кликов</p>
                <?php
                if ($is_auth) {
                    if ($is_moderator) {
                ?>
                        <a href="/sheets/" class="btn btn-outline-danger">Новые инд. экз. ведомости</a>
                    <?php
                    } else {
                    ?>
                        <a href="/sheets/new/" class="btn btn-outline-primary">Запросить инд. экз. ведомость</a>
                    <?php
                    }
                } else {
                    ?>
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Авторизуйтесь">
                        <button class="btn btn-outline-secondary" type="button" disabled>Запросить инд. экз. ведомость</button>
                    </span>
                <?php
                }
                ?>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="h-100 p-5 <?php echo ($is_auth) ? '' : 'bg-light'; ?> border rounded-3">
                <h2>Характеристики</h2>
                <p>Запросить характеристику онлайн и получить её сегодня или на следующий день</p>
                <?php
                if ($is_auth) {
                    if ($is_moderator) {
                ?>
                        <a href="/characteristics/" class="btn btn-outline-danger">Новые характеристики</a>
                    <?php
                    } else {
                    ?>
                        <a href="/characteristics/new/" class="btn btn-outline-primary">Запросить характеристику</a>
                    <?php
                    }
                } else {
                    ?>
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Авторизуйтесь">
                        <button class="btn btn-outline-secondary" type="button" disabled>Запросить характеристику</button>
                    </span>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>