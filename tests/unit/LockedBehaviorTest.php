<?php

/**
 * Created by PhpStorm.
 * User: vadim
 * Date: 05.02.16
 * Time: 13:04
 */
class LockedBehaviorTest extends \yii\codeception\TestCase
{
    public $appConfig = '@tests/unit/_config.php';

    /**
     * @var Connection test db connection
     */
    protected $dbConnection;
    public static function setUpBeforeClass()
    {
        if (!extension_loaded('pdo') || !extension_loaded('pdo_sqlite')) {
            static::markTestSkipped('PDO and SQLite extensions are required.');
        }
    }
    public function setUp()
    {
        $this->mockApplication(\yii\helpers\ArrayHelper::merge(
            require(Yii::getAlias($this->appConfig)),[
            'components' => [
                'db' => [
                    'class' => '\yii\db\Connection',
                    'dsn' => 'sqlite::memory:',
                ]
            ]
        ]));
        $columns = [
            'id' => 'pk',
            'status' => 'integer',
        ];
        Yii::$app->getDb()->createCommand()->createTable('test_auto_locked', $columns)->execute();
    }
    public function tearDown()
    {
        Yii::$app->getDb()->createCommand()->delete('test_auto_locked')->execute();
        Yii::$app->getDb()->close();
        parent::tearDown();
    }
    // Tests :
    public function testFields(){
        $model = new ActiveRecordLocked();
        $this->assertTrue(array_key_exists($model->lockedAttribute, $model->attributes));
    }

    public function testNewRecord()
    {
        $model = new ActiveRecordLocked();
        $model->save(false);

        $this->assertFalse($model->{$model->lockedAttribute} == true);
    }

    /**
     * @depends testNewRecord
     */
    public function testLockUnlockRecord()
    {
        $model = new ActiveRecordLocked();
        $model->save(false);

        $model->lock();

        $this->assertTrue($model->{$model->lockedAttribute} == true, 'Function lock() NOT work!');

        $model->unlock();

        $this->assertTrue($model->{$model->lockedAttribute} == false, 'Function lock() NOT work!');
    }
}

/**
 * Test Active Record class with [[TimestampBehavior]] behavior attached.
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $updated_at
 */
class ActiveRecordLocked extends sibds\components\ActiveRecord
{
    public $lockedAttribute = 'status';

    public static function tableName()
    {
        return 'test_auto_locked';
    }
}