<?php 
/**
 * PHP操作MongoDB
 * author 	liujx
 * version 	1.0
 * date 	2015-6-4
 * 开启了mongodb扩展php.ini extension = php_mongo.dll
 */
	// *** 1.PHP连接MongoDB ^.^ --->
	$connection = new Mongo() ;
	/*
		$conn = new Mongo( 'localhost' , array( 'replicaSet' => true ) ) ; # 负载均衡
		$conn = new Mongo( 'localhost' , array( 'persist' => 't' ) ) ;	   # 持久连接
		$conn = new Mongo( 'mongodb://root:123@localhost' ) ;			   # 带用户名和密码
	*/
	
	// *** 2.选择数据库 和 集合 ^.^ --->
	$db 		= $connection->mydata ;					// 选择数据库
	$collection = $db->user ;							// 选择集合
	// $collection = $db->selectCollection( 'user' ) ; 	# 选择集合的另一种方式
	// $collection = $db->mydata->user ;				# 简洁写法

	/**
	 * *** 3.添加数据( $collection->insert() ) ^.^ --->
	 * insert( $array [, $arr = array(
	 *		'safe' 		=> false ,
	 *		'fsync' 	=> false ,
	 *		'timeout' 	=> 10000 ,
	 *	)])
	 * @param array $array 添加数据信息
	 * @param $arr 额外参数信息( 可选 )
	 *		$arr['safe']  	boolean 是否安全写入(false)
	 *		$arr['fsync'] 	boolean 是否强制插入到同步磁盘(false)
	 *		$arr['timeout'] int		超时间( 毫秒 ) 
	 */
	$data = array(
		'username' => 'myCeshi' ,
		'age'	   => 12 ,
		'time'     => time() ,
		'skills'   => array( 'php', 'javascript' , 'css' , 'html' ) ,
	) ;
	
	$result = $collection->insert( $data ) ;			// 简单新增 , 返回布尔值 bool(true)
	
	/* 向集合中安全插入数据,返回插入状态(数组) */
	$data = array(
		'username' => 'liujx' ,
		'age' 	   => 22 ,
	) ;
	
	$result = $collection->insert( $data , true ) ;		// 用于等待MongoDB完成操作,以便确定是否成功(当有大量记录插入时使用该参数会比较有用)
	/*
		return array(
			'connectionId' => int 10
			'n' => int 0
			'syncMillis' => int 0
			'writtenTo' => null
			'err' => null
			'ok' => float 1
		) ;
	*/
	 
	/**
	 * *** 4.更新数据 update() ^.^ --->
	 * update( array $criteria , array $new [ , array $options = array() ])
	 * @param 	array 	$criteria 查询修改对象数组
	 * @param 	array 	$new      修改成的新对象数组
	 * @param 	array 	$options  更新的其他设置数组
	 * @return 	boolean 返回布尔值
	 *
	 * 更新数据说明
	 * $inc 	自增
	 * $set 	修改
	 * $unset 	删除字段
	 */
	/* 修改更新 */
	$criteria = array( 'username' => 'myCeshi' ) ;
	$update   = array( 'username' => 'myTitle' , 'name' => 'xiaoTitle'  ) ;
	$result   = $collection->update( $criteria , array( '$set' => $update ) ) ;		// 修改一条
	
	/* 替换更新 */
	$criteria = array( 'username' => 'myCeshi' ) ;
	$replace  = array( 'myname' => '123' , 'title' => 'nonoe' ) ;
	$result   = $collection->update( $criteria , $replace ) ;						// 替换一条
	
	/* 批量更新 */
	$criteria = array( 'username' => 'myCeshi' ) ;
	$update   = array( 'myname' => '123' , 'title' => 'nonoe' ) ;
	$result   = $collection->update( $criteria , array( '$set' => $update ) , array( 'multiple' => true ) ) ;
	
	/* 自动累加 */
	$criteria = array( 'username' => 'myTitle' ) ;
	$inc 	  = array( 'age' => 5 ) ;
	$result   = $collection->update( $criteria , array( '$inc' => $inc ) ) ;	  	// age累加5( 修改一条 )
	
	// *** 5.删除数据 remove() ^.^ --->
	$criteria = array( 'username' => 'myTitle' ) ;
	$result   = $collection->remove( $criteria ) ;
	// $collection->remove() 						# 清空集合
	
	/* 删除指定的MongoId */
	$id 	= new MongoId('557016db90428ba817000012') ;
	$result = $collection->remove(array( '_id' => $id )) ; 
	
	// *** 6.查询数据 find() 和 findOne() ^.^ --->
	/* 查询数据数目 count() */
	$num = $collection->count() ;						// 查询总条数
	$criteria = array( 'username' => 'liujx' ) ;
	$num = $collection->count( $criteria ) ;			// 查询满足条件数据条数
	$num = $collection->count( array( 'age' => array( '$gt' => 12 , '$lt' => 30  ) ) ) ;
	/*
		注:$gt为大于、$gte为大于等于、$lt为小于、$lte为小于等于、$ne为不等于、$exists不存在
	*/
	
	/**
	 * findOne() 查询一条数据 
	 * @param 	array 	$criteria 查询条件数组
	 * @return 	array 	返回数组( 没有数据返回 null )
	 */
	$data = $collection->findOne() ;
	$data = $collection->findOne( array( 'username' => 'liujx' ) ) ;
	
	/**
	 * find() 查询数据
	 * @param	array 	$criteria 查询条件数组
	 * @return  object 	返回对象( 没有返回 null )
	 * 注意:
	 * 在我们做了find()操作，获得$cursor游标之后，这个游标还是动态的.
	 * 换句话说,在我find()之后,到我的游标循环完成这段时间,如果再有符合条件的记录被插入到collection,那么这些记录也会被$cursor 获得.
	 * 如果你想在获得$cursor之后的结果集不变化,需要这样做：
	 * $cursor = $collection->find();
	 * $cursor->snapshot();
	 */
	$data = $collection->find() ;						// 查询所有数据,返回对象
	foreach ( $data as $key => $value )
	{
		// var_dump( $value ) ;							// 数组
	}
	
	/* 限制查询条数limit() */
	$data = $collection->find()->limit(5) ;
	foreach ( $data as $key => $value )
	{
		// var_dump( $value ) ;
	}
	
	/* 确定查询开始位置skip()  */
	$data = $collection->find()->limit(10)->skip(5) ;
	foreach ( $data as $key => $value )
	{
		// var_dump( $value ) ;
	}
	
	/* 排序条件查询sort() */
	$data = $collection->find()->limit(10)->sort(array( '_id' => -1 )) ;
	// $data = $data->snapshot() ; 					# 排序和snapshot() 查询出对象不能使用foreach
	foreach ( $data as $key => $value )
	{
		// var_dump( $value ) ;
	}
	
	/* 指定查询的列 fields() true 显示 false 不显示 */
	$data = $collection->find()->limit(10)->fields(array('_id' => false )) ;	// _id 列不显示
	// $data = $collection->find()->limit(10)->sort(array('_id' => -1 ))->fields(array('username'=> true)) ; # 只显示username(_id默认显示)
	foreach ( $data as $key => $value )
	{
		// var_dump( $value ) ;
	}
	
	/**
	 * *** 7.索引操作 ensureIndex() ^.^ --->
	 * ensureIndex( array() , array( 'name' =>'索引名称' ,' background' = true , 'unique' = true))
	 * 详见:http://www.php.net/manual/en/mongocollection.ensureindex.php
	 */
	$collection->ensureIndex(array(‘age’ => 1,’type’=>-1)); 							// 表示降序 -1表示升序
	$collection->ensureIndex(array(‘age’ => 1,’type’=>-1),array(‘background’=>true)); 	// 索引的创建放在后台运行(默认是同步运行)
	$collection->ensureIndex(array(‘age’ => 1,’type’=>-1),array(‘unique’=>true)); 		// 该索引是唯一的
	
	// *** 8.关闭连接 close() ^.^ --->
	$connection->close() ;
?>