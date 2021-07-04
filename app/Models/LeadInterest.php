<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class LeadInterest extends Model
{
    //
    protected $appends = ['id'];

    public function getVehicleName()
    {
        if ($this->vehicle_type) {
            $vehicle_type = VehicleType::where('id_car_type', $this->vehicle_type)->first();
            if ($vehicle_type) {
                return $vehicle_type->name;
            }
        } else { }
        return "---";
    }

    public function getMakeName()
    {
        if ($this->make) {
            $car_make = CarMake::where('id_car_make', $this->make)->first();
            if ($car_make) {
                return $car_make->name;
            }
        }
        return '---';
    }
    public function getModelName()
    {
        if ($this->model) {
            $car_model = CarModel::where('id_car_model', $this->model)->first();
            if ($car_model) {
                return $car_model->name;
            }
        }
        return '---';
    }
    public function getGenerationName()
    {
        if ($this->generation) {
            $car_generation = CarGeneration::where('id_car_generation', $this->generation)->first();
            if ($car_generation) {
                return $car_generation->name;
            }
        }
        return '---';
    }
    public function getSerieName()
    {
        if ($this->serie) {
            $car_serie = CarSeries::where('id_car_serie', $this->serie)->first();
            if ($car_serie) {
                return $car_serie->name;
            }
        }
        return '---';
    }

    public function getTrimName()
    {
        if ($this->trim) {
            $car_trim = CarTrim::where('id_car_trim', $this->trim)->first();
            if ($car_trim) {
                return $car_trim->name;
            }
        }
        return '---';
    }
    public function getEquipmentName()
    {
        if ($this->equipment) {
            $car_equipment = CarEquipment::where('id_car_equipment', $this->equipment)->first();
            if ($car_equipment) {
                return $car_equipment->name;
            }
        }
        return '---';
    }
    public function getTransmissionName()
    {
        if ($this->transmission) {
            $car_transmission = Transmission::find($this->transmission);
            if ($car_transmission) {
                return $car_transmission->transmission;
            }
        }
        return '---';
    }

    public function getMileageRange()
    {
        if ($this->mileage_from && $this->mileage_to) {
            return $this->mileage_from . ' - ' . $this->mileage_to . ' ' . $this->mileage_unit;
        }
    }

    public function getPriceRange()
    {
        if ($this->price_from && $this->price_to) {
            return $this->price_from . ' - ' . $this->price_to . ' ' . $this->getPriceCurrency();
        }
    }

    public function getPriceCurrency()
    {
        if ($this->price_currency) {
            $currency = Currency::find($this->price_currency);
            if ($currency) {
                return $currency->symbol;
            }
        }
    }

    public function vehicle_details()
    {
        return $this->hasOne('App\Models\VehicleType', 'id_car_type', 'vehicle_type');
    }
    public function location_details()
    {
        return $this->hasOne('App\Models\Location', '_id', 'location');
    }
    public function make_details()
    {
        return $this->hasOne('App\Models\CarMake', 'id_car_make', 'make');
    }

    public function model_details()
    {
        return $this->hasOne('App\Models\CarModel', 'id_car_model', 'model');
    }
    public function generation_details()
    {
        return $this->hasOne('App\Models\CarGeneration', 'id_car_generation', 'generation');
    }
    public function serie_details()
    {
        return $this->hasOne('App\Models\CarSeries', 'id_car_serie', 'serie');
    }
    public function trim_details()
    {
        return $this->hasOne('App\Models\CarTrim', 'id_car_trim', 'trim');
    }
    public function equipment_details()
    {
        return $this->hasOne('App\Models\CarEquipment', 'id_car_equipment', 'equipment');
    }
    public function currency_details()
    {
        return $this->hasOne('App\Models\Currency', '_id', 'price_currency');
    }
    public function transmission_details()
    {
        return $this->hasOne('App\Models\Transmission', '_id', 'transmission');
    }
}
