/**
 * 下载安装 Mongodb
 * 设置MongoDB 为wondows服务项( 配置环境变量 d:/MongoDB/bin) wind + R  cmd.exe
 * mongod --logpath D:/mongodb/logs/logs.txt --logappend --dbpath d:/mongodb/data --directoryperdb --serviceName MongoDB --auth -install
 * 参数说明
 * --logpath 			日志文件地址
 * --logappend			添加日志的方式为追加
 * --dbpath				指定MongoDB数据存放的路径
 * --directoryperdb		MongoDB按照数据库的不同，针对每一个数据库都建立一个目录，所谓的“目录每数据库
 * --auth				是否使用用户验证
 * --serviceName		安装Windows服务时使用的服务名
 * --install			安装MongoDB服务
 * 启动MongoDB服务 net start MongoDB
 * 关闭MongoDB服务 net stop MongoDB
 * 使用MongoDB 需要先启动MongoDB 然后 mongo
 */

/**
 * 添加用户使用db.createUser( user , writeConcern ) 函数使用说明
 * @param object user 			这个文档创建关于用户的身份认证和访问信息
 * @param mixed writeConcern	这个文档描述保证MongoDB提供写操作的成功报告。
 */
var user = {
	'user' : 'root',								// 用户名
	'pwd': 'lovemeljx',								// 密码
	'customData' : { employeeId: 12345 },			// 用户说明
	'roles': [				
		{ role: 'clusterAdmin', db: 'admin' },
		{ role: 'readAnyDatabase', db: 'admin' },
		'readWrite'
	]												// 用户角色
};
var user  = {
	'user' : 'root',
	'pwd': 'lovemeljx',
	'customData' : { employeeId: 12345 },
	'roles': [
		{ role: 'clusterAdmin', db: 'admin' },
		{ role: 'readAnyDatabase', db: 'admin' },
		'readWrite'
	]
};
var write = { w: "majority" , wtimeout: 5000 } ;
db.createUser( user , write ) ;
/*
	Built-In Roles（内置角色）：
		1. 数据库用户角色：read、readWrite;
		2. 数据库管理角色：dbAdmin、dbOwner、userAdmin；
		3. 集群管理角色：clusterAdmin、clusterManager、clusterMonitor、hostManager；
		4. 备份恢复角色：backup、restore；
		5. 所有数据库角色：readAnyDatabase、readWriteAnyDatabase、userAdminAnyDatabase、dbAdminAnyDatabase
		6. 超级用户角色：root  
		// 这里还有几个角色间接或直接提供了系统超级用户的访问（dbOwner 、userAdmin、userAdminAnyDatabase）
		7. 内部角色：__system
		PS：关于每个角色所拥有的操作权限可以点击上面的内置角色链接查看详情。
	writeConcern文档( 官方说明 )
		w选项：允许的值分别是 1、0、大于1的值、"majority"、<tag set>.
		j选项：确保mongod实例写数据到磁盘上的journal（日志）,这可以确保mongd以外关闭不会丢失数据。设置true启用.
		wtimeout：指定一个时间限制,以毫秒为单位。wtimeout只适用于w值大于1.
*/
// 登录验证
// mongo -u root -p lovemeljx --authenticationDatabase test;
db.auth('root' , 'lovemeljx') ; // 验证

/* 删除用户 */
db.system.user.remove({'user':'root'});

/* MongoDB添加库 */
use mydata					// 选择库
db.dropDatabase();			// 删除库

/* MongoDB添加表 */
db.createCollection('collection_name'); 	// 添加表
db.collection_name.drop() ;					// 删除表

/* MongoDB 数据库操作 */
// 查询 find() ^.^ --->
db.collection_name.find() ;	
// 查询所有 SQL: SELECT * FROM `user` ;	
db.user.find() ; 
// 1.指定返回指定的列 ( 键 ) SQL：SELECT `name`,`age` FROM `user` ;
db.user.find({},{'name':1,'age':1}) ;	/* 补充说明:第一个{} 放where条件 第二个{} 指定那些列显示和不显示( 0 不显示 1 显示) */
// 2.WHERE 条件 SQL: SELECT `name`,`age` FROM `user` WHERE `name`='123' ;
db.user.find({'name':'123'},{'name':1,'age':1});
// 3.使用 AND SQL: SELECT * FROM `user` WHERE `name`='123' AND `age`=21 ;
db.user.find({'name':'123','age':21});
// 4.使用 OR( $or ) SQL: SELECT * FROM `user` WHERE `name`='123' OR `age`=21 ;
db.user.find({'$or':[{'name':'123'},{'age':18}}]});
// 5.使用 < <= > >= !=( $lt , $lte , $gt , $gte , $ne ) SQL: SELECT * FROM `user` WHERE `id`>1 AND `id`<=3 ;
db.user.find({'_id':{'$gt':1,'$lte':3}}) ;
// 6.使用 IN NOT IN ( $in , $nin ) SQL: SELECT * FROM `user` WHERE `id` IN (1,3,5) ;
db.user.find({'_id':{'$in':[1,2,5]}});
// 7.匹配 null SQL: SELECT * FROM `user` WHERE `name` IS NULL ;
db.user.find({'name',null});
// 8.LIKE模糊查询 SQL: SELECT * FROM `user` WHERE `name` LIKE '%123%';
db.user.find({'name':/123/});
// 9.去掉重复的数据 DISTINCT SQL: SELECT DISTINCT(`name`) FROM `user`;
db.user.distinct('name'); 
// 10.统计查询 COUNT SQL: SELECT COUNT(*) FROM `user`;
/**
 * 查询统计条件可以传参数
 * db.user.count({'name':'123'});
 * db.find({'name':'123'}).count();
*/
db.user.count() ;
// 11.数组查询(MongoDB特有)
/**
 * skills = ['java','php','javascript','python'] ;
 */
