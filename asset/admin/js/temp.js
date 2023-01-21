slug('#title', '#slug');
if ($('#form_req')[0] || $('#form_req_year')[0]) {
    $(document).ready(function () {
        jQuery.extend(jQuery.validator.messages, {
            required: "این فیلد الزامی است.",
            remote: "لطفاً این قسمت را اصلاح کنید.",
            email: "لطفا یک آدرس ایمیل معتبر وارد کنید.",
            url: "لطفا یک نشانی وب معتبر وارد کنید.",
            date: "لطفا یک تاریخ معتبر وارد کنید.",
            dateISO: "لطفاً تاریخ معتبر (ISO) را وارد کنید.",
            number: "لطفا یک شماره معتبر وارد کنید.",
            digits: "لطفاً فقط ارقام را وارد کنید.",
            creditcard: "لطفا یک شماره کارت اعتباری معتبر وارد کنید.",
            equalTo: "لطفا مجددا همان مقدار را وارد کنید.",
            accept: "لطفاً یک مقدار با پسوند معتبر وارد کنید.",
            maxlength: jQuery.validator.format("لطفاً بیش از {0} نویسه وارد نکنید."),
            minlength: jQuery.validator.format("لطفاً حداقل {0} نویسه وارد کنید."),
            rangelength: jQuery.validator.format("لطفاً مقدار بین {0} و {1} نویسه را وارد کنید."),
            range: jQuery.validator.format("لطفاً مقداری بین {0} و {1} وارد کنید."),
            max: jQuery.validator.format("لطفاً مقداری کمتر یا مساوی {0} وارد کنید."),
            min: jQuery.validator.format("لطفاً مقدار بزرگتر یا مساوی {0} وارد کنید.")
        });

    });
    $("#form_req").validate({
        submitHandler: function (form) {
            if($('.textarea_rtl')[0] || $('.textarea_ltr')[0]) {
                for (var i in CKEDITOR.instances) {
                    CKEDITOR.instances[i].updateElement();
                }
            }
            $('#global-loader-form').css('display', 'block');
            form.submit();
        }
    });
}
$(".key_word").selectize({
    delimiter: ",",
    plugins: {
        remove_button: {
            label: "×"
        }
    },
    persist: false,
    createOnBlur: true,
    create: true
});
new ClipboardJS('.copy_btn');
$(function () {
    $('.select2').select2()
});
$(document).ready(function () {
    $('.numberPrice').text(function (index, value) {
        return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
});
$(document).ready(function () {
    $(".persian-datepicker").pDatepicker({
        // observer: true,
        defaultDate: true,
        format: 'YYYY/MM/DD',
        locale: 'en',
        // altField: '.observer-example-alt'
    });
});
if ($('.date_p')[0]) {
    $('.date_p').persianDatepicker({
        observer: true,
        format: 'YYYY/MM/DD',
        initialValueType: 'gregorian',
        initialValue: true,
    });
}
if ($('.date_p1')[0]) {
    $('.date_p1').persianDatepicker({
        observer: true,
        format: 'YYYY/MM/DD',
        initialValue: false,
    });
}
if ($('.tbl_1')[0]) {
    $(".tbl_1").DataTable({
        "order": [0, "asc"],
        "language": {
            "search": '<span>فیلتر :</span> _INPUT_',
            "lengthMenu": '<span class="tb-num">تعداد نمایش :</span> _MENU_',
            "emptyTable": "موردی یافت نشد",
            "zeroRecords": "درجستجو، موردی یافت نشد",
            "paginate": {
                "next": "بعدی",
                "previous": "قبلی"            }
        },
        "info": false,
        "paging": true,
        "ordering": true,
        "responsive": false,
    });
}
$(document).ready(function () {
    $('[data-toggle="popover"]').popover();
});
$('.popover-dismiss').popover({
    trigger: 'focus'
})
