<div class="container-fluid">
    <div class="row">
        <?php
        if (!stripos($current_url, 'help')) {
        ?>
            <div class="<?php echo (stripos($current_url, 'contacts')) ? 'col-lg-12 text-center' : 'col-lg-6'; ?>">
                <div class="p-5 mb-4 <?php echo ($is_auth) ? '' : 'bg-light'; ?> border rounded-3">
                    <h2>Связь с деканатом</h2>
                    <p>Номер телефона деканата: +7 (999) 999 99 99</p>
                    <p>Почта деканата: info@itu.ru</p>

                    <?php
                    if ($is_auth) {
                        if (!stripos($current_url, 'contacts')) {
                    ?>
                            <a href="/contacts/" class="btn btn-outline-primary" type="button">Перейти</a>
                        <?php
                        }
                    } else {
                        ?>
                        <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Авторизуйтесь">
                            <button class="btn btn-outline-secondary" type="button" disabled>Задать вопрос в чат</button>
                        </span>
                    <?php
                    }
                    ?>
                </div>
            </div>
        <?php
        }
        ?>
        <?php
        if (!stripos($current_url, 'contacts')) {
        ?>
            <div class="<?php echo (stripos($current_url, 'help')) ? 'col-lg-12 text-center' : 'col-lg-6'; ?>">
                <div class="p-5 mb-4 <?php echo ($is_auth) ? '' : 'bg-light'; ?> border rounded-3">
                    <h2>Помощь</h2>
                    <p>

                        <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ещё находится в разработке">
                            <a class="text-decoration-none" href="#">Часто задаваемые вопросы</a>
                        </span>
                    </p>
                    <p>
                        <a class="text-decoration-none" target="_blank" href="https://t.me/incuo">Сообщить о проблеме</a>
                    </p>
                    <p>
                        <a class="text-decoration-none" target="_blank" href="https://t.me/incuo">Связь с разработчиками</a>
                    </p>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</div>