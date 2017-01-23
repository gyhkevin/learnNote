<?php
namespace app\controllers;
use yii\web\Controllers;
use yii\web\Cookie;
use app\models\Test;
use app\models\Customer;
use app\models\Order;
 /**
 * yii2
 */
class HelloController extends Controller
{

	public $layout = 'common';

	// model
	function actionTestMd()
	{
		// 查询数据
		// 1.直接SQL
		$sql = 'select * from test where id=:id';
		$results = Test::findBySql($sql, array(':id'=>1))->all();

		// 2.以数组方式
		// id=1
		$results = Test::find()->where(['id']=>1)->all();
		// id>0
		$results = Test::find()->where(['>','id',0])->all();
		// id>=1 and id<=2
		$results = Test::find()->where(['between','id',1,2])->all();
		// title like '%title%'
		$results = Test::find()->where(['like','title','title1'])->all();

		// 查询结果转化成数组
		$results = Test::find()->where(['between','id',1,2])->asArray()->all();
		// 批量查询
		foreach (Test::find()->batch(2) as $testVal) {
			print_R(count($testVal));
		}

		print_R($results);

		// 删除数据
		$results = Test::find()->where(['id'=>1])->all();
		$results[0]->delete();
		Test::deleteAll('id>:id',array(':id'=>0));

		// 增加数据
		$test = new Test();
		$test->id = 3;
		$test->title = 'title3';
		$test->validate();
		if ($test->hasErrors()) {
			echo "Error";
			die();
		}
		$test->save();

		// 修改数据
		$test = Test::find()->where(['id'=>4])->one();
		$test->title = 'title4';
		$test->save();

		// 关联查询
		// 根据顾客查询订单
		$customer = Customer::find()->where(['name'=>'zhangsan'])->one();
		// $orders = $customer->getOrders();
		$orders = $customer->orders;
		// 关联查询结果缓存
		unset($customer->orders);
		// 重新获取数据
		$orders2 = $customer->orders;
		// 根据订单查询顾客
		$order = Order::find()->where(['id'=>1])->one();
		$customer = $order->customer;

		// 关联查询的多次查询
		// 查询一次后存储在orders的属性中
		$customer = Customer::find()->with('orders')->all();
		foreach ($customer as $cusVal) {
			// 调用$customer的orders属性
			$orders = $cusVal->orders;
		}

		print_r($customer);
	}

	// view
	function actionHelloview()
	{
		$hello_str = 'Hello God!';
		$test_arr = array(1,2);
		
		// 数据统一合并到一个数组中
		$data = array();
		$data ['view_hello_str'] = $hello_str;
		$data ['view_test_arr'] = $test_arr;
		// 返回给视图
		// return $this->renderPartial('index', $data);
		return $this->render('index',$data);
	}
	function actionAboutRender()
	{
		return $this->render('index'); // $content
	}

 	// request
 	function actionRequest()
 	{
 		$request = \YII::$app->request;
 		// get方式，第二个参数为默认值
 		echo $request->get('id');
 		echo $request->get('id', 20);
 		// post方式，第二个参数为默认值
 		echo $request->post('name', 333);

 		if ($request->isGet() {
 			echo "this is get mothed";
 		}

 		echo $request->userIp();
 	}

 	// response
 	function actionResponse()
 	{
 		$res = \YII::$app->response;
 		// 返回状态码
 		$res->statusCode = '404';

 		$res->headers->add('Pragma', 'no-cache');
 		$res->headers->set('Pragma', 'max-age=5');
 		$res->headers->remove('Pragma');

 		// 跳转
 		$res->headers->add('location','http://www.baidu.com');
 		// 临时的
 		$this->redirect('http://www.baidu.com', 302);
 		// 文件下载
 		$res->headers->add('content-disposition','attachment;filename="a.jpg"');
 		$res->sendFile('./robots.txt');
 		
 	}

 	// session
 	function actionSession()
	{
		$session = \YII::$app->session;

		$session->open();

		if ($session->isAction()) {
			echo "session is active";
		}
		// 以对象的方式
		$session->set('user','zhangsan');
		echo $session->get('user');
		$session->remove();
		// 以数组的方式
		$session['user'] = 'zhangsan';
		echo $session['user'];
		unset($session['user']);
		
		// flash特殊的session，只能响应一次结果
		// 请求 #1
		// 设置一个名为"postDeleted" flash 信息
		$session->setFlash('postDeleted', 'You have successfully deleted your post.');

		// 请求 #2
		// 显示名为"postDeleted" flash 信息
		echo $session->getFlash('postDeleted');

		// 请求 #3
		// $result 为 false，因为flash信息已被自动删除
		$result = $session->hasFlash('postDeleted');

		// Tip: For displaying Flash messages you can use yii\bootstrap\Alert widget in the following way:
		// 写在视图中：
		echo Alert::widget([
		   'options' => ['class' => 'alert-info'],
		   'body' => Yii::$app->session->getFlash('postDeleted'),
		]);
	}

	// cookie
	function actionCookie(){
		// 设置cookie
		$cookies = \YII::$app->response->cookies;
		$cookie_data = array('name'=>'user','value'=>'zhangsan');
		$cookies->add(new Cookie($cookie_data));
		$cookies->remove('id');
		// 获取cookie
		$cookies = \YII::$app->request->cookies;
		echo $cookies->getValue('user');
		// 第二个参数为默认值
		echo $cookies->getValue('users',20);
	}
}