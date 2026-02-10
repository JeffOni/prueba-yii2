<?php

namespace common\modules\api\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\ContentNegotiator;
use common\models\User;
use common\modules\api\v1\models\JwtHelper;

/**
 * Controlador de autenticación para la API
 * Maneja login y generación de tokens JWT
 */
class AuthController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }

    /**
     * Login y generación de token JWT
     *
     * @return array
     */
    public function actionLogin()
    {
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');

        if (empty($username) || empty($password)) {
            Yii::$app->response->statusCode = 400;
            return [
                'success' => false,
                'message' => 'Username y password son requeridos',
            ];
        }

        $user = User::findByUsername($username);

        if ($user === null || !$user->validatePassword($password)) {
            Yii::$app->response->statusCode = 401;
            return [
                'success' => false,
                'message' => 'Credenciales inválidas',
            ];
        }

        // Generar token JWT
        $token = JwtHelper::generateToken($user);

        return [
            'success' => true,
            'message' => 'Login exitoso',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'status' => $user->status,
                ],
            ],
        ];
    }

    /**
     * Verifica si un token es válido
     *
     * @return array
     */
    public function actionVerify()
    {
        $token = JwtHelper::getTokenFromHeader();

        if ($token === null) {
            Yii::$app->response->statusCode = 400;
            return [
                'success' => false,
                'message' => 'Token no proporcionado',
            ];
        }

        $data = JwtHelper::validateToken($token);

        if ($data === false) {
            Yii::$app->response->statusCode = 401;
            return [
                'success' => false,
                'message' => 'Token inválido o expirado',
            ];
        }

        return [
            'success' => true,
            'message' => 'Token válido',
            'data' => $data,
        ];
    }
}
