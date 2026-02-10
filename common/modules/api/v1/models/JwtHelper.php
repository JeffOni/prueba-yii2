<?php

namespace common\modules\api\v1\models;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Yii;

/**
 * Helper para generar y validar tokens JWT
 */
class JwtHelper
{
    /**
     * Genera un token JWT para un usuario
     *
     * @param \common\models\User $user
     * @return string Token JWT
     */
    public static function generateToken($user)
    {
        $secretKey = Yii::$app->params['jwtSecretKey'] ?? 'your-secret-key-change-this';
        $expireTime = Yii::$app->params['jwtExpireTime'] ?? 3600; // 1 hora por defecto

        $issuedAt = time();
        $expire = $issuedAt + $expireTime;

        $payload = [
            'iat' => $issuedAt,          // Issued at: tiempo cuando se generó el token
            'exp' => $expire,             // Expire: tiempo cuando expira el token
            'user_id' => $user->id,       // ID del usuario
            'username' => $user->username, // Nombre de usuario
            'email' => $user->email,      // Email
        ];

        return JWT::encode($payload, $secretKey, 'HS256');
    }

    /**
     * Valida y decodifica un token JWT
     *
     * @param string $token Token JWT
     * @return object|false Datos decodificados del token o false si es inválido
     */
    public static function validateToken($token)
    {
        try {
            $secretKey = Yii::$app->params['jwtSecretKey'] ?? 'your-secret-key-change-this';
            return JWT::decode($token, new Key($secretKey, 'HS256'));
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtiene el token JWT del header Authorization
     *
     * @return string|null Token JWT o null si no existe
     */
    public static function getTokenFromHeader()
    {
        $authHeader = Yii::$app->request->headers->get('Authorization');

        if ($authHeader !== null && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
