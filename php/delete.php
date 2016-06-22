<?php
$img = $_POST['image'];
	if (file_exists( '../upload/'.$img)) 
		unlink('../upload/'.$img);
