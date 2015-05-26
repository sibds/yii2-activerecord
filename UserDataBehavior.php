<?php
/**
 * Created by PhpStorm.
 * User: vadim
 * Date: 26.05.15
 * Time: 15:12
 */

namespace sibds\behaviors;


use yii\behaviors\BlameableBehavior;

class UserDataBehavior extends BlameableBehavior {

    /**
     * @getCreateUser
     * @return null|\yii\db\ActiveQuery
     */
    public function getCreateUser()
    {
        /* @var $owner BaseActiveRecord */
        $owner = $this->owner;

        if ($owner->hasAttribute($this->createdByAttribute) && $owner->hasAttribute($this->updatedByAttribute))
            return $owner->hasOne(User::className(), ['id' => $this->createdByAttribute]);

        return null;
    }

    /**
     * @getCreateUserName
     * @return null|\yii\db\ActiveQuery
     */
    public function getCreateUserName()
    {
        /* @var $owner BaseActiveRecord */
        $owner = $this->owner;

        if ($owner->hasAttribute($this->createdByAttribute) && $owner->hasAttribute($this->updatedByAttribute))
            return $this->createUser ? $this->createUser->username : '- no user -';

        return null;
    }

    /**
     * @getUpdateUser
     * @return null|\yii\db\ActiveQuery
     */
    public function getUpdateUser()
    {
        /* @var $owner BaseActiveRecord */
        $owner = $this->owner;

        if ($owner->hasAttribute($this->createdByAttribute) && $owner->hasAttribute($this->updatedByAttribute))
            return $this->hasOne(User::className(), ['id' => $this->updatedByAttribute]);

        return null;
    }

    /**
     * @getUpdateUserName
     * @return null|\yii\db\ActiveQuery
     */
    public function getUpdateUserName()
    {
        /* @var $owner BaseActiveRecord */
        $owner = $this->owner;

        if ($owner->hasAttribute($this->createdByAttribute) && $owner->hasAttribute($this->updatedByAttribute))
            return $this->createUser ? $this->updateUser->username : '- no user -';

        return null;
    }
}