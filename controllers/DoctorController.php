<?php

namespace app\controllers;


use app\models\Doctor;
use yii\web\Controller;

class DoctorController extends Controller
{
    public function actionIndex()
    {
        $doctors = Doctor::find()->all();
        return $this->render('index', ['doctors' => $doctors]);
    }
}