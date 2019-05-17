<?php
/**
 * Created by PhpStorm.
 * User: amir
 * Date: 21/12/2018
 * Time: 17:22
 */



namespace app\services;

use Yii;
use app\models\SynthezForm;

class TTSService {


    static function synthesize(SynthezForm $model) {
        $token = self::getIAMToken($model->oauth_key);
        if ($token == null) {

            return null;
        }

        $request = [
            'text' => $model->text,
            'lang' => $model->lang,
            'voice' => $model->voice,
            'speed' => $model->speed,
            'emotion' => $model->emotion,
            'format' => 'lpcm',
            'sampleRateHertz' => 48000,
        ];

        $hash = md5(serialize($request));
        $filename = '/sounds/'.$hash;

        $path = Yii::$app->getBasePath().$filename;


        if (file_exists($path.".wav")) {
            return $filename.".wav";
        }

        $request['folderId'] = $model->folder_id;

        $query = http_build_query($request);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://tts.api.cloud.yandex.net/speech/v1/tts:synthesize');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            ['Authorization: Bearer '.$token]
        );
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TRANSFER_ENCODING, 'chunked');

        $resp = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code != 200) {
            Yii::$app->session->setFlash('error', $resp);
            return null;
        }
        file_put_contents($path.".raw", $resp);
        exec("sox -r 48000 -b 16 -e signed-integer -c 1 ".$path.".raw ".$path.".wav", $out, $return);
        exec("rm ".$path.".raw");
        if ($return !== 0) {
            Yii::$app->session->setFlash('error', print_r($out, true));
            return null;
        }
        return $filename.".wav";
    }


    static private function getIAMToken(string $oauth) {

        $req = ['yandexPassportOauthToken' => $oauth];
        $query = json_encode($req);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://iam.api.cloud.yandex.net/iam/v1/tokens');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            [
                'Content-Type: application/json',
                'Content-Length: '.strlen($query),
            ]
        );
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $resp = curl_exec($ch);
        $json = json_decode($resp);

        if ($json == null || !isset($json->{'iamToken'})) {
            Yii::$app->session->setFlash('error', $resp);
            return null;
        }
        return $json->{'iamToken'};
    }


}