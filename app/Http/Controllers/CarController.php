<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Color;
use App\Car;

class CarController extends Controller
{
    public function index()
    {
        return Car::with('color')->get()->toJson();
    }

    public function store(Request $request)
    {
        $normalizedNumber = Car::normalizeCarNumber($request->number);
        
        if ( !Car::isNormalizedNumberCorrect($normalizedNumber) ) 
        return response()->json(['stored' => 'no',
            'error' => 'Проверьте номер машины',
        ]);

        if ( Car::where('number', $normalizedNumber)->count() > 0 ) 
        return response()->json(['stored' => 'no',
            'error' => 'Машина с таким номером уже существует'
        ]);

        if ( $request->marque == '' || $request->model == '' ||
            !Color::find($request->color_id) ) 
        return response()->json(['stored' => 'no',
            'error' => 'Проверьте поля "марка", "модель" и "цвет"'
        ]);

        Car::create([
            'marque' => $request->marque,
            'model' => $request->model,
            'color_id' => $request->color_id,
            'number' => $normalizedNumber,
            'parking_paid' => $request->parking_paid,
            'comment' => isset($request->comment) ? $request->comment : '',
        ]);

        return response()->json(['stored' => 'ok']);
    }
}
