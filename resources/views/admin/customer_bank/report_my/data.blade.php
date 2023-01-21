<canvas style="max-height: 400px;" id="myChart"></canvas>

<script src="{{asset('admin/js/chart.js')}}"></script>
<script>
    var myChart
    const labels = @json($month_arr);
    const data = {
        labels: labels,
        datasets: [
            {
                label: 'مشتریان جدید',
                backgroundColor: '#8856ce',
                borderColor: '#8856ce',
                data: @json($new_customer_arr),
            },
            {
                label: 'مشتریان من',
                backgroundColor: '#791515',
                borderColor: '#791515',
                data: @json($my_customer_arr),
            },
            {
                label: 'مشتریان(راضی)',
                backgroundColor: '#7b0f63',
                borderColor: '#7b0f63',
                data: @json($razi_customer_arr),
            },
            {
                label: 'مشتریان(وفادار)',
                backgroundColor: '#0f7b1c',
                borderColor: '#0f7b1c',
                data: @json($vafadar_customer_arr),
            },
            {
                label: 'مشتریان(هوادار)',
                backgroundColor: '#7d5808',
                borderColor: '#7d5808',
                data: @json($havadar_customer_arr),
            },
            {
                label: 'مشتریان(ارجاعی معرفی کرده)',
                backgroundColor: '#b2b512',
                borderColor: '#b2b512',
                data: @json($referr_customer_arr),
            },
            {
                label: 'مشتریان(همه)',
                backgroundColor: '#07a6a4',
                borderColor: '#07a6a4',
                data: @json($all_customer_arr),
            },
        ]
    };

    let options = {
        scales: {
            y: {
                ticks: {
                    stepSize: 1
                }
            }
        }
    };

    const config = {type: 'line', data: data, options: options};
    myChart = new Chart(document.getElementById('myChart'), config);

    $("#form_create_factor_flung_req").validate({
        submitHandler: function (form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function (data) {
                    if (data.status == 0) {
                        $.jGrowl(data.message, {life: 6000, position: 'bottom-right', theme: 'bg-danger'});
                    } else {
                        $('#data_new').empty();
                        $('#data_new').append(data);
                        document.getElementById("form_create_factor_flung_req").reset();
                        $.jGrowl('افزوده شد', {life: 6000, position: 'bottom-right', theme: 'bg-success'});
                    }
                }
            });
        }
    });
</script>