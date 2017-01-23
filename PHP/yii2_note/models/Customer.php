<?php
namespace app\models;

use yii\db\ActiveRecord;
/**
* Customer和数据库中的表的名称保持一致
*/
class Customer extends ActiveRecord
{
	public function getOrders(){
		return $this->hasMany(Order::className(),['customer_id'=>'id'])->asArray();
	}
	
}