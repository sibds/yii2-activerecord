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
    public function behaviors()
    {
        /*Sources:
         * https://yii2framework.wordpress.com/2014/11/15/yii-2-behaviors-blameable-and-timestamp/comment-page-1/
         * https://toster.ru/q/82962
         * */
        $behaviors = [];
        //Check timestamp
        if(array_key_exists('create_at', $this->attributes)&&array_key_exists('update_at', $this->attributes))
            $behaviors['timestamp']=[
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_at', 'update_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_at'],
                ],

            ];

        //Check blameable
        if(array_key_exists('create_by', $this->attributes)&&array_key_exists('update_by', $this->attributes))
            $behaviors['blameable']=[
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'create_by',
                'updatedByAttribute' => 'update_by',
            ];

        return $behaviors;
    }

    public function getCreateUser()
    {
        if(array_key_exists('create_by', $this->attributes)&&array_key_exists('update_by', $this->attributes))
            return $this->hasOne(User::className(), ['id' => 'create_by']);

        return null;
    }

    /**
     * @getCreateUserName
     *
     */
    public function getCreateUserName()
    {
        if(array_key_exists('create_by', $this->attributes)&&array_key_exists('update_by', $this->attributes))
            return $this->createUser ? $this->createUser->username : '- no user -';

        return null;
    }

    public function getUpdateUser()
    {
        if(array_key_exists('create_by', $this->attributes)&&array_key_exists('update_by', $this->attributes))
            return $this->hasOne(User::className(), ['id' => 'update_by']);

        return null;
    }

    /**
     * @getUpdateUserName
     *
     */
    public function getUpdateUserName()
    {
        if(array_key_exists('create_by', $this->attributes)&&array_key_exists('update_by', $this->attributes))
            return $this->createUser ? $this->updateUser->username : '- no user -';

        return null;
    }
}