<?php
chdir('../../');
include('includes/ajax_top.php');
function resizeImage($filename, $max_width, $max_height)
{
    list($orig_width, $orig_height) = getimagesize($filename);

    $width = $orig_width;
    $height = $orig_height;

    # taller
    if ($height > $max_height) {
        $width = ($max_height / $height) * $width;
        $height = $max_height;
    }

    # wider
    if ($width > $max_width) {
        $height = ($max_width / $width) * $height;
        $width = $max_width;
    }

    $image_p = imagecreatetruecolor($width, $height);

    $image = imagecreatefromjpeg($filename);

    imagecopyresampled($image_p, $image, 0, 0, 0, 0, 
                                     $width, $height, $orig_width, $orig_height);

    return $image_p;
}

if(true || $customer->login)
{
	if(isset($_GET['xid']))
	{
		$asset=(int)mysql_escape_string($_GET['xid']);
		if($asset > 0)
		{
			$entry=push_db_fetch_array(push_db_query("SELECT * FROM downloads WHERE id ='" . $asset . "'"));
			switch($entry['filetype'])
			{
				case 'PDF':
					$tmpn=md5($entry['filename']).".jpg";
					if(!is_file("download/tempfiles/$tmpn"))
					{
						exec("convert  download/" . $entry['ref_type'] . "/" . $entry['filename'] . "[0] download/tempfiles/$tmpn");
					}
					header('Content-Type: image/jpeg');
					chdir("download/tempfiles/");
					$width=(isset($_GET['w']) && intval($_GET['w'])>1)?intval($_GET['w']):250;
					$height=(isset($_GET['h']) && intval($_GET['h'])>1)?intval($_GET['h']):250;
					$im = resizeImage($tmpn,$width,$height); 
					imagejpeg($im);
				break;
				case 'IMG':
					$tmpn=$entry['filename'].".jpg";
					header('Content-Type: image/jpeg');
					chdir("download/" . $entry['ref_type'] . "/");
					$width=(isset($_GET['w']) && intval($_GET['w'])>1)?intval($_GET['w']):250;
					$height=(isset($_GET['h']) && intval($_GET['h'])>1)?intval($_GET['h']):250;
					$im = resizeImage($tmpn,$width,$height); 
					imagejpeg($im);
				
				break;
				default:
				break;
			
			}
		}
	}
}
?>