@extends('sections.master')

@section('contents')
    <style>
        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .chart-container {
            width: 100%;
            /* Por defecto, cada div ocupa el 100% del ancho */
            height: 50vw;
            /* La altura será proporcional al ancho del viewport */
            margin-bottom: 20px;
        }

        @media (min-width: 600px) {
            .chart-container {
                width: 48%;
                /* En pantallas mayores, cada div ocupará el 48% del ancho */
                height: 30vw;
                /* La altura se reduce para ajustarse mejor */
            }
        }
    </style>

    <div class="m-5">
        <div class="text-center">
            <h1>MEMOSC</h1>
            <img src="/img/logo.jpeg" alt="" height="200" width="200">

        </div>
        <div class="text-center">
            <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#param-modal"
                data-bs-whatever="@mdo">Parametros</a>
        </div>
        <div class="row">
            <div class="chart-container">
                <canvas id="chartTemperature"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="chartVibrate"></canvas>
            </div>
        </div>


        @include('admin.modal.parameters')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/js/chart.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/js/sweetalert2@11.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chart1 = document.getElementById('chartTemperature');
            const chart2 = document.getElementById('chartVibrate');
            let active = true;



            var chart1g = new Chart(chart1, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Temperatura',
                        data: [],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Sensor Temperatura'
                        }
                    }
                }
            });

            /**Vibracion*/

            var chart2g = new Chart(chart2, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Vibracion(Hz)',
                        data: [],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Sensor Vibracion'
                        }
                    }
                }
            });


            sensor = 1;

            const ur_sensores_x = '{{ route('sensores.show', ['sensore' => ':sensor']) }}';
            const ur_sensores = ur_sensores_x.replace(':sensor', sensor);

            function check_alert(data_1, data_2, $tmax, $hzmax) {

                if (data_1 >= $tmax) {
                    Swal.fire(
                        'Alerta!',
                        'Temperatura fuera de rango: ' + data_1 + "°C",
                        'error'
                    )

                } else if (data_2 >= $hzmax) {
                    Swal.fire(
                        'Alerta!',
                        'Vibracion fuera de rango: ' + data_2 + "Hz",
                        'error'
                    )
                }
            }

            function getData() {
                fetch(ur_sensores, {
                        method: "GET",
                    }).then((response) => response.json())
                    .then((response) => {

                        console.log(response);

                        chart1g.data.labels = response[0];
                        chart1g.data.datasets[0].data = response[1];
                        chart1g.update();

                        chart2g.data.labels = response[2];
                        chart2g.data.datasets[0].data = response[3];
                        chart2g.update();
                        console.log(response[4] + "-" + response[5] + "-" + response[6] + "-" + response[7]);

                        check_alert(response[4], response[5], response[6], response[7]);

                    })
            }


            var intervalId = setInterval(getData, 500);
        });
    </script>
@endsection
