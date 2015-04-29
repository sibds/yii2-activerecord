<?php
/**
 * Created by PhpStorm.
 * User: vadim
 * Date: 30.04.15
 * Time: 1:25
 */

class BehaviorTest extends \yii\codeception\TestCase
{
    public $appConfig = '@tests/unit/_config.php';

    private function saveAndReload($class, $id, $post)
    {
        $class = 'data\\'.$class;
        $model = $class::findOne($id);

        $this->assertNotEmpty($model, 'Load model');
        $this->assertTrue($model->load($post), 'Load POST data');
        $this->assertTrue($model->save(), 'Save model');

        $model = $class::findOne($id);
        $this->assertNotEmpty($model, 'Reload model');

        return $model;
    }
    /*
    public function testCreatePostByAdmin()
    {
        $post = new Post();

        //add test data
        $post->content = "test content";
        $post->save;

    }
    */
} 