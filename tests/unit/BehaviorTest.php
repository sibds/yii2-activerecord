<?php
/**
 * Created by PhpStorm.
 * User: vadim
 * Date: 30.04.15
 * Time: 1:25
 */

use Codeception\Util\Debug;

class BehaviorTest extends \yii\codeception\TestCase
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

    private function loginUser($id){
        return Yii::$app->user->login(data\User::findIdentity($id));
    }

    public function setUp()
    {
        $this->mockApplication(require(Yii::getAlias($this->appConfig)));
        if(Yii::$app->user->isGuest)
            $this->loginUser(100);
    }

    public function tearDown()
    {
        data\Post::deleteAll();
        parent::tearDown();
    }

    public function testCreatePostByAdmin()
    {
        $post = new data\Post();

        //add test data
        $post->content = "test content";
        $post->save();

        $this->assertTrue($post->created_by==100&&$post->updated_by==100);
    }

    /**
     *  @depends testCreatePostByAdmin
     */
    public function testChangePostByDemo(){
        $this->assertTrue(data\Post::find()->count()>0);

        $post = data\Post::find()->one();

        $this->loginUser(101);
        $post->content = "test content change";
        $post->save();

        $this->assertTrue($post->created_by==100&&$post->updated_by==101);
    }

    /**
     *  @depends testChangePostByDemo
     */
    public function testDelete(){
        $countBefore = data\Post::find()->count();
        $post = data\Post::find()->one();

        $this->loginUser(100);
        $post->delete();

        $countAfter = data\Post::find()->count();

        $this->assertTrue($countBefore==$countAfter+1);
    }

    private function loadData(){
        for($i=1;$i<=10;$i++){
            if($i%2){
                $this->loginUser(100);
            }else{
                $this->loginUser(101);
            }
            $post = new data\Post();
            $post->content = 'Test #'.$i;
            $post->save();
        }
    }

    /**
     *  @depends testDelete
     */
    public function testDuplicate(){
        $this->loadData();

        $countBefore = data\Post::find()->count();
        $post = data\Post::find()->one();

        $oldId = $post->id;

        $newpost = $post->duplicate();

        $countAfter = data\Post::find()->count();

        $this->assertTrue($oldId != $newpost->id);

        $this->assertTrue($countBefore+1==$countAfter);
    }
} 