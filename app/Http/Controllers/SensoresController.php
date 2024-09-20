<?php

namespace App\Http\Controllers;

use App\Models\Parameters;
use App\Models\Sensors;
use App\Models\Sensors_data;
use Illuminate\Http\Request;

class SensoresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $t_maxima = Parameters::find(1);
        $hz_maxima = Parameters::find(2);
        return view('admin.sensores', compact('t_maxima', 'hz_maxima'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        /**SENSOR DE TEMPERATURA -----------------------------------------------------------*/
        $data = Sensors_data::where('sensor_id', 1)->get();
        $pivotx = $data->max('id');
        $data = $data->where('id', '>=', $pivotx - 70);

        $max_1 = $data->max('id');
        $max_1 = Sensors_data::find($max_1);
        $max_1 = $max_1->valor;

        $response = [];
        $valores = [];
        $ids = [];

        $n_data = [];
        $idx = 0;
        foreach ($data as  $value) {
            $n_data[] = $value;
            $id = $id + 1;
        }

        //return $n_data[0]->valor;

        for ($i = 0; $i < $data->count(); $i++) {
            $ids[$i] = $n_data[$i]->hora;
            $valores[$i] = $n_data[$i]->valor;
        }
        /**generando siguientes listas  */
        $response[0] = $ids;
        $response[1] = $valores;


        /**SENSOR DE HZ -----------------------------------------------------------*/
        $data_2 = Sensors_data::where('sensor_id', 2)->get();
        $pivotx = $data_2->max('id');
        $data_2 = $data_2->where('id', '>=', $pivotx - 70);

        $max_2 = $data_2->max('id');
        $max_2 = Sensors_data::find($max_2);
        $max_2 = $max_2->valor;

        $n_data = [];
        $idx = 0;
        foreach ($data_2 as  $value) {
            $n_data[] = $value;
            $id = $id + 1;
        }



        //return $n_data;
        $valores_2 = [];
        $ids_2 = [];
        for ($i = 0; $i < $data_2->count(); $i++) {
            $ids_2[$i] = $n_data[$i]->hora;
            $valores_2[$i] = $n_data[$i]->valor;
        }

        $response[2] = $ids_2;
        $response[3] = $valores_2;

        $response[4] = $max_1;
        $response[5] = $max_2;

        $tmax = Parameters::find(1);
        $hzmax = Parameters::find(2);

        $response[6] = $tmax->maxim;
        $response[7] = $hzmax->maxim;

        return $response;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getTemperature(int $sensor) {}
}
