<?php

return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
    'user.passwordMinLength' => 8,

    // Configuración JWT para API
    'jwtSecretKey' => getenv('JWT_SECRET_KEY') ?: 'your-secret-key-change-this-in-production',
    'jwtExpireTime' => (int) (getenv('JWT_EXPIRE_TIME') ?: 3600), // 1 hora por defecto
];
