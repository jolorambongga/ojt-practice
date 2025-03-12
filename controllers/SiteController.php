<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\RegisterForm;
use app\models\Appointment;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'login', 'register', 'reports', 'appointment'],
                'rules' => [
                    [
                        'actions' => ['login', 'register'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'reports', 'appointment', 'request-password-reset', 'logout'],
                        'allow' => true,
                        'roles' => ['@'], // Only logged-in users
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    return Yii::$app->response->redirect(['site/login']);
                },
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }



    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
        return $this->render('index');
    }
    public function actionAppointment()
    {
        return $this->render('appointment');
    }
    public function actionReports()
    {
        return $this->render('reports');
    }
    public function actionRegister()
    {
        $model = new RegisterForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->register()) {
                Yii::$app->session->setFlash('success', 'Registration successful! Redirecting to login page...');
                return $this->redirect(['site/register', 'showSuccess' => 1]); // Redirect to same page first
            } else {
                Yii::$app->session->setFlash('danger', 'Error occurred while registering. Please try again.');
            }
        }

        return $this->render('register', ['model' => $model]);
    }




    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->setFlash('success', 'You have successfully logged in!');
            return $this->redirect(['site/login', 'showSuccess' => 1]); // Redirect back to show popup
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
    }


    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render(
            'contact',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    // CRUD FOR APPOINTMENT
    public function actionCreateAppointment()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Appointment();

        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
            return [
                'status' => 'success',
                'message' => 'Appointment created successfully!'
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Failed to create appointment.',
            'errors' => $model->errors
        ];
    }

    private function getStatusColor($status)
    {
        $colors = [
            'pending' => '#ffc107',
            'cancelled' => '#dc3545',
            'rescheduled' => '#17a2b8',
            'confirmed' => '#28a745',
        ];
        return $colors[$status] ?? 'gray';
    }

    public function actionGetAppointment()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $appointments = Appointment::find()->all();

        if (!$appointments) {
            return [
                'status' => 'empty',
                'message' => 'NO APPOINTMENTS',
            ];
        }

        $events = [];
        foreach ($appointments as $appointment) {
            $events[] = [
                'id' => $appointment->appointment_id,
                'start' => $appointment->scheduled_date,
                'status' => ucwords($appointment->status),
                'title' => ucwords($appointment->appointment_type),
                'color' => $this->getStatusColor($appointment->status),
                'textColor' => ($appointment->status == 'pending') ? 'black' : 'white',
                'user_id' => $appointment->user_id,
            ];
        }

        return [
            'status' => 'success',
            'appointments' => $events
        ];
    }

    public function actionGetAppointmentsForDate($date)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $formatted_date = date('Y-m-d', strtotime($date));
        $appointments = Appointment::find()
            ->where(['scheduled_date' => $formatted_date])
            ->all();

        $appointmentData = [];
        foreach ($appointments as $appointment) {
            $appointmentData[] = [
                'id' => $appointment->appointment_id,
                'title' => ucwords($appointment->appointment_type),
                'time' => date('h:i A', strtotime($appointment->scheduled_time)),
                'status' => ucwords($appointment->status),
            ];
        }

        if (empty($appointmentData)) {
            return [
                'status' => 'success',
                'appointments' => [],
                'message' => 'No appointments available for this date.'
            ];
        }

        return [
            'status' => 'success',
            'appointments' => $appointmentData
        ];
    }




    public function actionUpdateAppointment($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Appointment::findOne($id);

        if (!$model) {
            return [
                'status' => 'error',
                'message' => 'Appointment not found.'
            ];
        }
        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
            return [
                'status' => 'success',
                'message' => 'Appointment updated successfully!'
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Failed to update appointment.',
            'error' => $model->errors
        ];
    }

    public function actionDeleteAppointment()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');

        if (!$id) {
            return [
                'status' => 'error',
                'message' => 'No appointment ID provided'
            ];
        }

        $model = Appointment::findOne($id);

        if (!$model) {
            return [
                'status' => 'error',
                'message' => 'Appointment not found!'
            ];
        }

        if ($model->delete()) {
            return [
                'status' => 'success',
                'message' => 'Appointment deleted successfully.'
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Failed to delete appointment'
        ];
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for a password reset link.');
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset the password.');
            }
        }

        return $this->render('request-password-reset', [
            'model' => $model,
        ]);
    }



    public function actionResetPassword($token)
    {
        $model = new ResetPasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->resetPassword($token)) {
            Yii::$app->session->setFlash('success', 'Your password has been reset successfully.');
            return $this->goHome();
        }

        return $this->render('reset-password', ['model' => $model]);
    }
}
