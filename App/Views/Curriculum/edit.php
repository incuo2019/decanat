<section class="my-5 w-100">
    <div class="container">
        <h1><?php echo $certificates_title ?? ($title ?? ''); ?></h1>
        <form method="post">
            <div class="table-responsive my-5">
                <table class="table table-hover position-static no_datatable">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Учебная группа</th>
                            <th>Преподаватель</th>
                            <th>Предмет</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tbody">

                        <tr class="d-none" id="row">
                            <td>
                                <div class="form-group">
                                    <label class="index col-form-label">1</label>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control group" name="group[1]" required>
                                        <option value="">Выберите учебную группу</option>
                                        <?php if (!empty($groups)) { ?>
                                            <?php foreach ($groups as $group) { ?>
                                                <option value="<?php echo $group->id; ?>"><?php echo $group->name; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control teacher" name="teacher[1]" required>
                                        <option value="">Выберите преподавателя</option>
                                        <?php if (!empty($teachers)) { ?>
                                            <?php foreach ($teachers as $teacher) { ?>
                                                <option value="<?php echo $teacher->id; ?>"><?php echo $teacher->lastname . ' ' . $teacher->firstname . ' ' . $teacher->middlename; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control subject" name="subject[1]" required>
                                        <option value="">Выберите предмет</option>
                                        <?php if (!empty($subjects)) { ?>
                                            <?php foreach ($subjects as $subject) { ?>
                                                <option value="<?php echo $subject->id; ?>"><?php echo $subject->name; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </td>
                            <td class="pe-3" style="width: 0;">
                                <div class="form-group text-center">
                                    <button class="btn btn-outline-danger" onclick="RemoveBlock(this);">
                                        ×
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <?php if (!empty($curriculum)) { ?>
                            <?php foreach ($curriculum as $row) { ?>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <label class="index col-form-label"><?php echo $row->id ?? ''; ?></label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control group" name="group[<?php echo $row->id ?? ''; ?>]" required>
                                                <option value="">Выберите учебную группу</option>
                                                <?php if (!empty($groups)) { ?>
                                                    <?php foreach ($groups as $group) { ?>
                                                        <option value="<?php echo $group->id; ?>" <?php echo ($row->group_id == $group->id) ? 'selected' : ''; ?>><?php echo $group->name; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control teacher" name="teacher[<?php echo $row->id ?? ''; ?>]" required>
                                                <option value="">Выберите преподавателя</option>
                                                <?php if (!empty($teachers)) { ?>
                                                    <?php foreach ($teachers as $teacher) { ?>
                                                        <option value="<?php echo $teacher->id; ?>" <?php echo ($row->teacher_id == $teacher->id) ? 'selected' : ''; ?>><?php echo $teacher->lastname . ' ' . $teacher->firstname . ' ' . $teacher->middlename; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control subject" name="subject[<?php echo $row->id ?? ''; ?>]" required>
                                                <option value="">Выберите предмет</option>
                                                <?php if (!empty($subjects)) { ?>
                                                    <?php foreach ($subjects as $subject) { ?>
                                                        <option value="<?php echo $subject->id; ?>" <?php echo ($row->subject_id == $subject->id) ? 'selected' : ''; ?>><?php echo $subject->name; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="pe-3" style="width: 0;">
                                        <div class="form-group text-center">
                                            <button class="btn btn-outline-danger" onclick="RemoveBlock(this);">
                                                ×
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                <button class="btn btn-outline-success btn-sm" onclick="AppendBlock(this);">Добавить строку</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="form-group">
                <button class="btn btn-success" type="submit">Сохранить</button>
            </div>
        </form>
    </div>
</section>

<script>
    const AppendBlock = function() {
        if (typeof AppendBlock.Add == "function") {
            AppendBlock.Add();
        }
        updateIds();
    }

    const RemoveBlock = function(btn) {
        var block = btn.closest('tr');
        block.parentNode.removeChild(block);
        updateIds();
    }

    document.addEventListener("DOMContentLoaded", function() {
        const node = document.querySelector("#row").cloneNode(true);
        document.querySelector("#row").remove();

        AppendBlock.Add = function() {
            event.preventDefault();
            node.removeAttribute("id");
            node.removeAttribute("class");
            document.querySelector("#tbody").appendChild(node.cloneNode(true));
        }
    });

    function updateIds() {
        var rows = document.querySelectorAll("tr");

        for (var row of rows) {
            if (row.querySelector('.index')) {
                row.querySelector('.index').textContent = row.rowIndex;
            }
            if (row.querySelector('.group')) {
                row.querySelector('.group').setAttribute('name', 'group[' + row.rowIndex + ']');
            }
            if (row.querySelector('.teacher')) {
                row.querySelector('.teacher').setAttribute('name', 'teacher[' + row.rowIndex + ']');
            }
            if (row.querySelector('.subject')) {
                row.querySelector('.subject').setAttribute('name', 'subject[' + row.rowIndex + ']');
            }
        }
    }
</script>