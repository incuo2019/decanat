<div class="container-fluid">
    <div class="row align-items-md-stretch">
        <div class="col-md-6 mb-4">
            <div class="h-100 p-5 <?php echo ($is_auth) ? '' : 'bg-light'; ?> border rounded-3">
                <h2><?php echo ($is_moderator) ? 'Учебный план всех групп' : 'Учебный план моей группы'; ?></h2>
                <p>Or, keep it light and add a border for some added definition to the boundaries of your content. Be sure to look under the hood at the source HTML here as we've adjusted the alignment and sizing of both column's content for equal-height.</p>

                <?php
                if ($is_auth) {
                    if ($is_moderator) {
                ?>
                        <a href="/curriculum/" class="btn btn-outline-danger">Перейти к учебному плану</a>
                    <?php
                    } else {
                    ?>
                        <a href="/curriculum/" class="btn btn-outline-primary">Перейти к учебному плану</a>
                    <?php
                    }
                } else {
                    ?>
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Авторизуйтесь">
                        <button class="btn btn-outline-secondary" type="button" disabled>Перейти к учебному плану</button>
                    </span>
                <?php
                }
                ?>
            </div>
        </div>
        <!-- <div class="col-md-6 mb-4">
            <div class="h-100 p-5 <?php echo ($is_auth) ? '' : 'bg-light'; ?> border rounded-3">
                <h2>Календарный график</h2>
                <p>Or, keep it light and add a border for some added definition to the boundaries of your content. Be sure to look under the hood at the source HTML here as we've adjusted the alignment and sizing of both column's content for equal-height.</p>

                <?php
                if ($is_auth) {
                ?>
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ещё находится в разработке">
                        <button class="btn btn-outline-secondary" type="button" disabled>Перейти</button>
                    </span>
                <?php
                } else {
                ?>
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Авторизуйтесь">
                        <button class="btn btn-outline-secondary" type="button" disabled>Перейти</button>
                    </span>
                <?php
                }
                ?>
            </div>
        </div> -->
        <div class="col-md-6 mb-4">
            <div class="h-100 p-5 <?php echo ($is_auth) ? '' : 'bg-light'; ?> border rounded-3">
                <h2>Рабочие учебные планы</h2>
                <p>Or, keep it light and add a border for some added definition to the boundaries of your content. Be sure to look under the hood at the source HTML here as we've adjusted the alignment and sizing of both column's content for equal-height.</p>

                <?php
                if ($is_auth) {
                ?>
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ещё находится в разработке">
                        <button class="btn btn-outline-secondary" type="button" disabled>Перейти</button>
                    </span>
                <?php
                } else {
                ?>
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Авторизуйтесь">
                        <button class="btn btn-outline-secondary" type="button" disabled>Перейти</button>
                    </span>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>