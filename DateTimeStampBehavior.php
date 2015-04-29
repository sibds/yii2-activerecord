<?php
/**
 * Created by PhpStorm.
 * User: vadim
 * Date: 22.01.15
 * Time: 14:06
 * Idea: https://yii2framework.wordpress.com/2014/11/15/yii-2-behaviors-blameable-and-timestamp/comment-page-1/
 */

namespace sibds\components;

use \yii\db\BaseActiveRecord;
use \yii\behaviors\AttributeBehavior;

class DateTimeStampBehavior extends AttributeBehavior {
    public $attributes = [
        BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
        BaseActiveRecord::EVENT_BEFORE_UPDATE => 'update_time',
    ];

    public $value;

    protected function getValue($event)
    {
        if ($this->value instanceof \yii\db\Expression) {
            return $this->value;
        } else {
            return $this->value !== null ? call_user_func($this->value, $event) : new Expression("NOW()");
        }
    }
    public function touch($attribute)
    {
        $this->owner->updateAttributes(array_fill_keys((array) $attribute, $this->getValue(null)));
    }

    public static function getDateTimeForDb($timestamp=null)
    {
        if ($timestamp===null){
            return date('Y-m-d H:i:s');
        }
        return date('Y-m-d H:i:s',$timestamp);
    }

    public static function getDateForDb($timestamp=null){
        if ($timestamp===null){
            return date('Y-m-d');
        }
        return date('Y-m-d',$timestamp);
    }
}