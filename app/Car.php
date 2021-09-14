<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

const CYRILLIC_CAPITAL = array('А', 'В', 'Е', 'К', 'М', 'Н', 'О', 'Р', 'С', 'Т', 'У', 'Х');
const CYRILLIC_SMALL   = array('а', 'в', 'е', 'к', 'м', 'н', 'о', 'р', 'с', 'т', 'у', 'х');
const LATIN_CAPITAL    = array('A', 'B', 'E', 'K', 'M', 'H', 'O', 'P', 'C', 'T', 'Y', 'X');
const LATIN_SMALL      = array('a', 'b', 'e', 'k', 'm', 'h', 'o', 'p', 'c', 't', 'y', 'x');

class Car extends Model
{
    protected $fillable = [
        'marque',
        'model',
        'color_id',
        'number',
        'parking_paid',
        'comment'
    ];

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public static function getLettersArray() 
    {
        return CYRILLIC_CAPITAL;
    }
    
    public static function normalizeCarNumber($number)
    {
        $number = str_replace(LATIN_CAPITAL,  CYRILLIC_CAPITAL, $number);
        $number = str_replace(CYRILLIC_SMALL, CYRILLIC_CAPITAL, $number);
        return    str_replace(LATIN_SMALL,    CYRILLIC_CAPITAL, $number);
    }

    public static function isNormalizedNumberCorrect($normalizedNumber) 
    {
        $letters = implode('', CYRILLIC_CAPITAL);
        $regExp = "/^[$letters]\s\d{3}\s[$letters]{2}\s\d{2,3}$/iu";
        return (preg_match($regExp, $normalizedNumber) === 1);
    }
}
