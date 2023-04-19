<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Optimizer extends Model
{
    public static function saveAs($path)
    {

        $WEBSERVICE = 'http://api.resmush.it/ws.php?img=';


        $optimized_png_arr = json_decode(file_get_contents($WEBSERVICE . url($path)));

        if (isset($optimized_png_arr->dest)) {
            $optimized_png_url = $optimized_png_arr->dest;

            $fp = fopen($path, 'w+');

            $ch = curl_init($optimized_png_url);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
            curl_exec($ch);

            curl_close($ch);
            fclose($fp);


            return response()->json([
                'success' => true,
                'url' => $path,
            ]);

        } else {

            return response()->json([
                'success' => false,
                'url' => $path,
            ]);

        }

    }

}
