<?php

namespace app\controllers;


use app\models\Doctor;
use app\models\Schedule;
use app\models\ClientDataForm;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

class DoctorController extends Controller
{
    const ERROR_MESSAGE_DOCTOR_NOT_FOUND = 'Не найден врач с указанным идентификатором.';
    const ERROR_MESSAGE_DOCTOR_NOT_AVAILABLE_ON_DATE = 'Врач не может принять в указанную дату';
    const ERROR_MESSAGE_DOCTOR_NOT_AVAILABLE_ON_TIME = 'К сожалению, кто-то уже успел забронировать это время.';
    const ERROR_MESSAGE_OPERATION_NOT_PERMITTED = 'Операция недоступна';
    const ERROR_MESSAGE_SCHEDULE_NOT_FOUND = 'Не найдена бронь с указанным идентификатором.';

    public function actionIndex()
    {
        $doctors = Doctor::find()->all();
        return $this->render('index', ['doctors' => $doctors]);
    }
    
    public function actionCalendar($doctorId)
    {
        $doctor = Doctor::findOne(['id' => $doctorId]);
        if (!$doctor) {
            throw new NotFoundHttpException(self::ERROR_MESSAGE_DOCTOR_NOT_FOUND);
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
            throw new NotFoundHttpException(self::ERROR_MESSAGE_DOCTOR_NOT_FOUND);
        }
        $date = new \DateTime($date);
        if (!$doctor->isAvailableForDate($date)) {
            throw new ForbiddenHttpException(self::ERROR_MESSAGE_DOCTOR_NOT_AVAILABLE_ON_DATE);
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
                throw new NotFoundHttpException(self::ERROR_MESSAGE_DOCTOR_NOT_FOUND);
            }

            if ($doctor->isAvailableForDateTime($date, $time)) {
                $schedule = $doctor->startReserveTime($date, $time);
                $res = [
                    'reserve_id' => $schedule->id,
                    'code' => $this->sign($schedule->id),
                ];
            } else {
                $res = ['reserved' => false];
            }

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return $res;
        } else {
            throw new ForbiddenHttpException(self::ERROR_MESSAGE_OPERATION_NOT_PERMITTED);
        }
    }

    public function actionUserData($reserveId, $code)
    {
        if ($code == $this->sign($reserveId)) {
            $scheduleRecord = Schedule::findOne(['id' => $reserveId]);
            if (!$scheduleRecord) {
                throw new NotFoundHttpException(self::ERROR_MESSAGE_DOCTOR_NOT_FOUND);
            }
            if (!$scheduleRecord->isAvailableForReserve()) {
                throw new ForbiddenHttpException(
                    self::ERROR_MESSAGE_DOCTOR_NOT_AVAILABLE_ON_TIME);
            }

            $clientData = new ClientDataForm();
            if ($clientData->load(\Yii::$app->request->post()) && $clientData->validate()) {
                $scheduleRecord->client_data = json_encode([
                    'name' => $clientData->name,
                    'phone' => $clientData->phone,
                ]);
                $scheduleRecord->reserve_status = Schedule::RESERVE_STATUS_RESERVED;
                $scheduleRecord->save();
                return $this->redirect(
                    ['reserve-done', 'reserveId' => $reserveId, 'code' => $code]);
            }

            return $this->render(
                'user_data',
                [
                    'scheduleRecord' => $scheduleRecord,
                    'doctor' => Doctor::findOne(['id' => $scheduleRecord->doctor_id]),
                    'clientData' => $clientData,
                ]
            );
        } else {
            throw new ForbiddenHttpException(self::ERROR_MESSAGE_OPERATION_NOT_PERMITTED);
        }
    }

    public function actionReserveDone($reserveId, $code)
    {
        if ($code == $this->sign($reserveId)) {
            $scheduleRecord = Schedule::findOne(['id' => $reserveId]);
            if (!$scheduleRecord) {
                throw new NotFoundHttpException(self::ERROR_MESSAGE_SCHEDULE_NOT_FOUND);
            }
            return $this->render(
                'reserve_done',
                [
                    'scheduleRecord' => $scheduleRecord,
                    'doctor' => Doctor::findOne(['id' => $scheduleRecord->doctor_id]),
                    'clientData' => $scheduleRecord->getClientData(),
                ]
            );
        } else {
            throw new ForbiddenHttpException(self::ERROR_MESSAGE_OPERATION_NOT_PERMITTED);
        }
    }

    /**
     * Подписывает ИД брони
     * 
     * @param $id
     * @return string
     */
    protected function sign($id)
    {
        return md5($id);
    }
}