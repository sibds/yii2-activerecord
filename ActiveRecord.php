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
use yii\helpers\ArrayHelper;


class ActiveRecord extends \yii\db\ActiveRecord
{
    use BeforeQueryTrait;

    //Status state
    const STATUS_UNLOCK = 0;
    const STATUS_LOCK = 1; //Blocking records

    public static $BEFORE_QUERY = ['locked' => self::STATUS_UNLOCK];


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

    public $lockedAttribute = 'locked';

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
        if ($this->hasAttribute($this->createdAtAttribute) && $this->hasAttribute($this->updatedAtAttribute)) {
                    $behaviors['timestamp'] = [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => [$this->createdAtAttribute, $this->updatedAtAttribute],
                    ActiveRecord::EVENT_BEFORE_UPDATE => $this->updatedAtAttribute,
                ],
                'value' => new Expression('NOW()'), //TODO: need to change for different DB
            ];
        }

        //Check blameable
        if ($this->hasAttribute($this->createdByAttribute) && $this->hasAttribute($this->updatedByAttribute)) {
                    $behaviors['blameable'] = [
                'class' => UserDataBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => [$this->createdByAttribute, $this->updatedByAttribute],
                    ActiveRecord::EVENT_BEFORE_UPDATE => $this->updatedByAttribute,
                ],
            ];
        }

        //Check trash
        if ($this->hasAttribute($this->removedAttribute)) {
            $behaviors['trash'] = [
                'class' => TrashBehavior::className(),
                'trashAttribute' => $this->removedAttribute,
            ];
        }

        //Check locked
        if ($this->hasAttribute($this->lockedAttribute)) {
            $behaviors['locked'] = [
                'class' => LockedBehavior::className(),
                'lockedAttribute' => $this->lockedAttribute,
            ];
        }
        
        if($this->isNestedSet()){
            $behaviors['tree'] = ArrayHelper::merge([
                'class' => \creocoder\nestedsets\NestedSetsBehavior::className(),
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'depthAttribute' => 'depth',
            ], ($this->hasAttribute('tree')?['treeAttribute' => 'tree']:[]));
        }

        return $behaviors;
    }

    public function isNestedSet(){
        return $this->hasAttribute('lft')&&$this->hasAttribute('rgt')&&$this->hasAttribute('depth');
    }

    /**
     * Duplicate entries in the table.
     * @return $this|null
     */
    public function duplicate() {
        $this->isNewRecord = true;

        foreach ($this->primaryKey() as $key) {
                    $this->$key = null;
        }

        if ($this->save()) {
            return $this;
        }
        return null;
    }
    
    
    /**
     * @author Vitaly Voskobovich <vitaly@voskobovich.com>
     */ 
    public static function listAll($keyField = 'id', $valueField = 'name', $asArray = true)
    {
        $query = static::find();
        if ($asArray) {
            $query->select([$keyField, $valueField])->asArray();
        }

        return ArrayHelper::map($query->all(), $keyField, $valueField);
    }
}
