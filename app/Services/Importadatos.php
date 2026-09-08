<?php

namespace App\Services;

class Importadatos
{
    public static function consultarucydni($nruc)
    {
        if (strlen($nruc) == 11) {
            $curl = curl_init();

            $data = [
                'token' => 'FuwQz2KXifBql2dXfD2AQzPskTGoCEEQf1EzErzTjmlt32g4knCWDx2E0zs8',
                'ruc' => $nruc
            ];

            $post_data = http_build_query($data);
            // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, falso);
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, falso);
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.migo.pe/api/v1/ruc",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_POSTFIELDS => $post_data,
            ));
        } else {
            if (strlen($nruc) == 8) {
                $curl = curl_init();
                $data = [
                    'token' => 'FuwQz2KXifBql2dXfD2AQzPskTGoCEEQf1EzErzTjmlt32g4knCWDx2E0zs8',
                    'dni' => $nruc
                ];
                $post_data = http_build_query($data);
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.migo.pe/api/v1/dni",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_POSTFIELDS => $post_data,
                ));
            }
        }

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }
}
