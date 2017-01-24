#yii2
### Namespace
> 1. 命名空间的定义，只是抽象中的将类存放的地址，而不是真实的物理路径。
比如：`namespace app\lib\action`，这个类在实际路径中是找不到的。
目的是为了解决命名冲突，比如`class A `中有一个`index()`方法，在`class B `中也有一个`index()`方法，
同时都要使用时，放在不同的命名空间后，就可以正常使用。
> 2. 使用命名空间`use app\lib\action`或`use app\lib\action as BAction`，实例化`$abc = new action();`。
访问全局类，实例化`$abc = new \A();`。

### Request
### Response
### Session
> 1. session保存位置，在php.ini中查找`session.save_path`对应的路径。
> 2. 用不同的浏览器向服务器发送请求的时候，会保存不同的session值，以区分。

### View
> 1. 在视图中，抽离出页面中的公共部分，需要将公共的内容放置在`layouts`文件夹下，默认创建`main.php`，
也可以自己创建一个`common.php`文件。在对应的控制器方法中，调用`render()`方法。
`render()`方法在被调用时会将变量存储在`$content`变量中。然后告诉`render()`去加载`$layout`变量所指定的公共部分。
> 2. 在视图中，创建数据块`$this->beginBlock('block1')`，并且在结束时`$this->endBlock()`。数据块的作用在于
可以动态的改变公共页面中的内容。

### Model
> 1. 预防SQL注入，使用占位符的方式。比如：使用`findBySql()`方法时，传递第二个参数`array(':id'=>1)`，
用于替换如`$sql = 'select * from test where id=:id';`中的`:id`。
> 2. 若AR对象是由`new`操作符初始化出来的，`save()`方法会在表里插入一条数据； 如果一个 AR 是由`find()`方法获取来的，
则`save()`会更新表里的对应行记录。

### Cache
####数据缓存
####片段缓存
####分页缓存
####HTTP缓存
> 1.服务器通过`Last-Modified`头和`Etag`的变化，返回给浏览器，判断是否使用缓存。如果使用缓存，则返回`304`状态。