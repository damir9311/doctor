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
    
    public function actionCalendar($doctorId)
    {
        $doctor = Doctor::findOne(['id' => $doctorId]);
        if (!$doctor) {
            throw new \yii\web\NotFoundHttpException();
        }
        return $this->render(
            'calendar',
            [
                'schedule' => \Yii::$app->params['schedule'],
                'doctor' => $doctor,
            ]
        );
    }
}