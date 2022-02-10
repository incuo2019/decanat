<section class="my-5 w-100">
    <div class="container">
        <h1><?php echo $certificates_title ?? ($title ?? ''); ?></h1>
        <?php if (!empty($curriculum)) { ?>
            <div class="table-responsive my-5">
                <table class="table table-hover position-static no_datatable">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Учебная группа</th>
                            <th>Преподаватель</th>
                            <th>Предмет</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <?php foreach ($curriculum as $index => $row) { ?>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label class="index col-form-label"><?php echo $index + 1 ?? ''; ?></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label class="col-form-label"><?php echo $groups[$row->group_id]->name ?? ''; ?></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label class="col-form-label"><?php echo ($teachers[$row->teacher_id]->lastname ?? '') . ' ' . ($teachers[$row->teacher_id]->firstname ?? '') . ' ' . ($teachers[$row->teacher_id]->middlename ?? ''); ?></label>
                                    </div>
                                </td>
                                <td>

                                    <div class="form-group">
                                        <label class="col-form-label"><?php echo $subjects[$row->subject_id]->name ?? ''; ?></label>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
        <?php if ($is_moderator) { ?>
            <a class="btn btn-outline-warning mx-2" href="/curriculum/edit">Изменить план</a>
        <?php } ?>
    </div>
</section>