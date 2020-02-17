<?php

namespace common\facade;

use common\components\UUIDGenerator;
use yii\base\Model;

class UUID
{
    /**
     * @param string $ver
     * @param string $namespace
     * @param string $name
     * @return string
     * @throws \Exception
     */
    public static function generate($ver = 'v5', $namespace = '', $name = '')
    {
        switch ($ver) {
            case 'v3':
            case 'v5':
                if (empty($name)) {
                    throw new \Exception('Name is not set for ' . $ver . ' UUID.');
                }
                if (empty($namespace)) {
                    $namespace = UUIDGenerator::v4();
                }
                return UUIDGenerator::$ver($namespace, $name);
            case 'v4':
                return UUIDGenerator::$ver();
            case 'uq':
                return static::uniqid($namespace);
        }

        throw new \Exception("Wrong UUID version specified - [{$ver}].");
    }

    /**
     * @param string $namespace
     * @return string
     */
    private static function uniqid(string $namespace = '')
    {
        $timeBaseId = $namespace;
        /**
         * Total length = 22 char (14 + 4 + 4) + Namespace
         */
        $timeBaseId .= dechex(mt_rand(0x1000,0x5000) | 0x0402);
        $timeBaseId .= substr(base_convert(uniqid('', true), 16, 36), 0, 14);
        $timeBaseId .= dechex(mt_rand(0x8000,0xD000) | 0x2020);

        return strtoupper($timeBaseId);
    }

    /**
     * V5 Generation shortcut
     * @param string $namespace
     * @param string $name
     * @return string
     * @throws \Exception
     */
    public static function generateV5(string $namespace, string $name)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return static::generate('v5', $namespace, $name);
    }

    /**
     * @param string $namespace
     * @return string
     * @throws \Exception
     */
    public static function generateV5Auto(string $namespace)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return static::generateV5($namespace, uniqid('', true));
    }

    /**
     * @var int number of tries to generate and validate UUID
     */
    private const UUID_GENERATE_TRIES = 5;

    /**
     * Trusted way to set unique UUID to the 'uuid' property of the Model.
     *
     * @param Model $model
     * @param string $namespace
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function fillModelWithValidUUID(Model &$model, string $namespace): bool
    {
        if (!$model->canGetProperty('uuid')) {
            throw new \yii\base\InvalidConfigException('No UUID attribute or property found in the object "' . $model->formName() . '"');
        }

        $i = 0;
        do {
            if ($model->validate()) {
                return true;
            }
            $model->uuid = UUID::generateV5Auto($namespace);
        } while(++$i < static::UUID_GENERATE_TRIES);

        return false;
    }

    public static function isValid(string $uuid): bool
    {
        return UUIDGenerator::is_valid($uuid);
    }
}
