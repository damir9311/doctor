<?php

namespace app\controllers;


use app\models\Doctor;
use yii\web\Controller;
use yii\web\Response;

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
                'schedule' => $doctor->getScheduleScheme(),
                'doctor' => $doctor,
            ]
        );
    }
    
    public function actionTime($doctorId, $date)
    {
        $doctor = Doctor::findOne(['id' => $doctorId]);
        if (!$doctor) {
            throw new \yii\web\NotFoundHttpException();
        }
        $date = new \DateTime($date);
        if (!$doctor->isAvailableForDate($date)) {
            throw new \yii\web\ForbiddenHttpException();
        }
        return $this->render(
            'time',
            [
                'doctor' => $doctor,
                'date' => $date,
            ]
        );
    }

    public function actionReserve()
    {
        if (\Yii::$app->request->isAjax) {
            // TODO: можно запилить проверки параметров
            $doctorId = \Yii::$app->request->post('doctor_id');
            $time = \Yii::$app->request->post('time');
            $date = \Yii::$app->request->post('date');
            $date = new \DateTime($date);

            $doctor = Doctor::findOne(['id' => $doctorId]);
            if (!$doctor) {
                throw new \yii\web\NotFoundHttpException();
            }

            if ($doctor->isAvailableForDateTime($date, $time)) {
                $schedule = $doctor->startReserveTime($date, $time);
                $res = ['reserve_id' => $schedule->id];
            } else {
                $res = ['reserved' => false];
            }

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return $res;
        } else {
            throw new \yii\web\ForbiddenHttpException();
        }
    }
}