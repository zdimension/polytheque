<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelMultiplePrimary extends Model
{
    public $incrementing = false;

    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys))
        {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName)
        {
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    protected function getKeyForSaveQuery($keyName = null)
    {
        if (is_null($keyName))
        {
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName]))
        {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }
}