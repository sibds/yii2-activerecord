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
    //Status state
    const STATUS_DEFAULT = 0;
    const STATUS_LOCK = -1;
    const STATUS_REMOVE = 1;

    // Dynamical fields for behaviors
    /**
     * @var string the attribute that will receive timestamp value
     * Set this property to false if you do not want to record the creation time.
     */
    public $createdAtAttribute = 'created_at';
    /**
     * @var string the attribute that will receive timestamp value.
     * Set this property to false if you do not want to record the update time.
     */
    public $updatedAtAttribute = 'updated_at';

    /**
     * @var string the attribute that will receive current user ID value
     * Set this property to false if you do not want to record the creator ID.
     */
    public $createdByAttribute = 'created_by';
    /**
     * @var string the attribute that will receive current user ID value
     * Set this property to false if you do not want to record the updater ID.
     */
    public $updatedByAttribute = 'updated_by';


    public function behaviors()
    {
        /*Sources:
         * https://yii2framework.wordpress.com/2014/11/15/yii-2-behaviors-blameable-and-timestamp/comment-page-1/
         * https://toster.ru/q/82962
         * */
        // If table not have fields, then behavior not use
        $behaviors = [];
        //Check timestamp
        if(array_key_exists($this->createdAtAttribute, $this->attributes)&&array_key_exists($this->updatedAtAttribute, $this->attributes))
            $behaviors['timestamp']=[
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => [$this->createdAtAttribute, $this->updatedAtAttribute],
                    ActiveRecord::EVENT_BEFORE_UPDATE => $this->updatedAtAttribute,
                ],

            ];

        //Check blameable
        if(array_key_exists($this->createdByAttribute, $this->attributes)&&array_key_exists($this->updatedByAttribute, $this->attributes))
            $behaviors['blameable']=[
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => $this->createdByAttribute,
                'updatedByAttribute' => $this->updatedByAttribute,
            ];

        return $behaviors;
    }

    /**
     * @getCreateUser
     * @return null|\yii\db\ActiveQuery
     */
    public function getCreateUser()
    {
        if(array_key_exists($this->createdByAttribute, $this->attributes)&&array_key_exists($this->updatedByAttribute, $this->attributes))
            return $this->hasOne(User::className(), ['id' => $this->createdByAttribute]);

        return null;
    }

    /**
     * @getCreateUserName
     * @return null|\yii\db\ActiveQuery
     */
    public function getCreateUserName()
    {
        if(array_key_exists($this->createdByAttribute, $this->attributes)&&array_key_exists($this->updatedByAttribute, $this->attributes))
            return $this->createUser ? $this->createUser->username : '- no user -';

        return null;
    }

    /**
     * @getUpdateUser
     * @return null|\yii\db\ActiveQuery
     */
    public function getUpdateUser()
    {
        if(array_key_exists($this->createdByAttribute, $this->attributes)&&array_key_exists($this->updatedByAttribute, $this->attributes))
            return $this->hasOne(User::className(), ['id' => $this->updatedByAttribute]);

        return null;
    }

    /**
     * @getUpdateUserName
     * @return null|\yii\db\ActiveQuery
     */
    public function getUpdateUserName()
    {
        if(array_key_exists($this->createdByAttribute, $this->attributes)&&array_key_exists($this->updatedByAttribute, $this->attributes))
            return $this->createUser ? $this->updateUser->username : '- no user -';

        return null;
    }
}