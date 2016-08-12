<!DOCTYPE HTML>
<html>
 <head>
   <meta charset="utf-8">
   
   <link rel="stylesheet" type="text/css" href="/public/css/default.css">
 
 </head>
 <body>
	<p>
		<?php
			$rooms = '';
			for($i=1; $i<=$boardrooms; $i++)
				$rooms .= "<a href='/booker/index/b=" . $i ."'>Boadroom " . $i . "</a> | ";

			echo rtrim($rooms, '| ');
		?>
	</p>
	<h1>Boardroom Booker </h1>
	<?php
		require_once $template;
	?>
 </body>
</html>