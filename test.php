<?php

?>
<html>
<head>
	<title>Test get_tags</title>
</head>
<body>
	<form method="post">
		<p>
			html:<br>
			<textarea name="html"><?=$_POST['html']?></textarea><br>
		</p>
		<p>
			CSS Selector:<br>
			<input type="text" name="selector" placeholder="selector" value="<?=$_POST['selector']?>"><br>
		</p>
		<input type="submit" value="GO test">
	</form>
	<?php
		if (isset($_POST['html']) &&  isset($_POST['selector'])) {
			include('get_tags.php');
			$arResult = get_tags($_POST['selector'], $_POST['html'], false);
			echo 'Result: <br><pre>';
			echo str_replace('<', '&lt;', print_r([$arResult], true));
			echo '</pre>';
		}
	?>
</body>
</html>