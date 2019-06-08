<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Consumption;
use App\Equipment;

class ChartsQuery extends Controller
{
    public function getInfo(){
    	$settings = Equipment::select('name')->orderBy('equipment_id')->get()->map(function($item){
    		return $item->name;
    	})->toArray();
    	array_unshift($settings, 'Дата');

    	return [
    		'settings' => $settings,
    		'values' => Consumption::all()
    	];
    }
}
