<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>
<!-- 转化为HTML实例 -->
<h1><?=Html::encode($view_hello_str);?></h1>
<!-- 完全过滤JavaScript代码 -->
<h1><?=HtmlPurifier::process($view_hello_str);?></h1>

<!-- 加载另一个视图组件 -->
<?php echo $this->render('about', array('v_hello_str'=>'Hello world!'));?>

<!-- 数据块 -->
<?php $this->beginBlock('block1');?>
<h1>index</h1>
<?php $this->endBlock();?>