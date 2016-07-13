<?php

namespace app\models;


use yii\base\Model;

class ClientDataForm extends Model
{
    public $name;
    public $phone;

    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'phone' => 'Телефон'
        ];
    }

    public function rules()
    {
        return [
            [['name', 'phone'], 'required', 'message' => 'Поле обязательно для заполнения'],
        ];
    }
}