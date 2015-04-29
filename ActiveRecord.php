<?php
/**
 * Created by PhpStorm.
 * User: vadim
 * Date: 22.01.15
 * Time: 13:47
 */

namespace sibds\components;

use Yii;
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
        return [
            'timestamp' => [
                'class' => DateTimeStampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_at', 'update_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'create_by',
                'updatedByAttribute' => 'update_by',
            ],
        ];
    }

    public function getCreateUser()
    {
        return $this->hasOne(User::className(), ['id' => 'create_by']);
    }

    /**
     * @getCreateUserName
     *
     */
    public function getCreateUserName()
    {
        return $this->createUser ? $this->createUser->username : '- no user -';
    }

    public function getUpdateUser()
    {
        return $this->hasOne(User::className(), ['id' => 'update_by']);
    }

    /**
     * @getUpdateUserName
     *
     */
    public function getUpdateUserName()
    {
        return $this->createUser ? $this->updateUser->username : '- no user -';
    }
}