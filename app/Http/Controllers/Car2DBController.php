<?php

namespace App\Http\Controllers;

use App\Models\CarEquipment;
use App\Models\CarGeneration;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\CarSeries;
use App\Models\CarSpecificationValue;
use App\Models\CarTrim;
use App\Models\Transmission;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class Car2DBController extends Controller
{
    //
    public function getVehicleTypes()
    {
        return VehicleType::all();
    }
    public function getMakes($type)
    {
        $makes = CarMake::where('id_car_type', $type)->get();
        return $makes->toArray();
    }
    public function getModels($type, $make)
    {
        $models = CarModel::where('id_car_type', $type)->where('id_car_make', $make)->get();
        return $models->toArray();
    }
    public function getModelsByMake($make)
    {
        $models = CarModel::where('id_car_make', $make)->get();
        return $models->toArray();
    }
    public function getGenerations($type, $model)
    {
        $generations = CarGeneration::where('id_car_type', $type)->where('id_car_model', $model)->get();
        return $generations->toArray();
    }
    public function getSeries($type, $model, $generation)
    {
        $series = CarSeries::where('id_car_type', $type)->where('id_car_model', $model)->where('id_car_generation', $generation)->get();
        return $series->toArray();
    }
    public function getTrims($type, $model, $serie)
    {
        $trims = CarTrim::where('id_car_type', $type)->where('id_car_model', $model)->where('id_car_serie', $serie)->get();
        return $trims->toArray();
    }
    public function getEquipments($type, $trim)
    {
        $equipments = CarEquipment::where('id_car_type', $type)->where('id_car_trim', $trim)->get();
        return $equipments->toArray();
    }
    public function transmissions()
    {
        return Transmission::all();
    }
}
