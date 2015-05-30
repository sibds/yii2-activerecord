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
use sibds\behaviors\UserDataBehavior;
use sibds\behaviors\TrashBehavior;


class ActiveRecord extends \yii\db\ActiveRecord
{
    use BeforeQueryTrait;

    //Status state
    const STATUS_DEFAULT = 0;
    const STATUS_LOCK = 1; //Blocking records

    public static $BEFORE_QUERY = ['removed' => 0, 'status' => self::STATUS_DEFAULT];


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


    public $removedAttribute = 'removed';


    public function behaviors()
    {
        /*Sources:
         * https://yii2framework.wordpress.com/2014/11/15/yii-2-behaviors-blameable-and-timestamp/comment-page-1/
         * https://toster.ru/q/82962
         * */
        // If table not have fields, then behavior not use
        $behaviors = [];
        //Check timestamp
        if ($this->hasAttribute($this->createdAtAttribute) && $this->hasAttribute($this->updatedAtAttribute))
            $behaviors['timestamp'] = [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => [$this->createdAtAttribute, $this->updatedAtAttribute],
                    ActiveRecord::EVENT_BEFORE_UPDATE => $this->updatedAtAttribute,
                ],

            ];

        //Check blameable
        if ($this->hasAttribute($this->createdByAttribute) && $this->hasAttribute($this->updatedByAttribute))
            $behaviors['blameable'] = [
                'class' => UserDataBehavior::className(),
                'createdByAttribute' => $this->createdByAttribute,
                'updatedByAttribute' => $this->updatedByAttribute,
            ];

        //Check trash
        if ($this->hasAttribute($this->removedAttribute)) {
            $behaviors['trash'] = [
                'class' => TrashBehavior::className(),
                'trashAttribute' => $this->removedAttribute,
            ];
        }

        return $behaviors;
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            if ($this->hasAttribute('status'))
                if (empty($this->status) || is_null($this->status))
                    $this->status = self::STATUS_DEFAULT;
        }

        return parent::beforeSave($insert);
    }


    public function lock(){
        $this->status = self::STATUS_LOCK;
        $this->save();
    }

    public function unlock(){
        $this->status = self::STATUS_DEFAULT;
        $this->save();
    }

    /**
     * Duplicate entries in the table.
     * @return $this|null
     */
    public function duplicate(){
        $this->isNewRecord=true;

        foreach($this->primaryKey() as $key)
            $this->$key = null;

        if($this->save()){
            return $this;
        }
        return null;
    }
}