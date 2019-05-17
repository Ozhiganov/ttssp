<?php

namespace app\controllers;

use Faker\Provider\File;
use Yii;
use yii\web\Controller;
use yii\web\Cookie;
use app\models\SynthezForm;
use app\services\TTSService;

class SiteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new SynthezForm();

        $request = Yii::$app->request;

        if ($request->isPost && $model->load($request->post())) {
            $file = TTSService::synthesize($model);
           if ($file == null) {
               return $this->render('index',['model' => $model]);
           } else {

               return $this->render('index', ['model' => $model, 'file' => $file]);
           }
        }

        $model->lang = 'ru-RU';
        $model->speed = 1;
        $model->voice = 'ermil';
        $model->emotion = 'neutral';

        return $this->render('index', ['model' => $model]);
    }


    public function actionFile() {
        if ($file = Yii::$app->request->get('path')) {
            return Yii::$app->response->sendFile(Yii::$app->getBasePath().$file);
        }
    }

    /**
     * Displays about.
     *
     * @return string
     */
   /*
   public function actionAbout()
    {
        return $this->render('about');
    }
*/
    /**
     * Displays examples.
     *
     * @return string
     */
    public function actionExamples()
    {
        return $this->render('examples');
    }


    /**
     * Displays manual.
     *
     * @return string
     */
    public function actionManual()
    {
        return $this->render('instructions');
    }

}
