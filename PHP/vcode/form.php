<?php
	header('content-type:text/html;charset=UTF-8');
	if (isset($_REQUEST['authcode'])) {
		session_start();

		if (strtolower($_REQUEST['authcode']) == $_SESSION['authcode']) {
			echo "<font color='#0000CC'>输入正确</font>";
		}else{
			echo "<font color='#CC0000'><b>输入错误</b></font>";
		}
		exit();
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>确认验证码</title>
</head>
<body>
	<form method="post" action="./form.php">
		<p>验证码图片：
			<img id="img" src="./vcode.php?r=<?php echo rand();?>" width="100" height="30">
			<a href="javascript:void(0)" onclick="document.getElementById('img').src='./vcode.php?r='+Math.random()">看不清</a>
		</p>
		<p>请输入图片中的内容：<input type="text" name="authcode" value="" /></p>
		<p><input type="submit" value="提交" style="6px 20px" /></p>
	</form>
</body>
</html>