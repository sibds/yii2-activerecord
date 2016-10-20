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
                $params = $property->getValue($obj);
                foreach($params as $key => $value){
                    $params[$obj::tableName().'.'.$key] = $value;
                    unset($params[$key]);
                }
                $condition = array_merge($condition, $params);
            }
        }

        if ($obj->hasAttribute($obj->removedAttribute))
            return (new DynamicQuery($obj))->findRemoved()->andFilterWhere($condition);
        elseif ($obj->isNestedSet())
            return (new DynamicQuery($obj))->andFilterWhere($condition);
        else
            return parent::find()->andFilterWhere($condition);
    }
}