// 找到skills
db.user.find({'skills':'php'});
// $all ** 必须同时包含
db.user.find({'skills':{'$all':['java','php']}});
// $size ** 数组长度等于
db.user.find({'skills':{'$size':2}});
// $slice ** 
// db.user.find({'skills':{'$slice':[1,1]}});
// 12.判断一个元素是否存在 $exists
db.td_user.find({'username':{'$exists':true}});
// 13.取反 $not
db.td_user.find({'username':{$not:/123/}});
// 14.sort()排序条件
db.user.find().sort({'_id':-1});
// 15.查询条数limit();
db.user.find().limit(10);
// 16.查询5-10间的数据
db.user.find().limit(5).skip(5);

// 新增数据 save() ^.^ --->
/**
 * db.collection_name.save( objNew ) 
 * 新增数据
 * @param object objNew 跟新的对象
 */
db.td_user.save({'name':'123','username':'ljx'});

// 修改 update() AND save() ^.^ --->
/**
 * db.collection_name.update( criteria , objNew , upsert , multi ) 
 * @param object 	criteria 查询条件对象
 * @param object	objNew	 更新的操作
 * @param boolean	upsert   不存在是否添加
 * @param boolean  	multi 	 是否全部跟新
 */
db.td_user.update( {'username':'123'} , {$set:{'username':456}}) ;							// 修改一条数据
db.td_user.update( {'user':'yanyan'} , {$set:{'name':'loveme'}} , false , true );			// 全部修改
db.td_user.update( {'user':'lovemeljx'} , {$set:{'user':'loveme-yanyan'}} , true , false ); // 不存在添加一条
db.td_user.update( {'user':'lovemeljx'} , {$set:{'user':'loveme-yanyan'}} , true , true );  // 添加所有
db.td_user.update( { "count" : { $gt : 15 } } , { $inc : { "count" : 1} } , false , true );	// 全更新了
db.td_user.update( { "count" : { $gt : 10 } } , { $inc : { "count" : 1} } , false , false );// 只更新了第一条
/* 跟新说明 */
// a.$inc 一个数字字段field增加value(数字)
db.td_user.update( {'username':'love1'} , {$inc:{'age':2}} );
// b.$set 修改值(所有值都支持)
db.td_user.update( {'username':"loveme"} , {$set:{'username':"mylove"}} );
// c.unset 删除字段
db.td_user.update( {'username':'mylove'} , {$unset:{'age':1}} );
// d.push 将值追加到数组中
db.td_user.update( {'username':'mylovelx'} , {$push:{'age':23}} );
db.td_user.update( {'username':'mylovelx'} , { $push : { "test1": "ccc" } } );
db.td_user.update( {'username':'mylove'} , { $set : { "age" : ["aaa","bbb"] } } );
db.td_user.update( {'user':/123/} , {$set:{'_id':9}} );

// 删除和修改数据 remove() AND findAndModify() AND runCommand() ^.^ --->
/**
 * remove( criteria ) 
 * @param object criteria 查询删除的对象 
 */
db.user.remove({'_id':0});

/**
 * findAndModify( objNew ) objNew.remove 和 objNew.update 不能同时存在 runCommand()同
 * @param objNew.query 	查询对象
 * @param objNew.sort  	排序条件
 * @param objNew.update 修改的数据
 * @param objNew.remove 是否删除数据
 */
db.user.findAndModify({
	query:{'_id':1},
	sort:{'_id':-1},
	update:{$set:{'username':'loveme_user'}},
	remove:false ,
});

for ( var i = 100 ; i< 200 ; i ++  ) {
	db.user.save({'_id': i + 1 , 'username':'loveme_' + i , 'age' : 22});
}