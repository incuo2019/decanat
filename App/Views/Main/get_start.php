<div class="container-fluid">
    <div class="p-5 mb-4 border rounded-3 text-center">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold"><?php global $app_settings;
                                            echo $app_settings['project_name']; ?></h1>
            <div class="container">
                <p class="mb-5 fs-4">Актуальная информация, календарный график, рабочие учебные планы, справки, экзаменационные ведомости, характеристики, вопросы и ответы от деканата ИТУ РГУПС.</p>
            </div>
            <?php if ($is_auth) { ?>
                <a href="/appeals/" class="btn <?php echo $is_moderator ? 'btn-outline-danger' : 'btn-outline-primary' ?> btn-lg px-4"><?php echo $is_moderator ? 'Новые обращения' : 'Мои обращения' ?></a>
            <?php } else { ?>
                <a href="/auth/" class="btn btn-outline-primary btn-lg px-4">Авторизоваться</a>
            <?php } ?>
        </div>
    </div>
</div>