<?php

namespace App\Helpers;

class CreditCardHelper
{
    private $brands = [
        'elo'        => '/^(401178|401179|431274|438935|451416|457393|457631|457632|504175|627780|636297|636368|(506699|5067[0-6]\d|50677[0-8])|(50900\d|5090[1-9]\d|509[1-9]\d{2})|65003[1-3]|(65003[5-9]|65004\d|65005[0-1])|(65040[5-9]|6504[1-3]\d)|(65048[5-9]|65049\d|6505[0-2]\d|65053[0-8])|(65054[1-9]|6505[5-8]\d|65059[0-8])|(65070\d|65071[0-8])|65072[0-7]|(65090[1-9]|65091\d|650920)|(65165[2-9]|6516[6-7]\d)|(65500\d|65501\d)|(65502[1-9]|6550[3-4]\d|65505[0-8]))[0-9]{10,12}$/',
        'hipercard'  => '/^(((606282)\d{0,10})|((3841)\d{0,12}))$/',
        'mastercard' => '/^[5|2][1-5][0-9]{14}$/',
        'visa'       => '/^4[0-9]{12}([0-9]{3})?$/',
        'amex'       => '/^3[47][0-9]{13}$/',
        'diners'     => '/^3(0[0-5]|[68][0-9])[0-9]{11}$/',
        'discover'   => '/^6(?:011|5[0-9]{2})[0-9]{12}$/',
        'jcb'        => '/^(?:2131|1800|35\d{3})\d{11}$/',
        'aura'       => '/^(5078\d{2})(\d{2})(\d{11})$/',
        'maestro'    => '/^(?:5[0678]\d\d|6304|6390|67\d\d)\d{8,15}$/',
    ];

    public function getBrand($number): string
    {
        $number = preg_replace('/\D/', '', $number);

        foreach ($this->brands as $brand => $regex) {
            if (preg_match($regex, $number)) {
                return $brand;
            }
        }

        return 'undefined';
    }

    public function isValid($number): bool
    {
        $number = (string) preg_replace('/\D/', '', $number);

        $pieces = str_split($number);
        $checksum = (int) array_pop($pieces);

        $pieces = array_reverse($pieces);

        $pieces_count = count($pieces);
        $total = 0;

        for ($i = 0; $i < $pieces_count; $i++) {
            $pieces[$i] = (int) $pieces[$i];

            if ($i % 2 == 0) {
                $pieces[$i] = $pieces[$i] * 2;
                if ($pieces[$i] > 9) {
                    $pieces[$i] -= 9;
                }
            }
            $total += $pieces[$i];

        }

        return (bool) (($total * 9) % 10 == $checksum);
    }
}
