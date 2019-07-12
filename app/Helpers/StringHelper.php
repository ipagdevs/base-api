<?php

namespace App\Helpers;

class StringHelper
{
    /**
     * Retorna apenas números em um string
     * @param string $string
     *
     * @return string
     */
    public function getOnlyNumbers($string)
    {
        return preg_replace('/\D/', '', $string);
    }

    /**
     * Aplica mascara a uma string
     * @param string $value
     * @param string $mask
     *
     * @return string
     */
    public function mask($value, $mask): string
    {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($value[$k])) {
                    $maskared .= $value[$k++];
                }
            } else {
                if (isset($mask[$i])) {
                    $maskared .= $mask[$i];
                }

            }
        }

        return $maskared;
    }

    /**
     * Remove acentos de uma string
     * @param string $texto
     *
     * @return string
     */
    public function retiraAcentos($texto)
    {
        $array1 = ["á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç"
            , "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç"];
        $array2 = ["a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c"
            , "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C"];
        return str_replace($array1, $array2, $texto);
    }
}
