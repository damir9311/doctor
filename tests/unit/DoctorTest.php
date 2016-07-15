<?php

namespace app\tests\unit;


use Codeception\Util\Stub;
use yii\codeception\TestCase;
use app\models\Doctor;

class DoctorTest extends TestCase
{
    public $appConfig = '@tests/unit/_config.php';

    /**
     * @return Doctor
     */
    protected function getDoctor()
    {
        /** @var Doctor $stubDoctor */
        $stubDoctor = Stub::make(
            'app\models\Doctor',
            [
                'getScheduleScheme' => Stub::atLeastOnce(
                    function() {
                        return [
                            'monday' => [
                                '10:00-10:30',
                                '10:30-11:00',
                                '13:00-13:30',
                                '14:00-14:30',
                            ],
                            'sunday' => [
                                '10:00-10:30',
                                '10:30-11:00',
                                '13:00-13:30',
                                '14:00-14:30',
                            ],
                            'dayOffs' => ['sunday']
                        ];
                    }
                ),
            ]
        );

        /** @var Doctor $doctor */
        $doctor = Doctor::find()->one();
        $stubDoctor->setAttributes($doctor->getAttributes(), false);
        return $stubDoctor;
    }

    /**
     * @covers Doctor::isAvailableForDate
     */
    public function testDoctorIsAvailableForDate()
    {
        $doctor = $this->getDoctor();

        // на понедельник день должен быть свободен
        $this->assertTrue($doctor->isAvailableForDate(new \DateTime('2016-07-11')));
        // в воскресенье выходной
        $this->assertFalse($doctor->isAvailableForDate(new \DateTime('2016-07-17')));
        // а вторника вообще нет в расписании
        $this->assertFalse($doctor->isAvailableForDate(new \DateTime('2016-07-12')));
    }

    /**
     * @covers Doctor::isAvailableForDateTime
     */
    public function testDoctorIsAvailableForDateTime()
    {
        $date = new \DateTime('2016-07-11');
        $time = '10:00-10:30';
        $doctor = $this->getDoctor();

        // зарезервируем время у врача
        $doctor->startReserveTime($date, $time);

        // после этого это время уже недоступно
        $this->assertFalse($doctor->isAvailableForDateTime($date, $time));

        // во вторник и воскресенье не работает
        $this->assertFalse($doctor->isAvailableForDateTime(new \DateTime('2016-07-12'), $time));
        $this->assertFalse($doctor->isAvailableForDateTime(new \DateTime('2016-07-17'), $time));

        // а другое время понедельника еще свободно
        $this->assertTrue($doctor->isAvailableForDateTime($date, '10:30-11:00'));
    }
}