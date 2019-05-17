<?php

namespace app\models;

use Yii;
use yii\base\Model;


class SynthezForm extends Model
{
    public $text;
    public $voice;
    public $emotion;
    public $lang;
    public $speed;
    public $oauth_key;
    public $folder_id;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [

            [['text', 'voice', 'emotion', 'lang', 'oauth_key', 'folder_id'], 'required'],
            [['text'], 'string', 'max' => 5000],
            [['speed'], 'double', 'min' => 0.1, 'max' => 3],

        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'text' => 'Текст (для обозначения ударения введите \'+\' перед буквой)',
            'voice' => 'Голос',
            'emotion' => 'Эмоциональная окраска',
            'lang' => 'Язык',
            'speed' => 'Скорость речи (от 0.1 до 3, 1 - нормальная скорость )',
            'folder_id' => 'Идентификатор каталога в Яндекс.Облако',
            'oauth_key' => 'Ваш Яндекс.OAuth токен',
        ];
    }


}
