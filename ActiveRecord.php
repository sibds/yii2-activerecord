<?php
/**
 * Created by PhpStorm.
 * User: vadim
 * Date: 22.01.15
 * Time: 13:47
 */

namespace sibds\components;

use Yii;
use yii\behaviors\TimestampBehavior;
use \yii\db\Expression;
use \yii\behaviors\BlameableBehavior;


class ActiveRecord extends \yii\db\ActiveRecord
{
    public $timestampFields = ['created_at', 'updated_at'];
    public $blameableFields = ['created_by', 'updated_by'];

    public function behaviors()
    {
        /*Sources:
         * https://yii2framework.wordpress.com/2014/11/15/yii-2-behaviors-blameable-and-timestamp/comment-page-1/
         * https://toster.ru/q/82962
         * */
        $behaviors = [];
        //Check timestamp
        if(array_key_exists($this->timestampFields[0], $this->attributes)&&array_key_exists($this->timestampFields[1], $this->attributes))
            $behaviors['timestamp']=[
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => $this->timestampFields,
                    ActiveRecord::EVENT_BEFORE_UPDATE => $this->timestampFields[1],
                ],

            ];

        //Check blameable
        if(array_key_exists($this->blameableFields[0], $this->attributes)&&array_key_exists($this->blameableFields[1], $this->attributes))
            $behaviors['blameable']=[
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => $this->blameableFields[0],
                'updatedByAttribute' => $this->blameableFields[1],
            ];

        return $behaviors;
    }

    public function getCreateUser()
    {
        if(array_key_exists($this->blameableFields[0], $this->attributes)&&array_key_exists($this->blameableFields[1], $this->attributes))
            return $this->hasOne(User::className(), ['id' => $this->blameableFields[0]]);

        return null;
    }

    /**
     * @getCreateUserName
     *
     */
    public function getCreateUserName()
    {
        if(array_key_exists($this->blameableFields[0], $this->attributes)&&array_key_exists($this->blameableFields[1], $this->attributes))
            return $this->createUser ? $this->createUser->username : '- no user -';

        return null;
    }

    public function getUpdateUser()
    {
        if(array_key_exists($this->blameableFields[0], $this->attributes)&&array_key_exists($this->blameableFields[1], $this->attributes))
            return $this->hasOne(User::className(), ['id' => $this->blameableFields[1]]);

        return null;
    }

    /**
     * @getUpdateUserName
     *
     */
    public function getUpdateUserName()
    {
        if(array_key_exists($this->blameableFields[0], $this->attributes)&&array_key_exists($this->blameableFields[1], $this->attributes))
            return $this->createUser ? $this->updateUser->username : '- no user -';

        return null;
    }
}