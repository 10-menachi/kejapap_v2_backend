<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    function get_properties () {
        $properties = Property::all();
        return response()->json($properties);
    }
}
