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
                <canvas id="charRNNday"></canvas>
            </div>
        </div>

        <div class="row">


            <div class="chart-container">
                <canvas id="chartVibrate"></canvas>
            </div>

            <div class="chart-container">
                <canvas id="charRNNhz"></canvas>
            </div>
        </div>


        @include('admin.modal.parameters')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/js/chart.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/js/sweetalert2@11.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@3.6.0"></script>
    <script src="/js/RNN_tsf.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chart1 = document.getElementById('chartTemperature');
            const chart2 = document.getElementById('chartVibrate');
            const chart3 = document.getElementById('charRNNday');
            const chart4 = document.getElementById('charRNNhz');

            let active = true;

            let response_all;

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

            var chart3g = new Chart(chart3, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Predicciones(Temperature)',
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
                            text: 'Sensor Temperatura Prediction'
                        }
                    }
                }
            });


            var chart4g = new Chart(chart4, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Predicciones(HZ)',
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
                            text: 'Vibracion Prediction'
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


            function generateNextTimes(startTime, count) {
                const result = [];
                let [hours, minutes, seconds] = startTime.split(':').map(Number);

                for (let i = 0; i < count; i++) {
                    // Generar dos entradas para cada timestamp
                    result.push(
                        `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`
                    );

                    // Incrementar los segundos en 12
                    seconds += 12;
                    if (seconds >= 60) {
                        seconds -= 60;
                        minutes += 1;
                    }
                    if (minutes >= 60) {
                        minutes -= 60;
                        hours += 1;
                    }
                }

                return result;
            }



            function getData() {
                fetch(ur_sensores, {
                        method: "GET",
                    }).then((response) => response.json())
                    .then((response) => {

                        //console.log(response);

                        chart1g.data.labels = response[0];
                        chart1g.data.datasets[0].data = response[1];
                        chart1g.update();

                        chart2g.data.labels = response[2];
                        chart2g.data.datasets[0].data = response[3];
                        chart2g.update();
                        //console.log(response[4] + "-" + response[5] + "-" + response[6] + "-" + response[7]);

                        check_alert(response[4], response[5], response[6], response[7]);
                        //const data = [10, 20, 25, 20, 50, 30, 70, 65, 85, 75, 50, 125, 130];

                        //run(response[1]);
                        response_all = response;
                    })

            }



            var intervalId = setInterval(getData, 1000);
            // Función que llama a `run` cada 30 segundos
            function startAutoRun() {
                setInterval(async () => {
                    try {
                        if (response_all !== undefined) {

                            // Último tiempo de la secuencia que proporcionaste
                            const lastTime = response_all[0][response_all[0].length - 1];
                            // Generar los 70 siguientes tiempos
                            const nextTimes = generateNextTimes(lastTime, 35);
                            // 35 tiempos con 2 repeticiones cada uno

                            const predictedValues = await run(response_all[1]);
                            //tthis is a new test

                            //console.log(predictedValues);

                            chart3g.data.labels = nextTimes;
                            chart3g.data.datasets[0].data = predictedValues;
                            chart3g.update();

                            const predictedValuesHZ = await runHZ(response_all[3]);
                            chart4g.data.labels = nextTimes;
                            chart4g.data.datasets[0].data = predictedValuesHZ;
                            chart4g.update();

                            console.log(response_all);


                        }
                    } catch (error) {
                        console.error("Error al ejecutar la función run:", error);
                    }
                }, 3000); // 30000 milisegundos = 30 segundos
            }

            // Iniciar el ciclo
            startAutoRun();


        });
    </script>
@endsection
