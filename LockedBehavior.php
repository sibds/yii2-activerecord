<?php
/**
 * Created by PhpStorm.
 * User: vadim
 * Date: 05.02.16
 * Time: 12:57
 */

namespace sibds\components;


use yii\base\Behavior;
use yii\base\Exception;

class LockedBehavior extends Behavior
{
    public $lockedAttribute;

    public $valueLock = true;
    public $valueUnlock = false;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'getDefaultValue',
        ];
    }

    public function getDefaultValue($event)
    {
        $owner = $this->owner;
        if ($owner->hasAttribute($this->lockedAttribute)) {
            if (empty($owner->{$this->lockedAttribute}) || is_null($owner->{$this->lockedAttribute})) {
                $owner->{$this->lockedAttribute} = $this->valueUnlock;
            }
        }
    }

    public function lock()
    {
        /* @var $owner ActiveRecord */
        $owner = $this->owner;

        $owner->{$this->lockedAttribute} = $this->valueLock;
        if(!$owner->validate())
            throw new \yii\db\Exception('Error saving model! Validating not comlete: ' . implode('; ', $owner->firstErrors));
        $owner->save();
    }

    public function unlock()
    {
        /* @var $owner ActiveRecord */
        $owner = $this->owner;

        $owner->{$this->lockedAttribute} = $this->valueUnlock;
        if(!$owner->validate())
            throw new \yii\db\Exception('Error saving model! Validating not comlete: ' . implode('; ', $owner->firstErrors));

        $owner->save();
    }
}
