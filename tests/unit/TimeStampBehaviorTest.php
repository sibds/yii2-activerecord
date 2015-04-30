<?php
/**
 * Created by PhpStorm.
 * User: vadim
 * Date: 30.04.15
 * Time: 2:08
 */

class TimeStampBehaviorTest extends \yii\codeception\TestCase
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
            'create_at' => 'integer',
            'update_at' => 'integer',
        ];
        Yii::$app->getDb()->createCommand()->createTable('test_auto_timestamp', $columns)->execute();
    }
    public function tearDown()
    {
        Yii::$app->getDb()->createCommand()->delete('test_auto_timestamp')->execute();
        Yii::$app->getDb()->close();
        parent::tearDown();
    }
    // Tests :
    public function testFields(){
        $model = new ActiveRecordTimestamp();
        $this->assertTrue(array_key_exists('create_at', $model->attributes));
        $this->assertTrue(array_key_exists('update_at', $model->attributes));
    }
    public function testNewRecord()
    {
        $currentTime = time();

        $model = new ActiveRecordTimestamp();
        $model->save(false);

        $this->assertTrue($model->create_at >= $currentTime);
        $this->assertTrue($model->update_at >= $currentTime);
    }
    /**
     * @depends testNewRecord
     */
    public function testUpdateRecord()
    {
        $currentTime = time();
        $model = new ActiveRecordTimestamp();
        $model->save(false);

        $enforcedTime = $currentTime - 100;

        $model->create_at = $enforcedTime;
        $model->update_at = $enforcedTime;

        $model->save(false);

        $this->assertEquals($enforcedTime, $model->create_at, 'Create time has been set on update!');
        $this->assertTrue($model->update_at >= $currentTime, 'Update time has NOT been set on update!');
    }
}

/**
 * Test Active Record class with [[TimestampBehavior]] behavior attached.
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $updated_at
 */
class ActiveRecordTimestamp extends sibds\components\ActiveRecord
{
     public static function tableName()
    {
        return 'test_auto_timestamp';
    }
}