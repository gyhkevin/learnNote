<?php
namespace app\models;

use yii\db\ActiveRecord;
/**
* Test和数据库中的表的名称保持一致
*/
class Test extends ActiveRecord
{
	public function rules()
	{
		return [
			['id','integer'],
			['title','string','length'=>[0,5]]
		];
	}
	
}