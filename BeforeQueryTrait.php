<?php

namespace sibds\components;

trait BeforeQueryTrait
{

    public static function find()
    {
        /**
         * @var $obj ActiveRecord
         */
        $obj = new static;
        $class = new \ReflectionClass($obj);
        $condition = [];
        foreach ($class->getProperties(\ReflectionProperty::IS_STATIC) as $property) {
            if (strpos($property->getName(), 'BEFORE_QUERY') !== false && is_array($property->getValue($obj))) {
                $condition = array_merge($condition, $property->getValue($obj));
            }
        }
        if($obj->hasAttribute($obj->removedAttribute))
            return  (new \sibds\behaviors\TrashQuery($obj))->findRemoved()->andFilterWhere($condition);
        else
            return parent::find()->andFilterWhere($condition);
    }
}
