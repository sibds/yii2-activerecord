<?php

namespace data;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $login
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
        ];
    }

    public function getCreated()
    {
        return $this->hasMany(Post::className(), ['create_by' => 'id']);
    }

    public function getUpdated()
    {
        return $this->hasMany(Post::className(), ['update_by' => 'id']);
    }
}
