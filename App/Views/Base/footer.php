<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/522e773d77.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/datepicker.min.js" integrity="sha512-sM9DpZQXHGs+rFjJYXE1OcuCviEgaXoQIvgsH7nejZB64A09lKeTU4nrs/K6YxFs6f+9FF2awNeJTkaLuplBhg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?php

use App\System\Auth;
use App\System\Notification;

if (Auth::isModerator()) {
?>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<?php
}
?>
<script>
    $(function() {

        var disabledDays = [0, 6];
        $("#date").datepicker({
            onRenderCell: function(date, cellType) {
                if (cellType == 'day') {
                    var day = date.getDay(),
                        isDisabled = disabledDays.indexOf(day) != -1;

                    return {
                        disabled: isDisabled
                    }
                }
            },
            minDate: new Date($("#date").attr('min')),
            maxDate: new Date($("#date").attr('max')),
            dateFormat: 'dd.mm.yyyy'
        });

        $("#year").datepicker({
            dateFormat: "yyyy",
            view: "years",
            minView: "years",
            autoClose: true
        });
    });

    $(document).ready(function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        if ($('#date').length) {
            $('#date').mask('00.00.0000', {
                clearIfNotMatch: true
            });
        }
        if ($('#number_card').length) {
            $('#number_card').mask('000000', {
                clearIfNotMatch: true
            });
        }
        if ($('#phone').length) {
            $('#phone').mask('+7 (000) 000 00-00', {
                clearIfNotMatch: true
            });
        }
        if ($('#birth_date').length) {
            $('#birth_date').mask('00.00.0000г', {
                clearIfNotMatch: true
            });
        }
        if ($(".toast").length) {
            $(".toast").toast('show');
        }
        if ($('.table').length) {
            $('.table').each(function() {
                if (!$(this).hasClass('no_datatable')) {
                    if (!$.fn.DataTable.isDataTable(this)) {
                        var table = $(this).DataTable({
                            responsive: true,
                            "paging": false,
                            "info": false,
                            "searching": false,
                            "order": [
                                [0, "desc"]
                            ],
                            "language": {
                                "emptyTable": "Данных в таблице ещё нет"
                            }
                        });

                    }
                }
            });
        }
        if ($('#summernote').length) {
            $('#summernote').summernote({
                minHeight: 300,
            });
        }
    });
</script>

</body>

</html>

<?php
Notification::delete();
?>