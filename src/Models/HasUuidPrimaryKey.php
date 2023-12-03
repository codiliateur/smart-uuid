<?php

namespace Codiliateur\SmartUuid\Models;

use Codiliateur\SmartUuid\Uuid as SmartUuid;

/**
 * @desc Use this trait in models with UUID primary key
 *
 * Define specific $entityCode and $appCode in model properties to specific UUID generations.
 */
trait HasUuidPrimaryKey
{
    /**
     * Registering handler for "creating" event.
     */
    protected static function bootHasUuidPrimaryKey()
    {
        static::creating(function ($model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = SmartUuid::generateUuid($model->getEntityCode(), $model->getAppCode());
            }
        });
    }

    /**
     * Overrides model's method to block 'incrementing' property changes
     *
     * @return boolean
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Overrides model's method to block 'keyType' property changes
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }

    /**
     * Retrieves the application code specific for model
     *
     * @return integer
     */
    public function getAppCode()
    {
        return $this->appCode ?? 0;
    }

    /**
     * Retrieves the entity code specific for model
     *
     * @return integer
     */
    public function getEntityCode()
    {
        return $this->entityCode ?? 0;
    }

}