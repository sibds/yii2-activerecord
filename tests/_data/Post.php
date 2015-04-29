<?php

namespace data;

use Yii;

/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property string $content
 * @property integer $create_at
 * @property integer $update_at
 * @property integer $create_by
 * @property integer $update_by
 */
class Post extends \sibds\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['create_at', 'update_at', 'create_by', 'update_by'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'create_by' => 'Create By',
            'update_by' => 'Update By',
        ];
    }
}
