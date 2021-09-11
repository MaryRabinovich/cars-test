<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Data\Cars\AllowedSymbols;
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

        // сохраняем в стандартной форме - 
        // латинские прописные буквы и цифры
        $normalizedNumber = AllowedSymbols::normalizeCarNumber($request->number);

        if (
            Car::where('number', $normalizedNumber)->count()
            > 0
        ) return response()->json([
            'stored' => 'no',
            'error' => 'Машина с таким номером уже существует'
        ]);

        if (
            $request->marque == '' ||
            $request->model == '' ||
            !Color::find($request->color_id)
        ) return response()->json([
            'stored' => 'no',
            'error' => 'Проверьте поля "марка", "модель" и "цвет"'
        ]);

        $letters = AllowedSymbols::getLettersString();
        $regExp = "/^[$letters]\s\d{3}\s[$letters]{2}\s\d{2,3}$/iu";
        if (
            // preg_match($regExp, $request->number) != 1
            preg_match($regExp, $normalizedNumber) != 1
        ) return response()->json([
            'stored' => 'no',
            'error' => 'Проверьте номер машины',
        ]);

        Car::create([
            'marque' => $request->marque,
            'model' => $request->model,
            'color_id' => $request->color_id,
            'number' => $normalizedNumber,
            // 'number' => $request->number,
            'parking_paid' => $request->parking_paid,
            'comment' => isset($request->comment) ? $request->comment : '',
        ]);

        return response()->json([
            'stored' => 'ok'
        ]);
    }
}
