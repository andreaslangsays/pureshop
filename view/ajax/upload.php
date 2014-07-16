<?php 	//var_dump($_FILES);
	if(isset($_FILES['foto']))
	{
		$filename =	$_FILES['foto']['name'];
		$filetype =	$_FILES['foto']['type'];
		$tmp_name = $_FILES['foto']['tmp_name'];
		$upload_error   = $_FILES['foto']['error'];
		if($upload_error==0)
		{
			$typearr= array('image/jpeg', 'image/png', 'image/gif', 'image/jpg');
			if(in_array($filetype,$typearr))
			{
				if(is_uploaded_file($_FILES['foto']['tmp_name']))
				{
					if(move_uploaded_file($tmp_name,'/srv/www/vhosts/if-bi.com/httpdocs/t3/shop/images/mailupload/'.basename($filename)))
					{
						echo "true";
					}
					else
					{
						header("HTTP/1.0 404 not found");
					}
				}
			}
			else
			{
				header("HTTP/1.0 404 not found");
			}
			//mail('andreas.lang@Bruesselser-Kakaoroesterei.de',"TESTE FOTOUPLOAD", utf8_decode($filename));
		}
		else
		{
			header("HTTP/1.0 404 not found");
		}
	}
	else
	{
		header("HTTP/1.0 404 not found");
	}?>