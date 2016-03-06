<?php
/**
 * Created by PhpStorm.
 * User: vadim
 * Date: 07.03.16
 * Time: 2:11
 */

namespace sibds\components;


use creocoder\nestedsets\NestedSetsQueryBehavior;
use sibds\behaviors\TrashQueryBehavior;
use yii\db\ActiveQuery;

class DynamicQuery extends ActiveQuery
{
    public function behaviors()
    {
        $behaviors = [];

        $model = $this->modelClass;
        //$model = new $model;

        if ($model->hasAttribute($model->removedAttribute)) {
            $behaviors[] = TrashQueryBehavior::className();
        }

        if($model->hasAttribute('lft')&&$model->hasAttribute('rgt')&&$model->hasAttribute('depth')){
            $behaviors[] = NestedSetsQueryBehavior::className();
        }

        return $behaviors;
    }
}
