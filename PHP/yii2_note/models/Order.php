<?php
namespace app\models;

use yii\db\ActiveRecord;
/**
* Customer和数据库中的表的名称保持一致
*/
class Order extends ActiveRecord
{
	
	public function getCustomer()
	{
		return $this->hasOne(Customer::className(), ['id'=>'customer_id']);
	}
}