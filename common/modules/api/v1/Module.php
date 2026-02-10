<?php

namespace common\modules\api\v1;

/**
 * Módulo API v1
 *
 * Este módulo maneja las peticiones REST de la API
 * con autenticación JWT
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'common\modules\api\v1\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // Configuración específica del módulo API
        \Yii::$app->user->enableSession = false;
        \Yii::$app->user->loginUrl = null;
    }
}
