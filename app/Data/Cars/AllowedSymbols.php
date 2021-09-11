<?php

namespace App\Data\Cars;

class AllowedSymbols
{
    /**
     * В номерах машин в РФ используются 
     * следующие буквы
     * (кириллица с аналогами в латинице)
     */
    public static function getLettersArray()
    {
        return [

            // кириллица
            'А', 'В', 'Е', 'К', 'М', 'Н', 'О', 'Р', 'С', 'Т', 'У', 'Х',
            
            // latin
            'A', 'B', 'E', 'K', 'M', 'H', 'O', 'P', 'C', 'T', 'Y', 'X',
            
        ];
    }

    public static function getLettersString()
    {
        return 'АВЕКМНОРСТУХABEKMHOPCTYX';
    }

    /**
     * Возвращает номер
     * с прописными латинскими буквами и цифрами,
     * заменяем всю кириллицу
     * для упрощения проверки наличия машины в базе
     * 
     * @param string $number
     * 
     * @return string $normalizedNumber
     */
    public static function normalizeCarNumber($number)
    {

        $cyrillicCapital = ['А', 'В', 'Е', 'К', 'М', 'Н', 'О', 'Р', 'С', 'Т', 'У', 'Х'];
        $cyrillicSmall   = ['а', 'в', 'е', 'к', 'м', 'н', 'о', 'р', 'с', 'т', 'у', 'х'];
        
        $latinCapital = ['A', 'B', 'E', 'K', 'M', 'H', 'O', 'P', 'C', 'T', 'Y', 'X'];
        $latinSmall   = ['a', 'b', 'e', 'k', 'm', 'h', 'o', 'p', 'c', 't', 'y', 'x'];
        
        $number = str_replace(
            $cyrillicCapital,
            $latinCapital,
            $number
        );

        $number = str_replace(
            $cyrillicSmall,
            $latinCapital,
            $number
        );

        $number = str_replace(
            $latinSmall,
            $latinCapital,
            $number
        );

        return $number;
    }
}