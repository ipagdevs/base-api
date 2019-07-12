<?php

namespace App\Helpers;

class DateHelper
{
    /**
     * Verifica se a data informada não irá cair em fds ou feriado,
     * se sim retorna o próximo dia util
     * @param string $data
     *
     * @return string (Y-m-d)
     */
    public function setExpiryDate($data)
    {
        $ano = \DateTime::createFromFormat('Y-m-d', $data)->format('Y');
        $pascoa_datetime = date("Y-m-d", easter_date($ano));
        $carnaval = date('d-m', strtotime($pascoa_datetime.'-47 days'));
        $sexta_santa = date('d-m', strtotime($pascoa_datetime.'-2 days'));
        $corpus = date('d-m', strtotime($pascoa_datetime.'+60 days'));
        $pascoa = date('d-m', strtotime($pascoa_datetime));

        //feriados nacionais
        $feriados = array();
        $feriados[] = "01-01"; //confraternização universal
        $feriados[] = "21-04"; //Tiradentes
        $feriados[] = "01-05"; //Dia do trabalho
        $feriados[] = "07-09"; //Independência
        $feriados[] = "12-10"; //Nossa Senhora Aparecida
        $feriados[] = "02-11"; //Finados
        $feriados[] = "15-11"; //Proclamação da Republica
        $feriados[] = "25-12"; //Natal
        $feriados[] = $carnaval;
        $feriados[] = $sexta_santa;
        $feriados[] = $corpus;

        // verifica o dia da semana do vencimento
        // sexta: se for feriado, adiciona 1 dia à data antes de verificar se é final de semana
        // sábado: adiciona 2 dias à data
        // domingo: adiciona 1 dia à data
        $datetime = \DateTime::createFromFormat('Y-m-d', $data)->format('Y-m-d');
        $dow = date('w', strtotime($datetime));
        if ($dow == 5) {
            //sexta e feriado
            $diames = date("d-m", strtotime($datetime));
            if (in_array($diames, $feriados)) {
                $datetime = date('Y-m-d', strtotime($datetime.'+1 days'));
                $dow = date('w', strtotime($datetime));
            }
        }

        if ($dow == 0) {
            //domingo
            $datetime = date('Y-m-d', strtotime($datetime.'+1 days'));
        } elseif ($dow == 6) {
            //sabado
            $datetime = date('Y-m-d', strtotime($datetime.'+2 days'));
        }

        //feriado
        $diames = date("d-m", strtotime($datetime));
        if (in_array($diames, $feriados)) {
            $datetime = date('Y-m-d', strtotime($datetime.'+1 days'));
        }

        return $datetime;
    }
}
