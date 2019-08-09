<?php

$string = "<?php
	session_start();
	include\"../../lib/conn.php\";
	include\"../../lib/all_function.php\";


	if(!isset(\$_SESSION['login_user'])){
		header('location: ../../login.php'); // Mengarahkan ke Home Page
	}

	if(isset(\$_GET['mod']) && isset(\$_GET['act']))
	{
		\$mod = \$_GET['mod'];
		\$act = \$_GET['act'];
	}
	else
	{
		\$mod = \"\";
		\$act = \"\";
	}

	if(\$mod == \"".$mod."\" AND \$act == \"simpan\")
	{
		//variable input
		\$".$pk." = trim(\$_POST['id']);";
		$arr = array();
		$arr2 = array();
		foreach ($non_pk as $row) {
			$arr[] = "\n\t\t\t\t\t\t\t\t\t\t".$row['column_name'];
			$arr2[] = "\n\t\t\t\t\t\t\t\t\t\t'\$".$row['column_name']."'";
			$string .= "\n\t\t\$".$row['column_name']."= anti_inject(\$_POST['".$row['column_name']."']);";
		}

		$string .="\n\n\t\tmysql_query(\"INSERT INTO ".$nama_table."('".$pk."', ";
									$string .= implode(", ", $arr);
									$string .=")
									VALUES (\$".$pk.", ";
									$string .= implode(", ", $arr2);
									$string .=")\") or die(mysql_error());
		flash('example_message', '<p>Berhasil menambah data biaya.</p>' );

		echo\"<script>
			window.history.go(-2);
		</script>\";
	}

	elseif (\$mod == \"".$mod."\" AND \$act == \"edit\") 
	{
		//variable input
		\$".$pk." = trim(\$_POST['id']);";
		$arr3 = array();
		foreach ($non_pk as $row) {
			$arr3[] = "\n\t\t\t\t\t\t\t\t\t\t".$row['column_name']."= '\$".$row['column_name']."'";
			$string .= "\n\t\t\$".$row['column_name']."= anti_inject(\$_POST['".$row['column_name']."']);";
		}

		$string .= "\n\n\t\tmysql_query(\"UPDATE ".$nama_table." SET ";
						$string .= implode(", ", $arr3);
					$string .= " 
					WHERE ".$pk." = '\$_POST[id]'\") or die(mysql_error());

		flash('example_message', '<p>Berhasil mengubah data biaya.</p>');

		echo\"<script>
			window.history.go(-2);
		</script>\";
	}

	elseif (\$mod == \"".$mod."\" AND \$act == \"hapus\") 
	{
		mysql_query(\"DELETE FROM ".$nama_table." WHERE ".$pk." = '\$_GET[id]'\") or die(mysql_error());
		flash('example_message', '<p>Berhasil menghapus data biaya kuliah.</p>' );
		echo\"<script>
			window.history.back();
		</script>\";	
	}

?>";


createFile($string, "../mod/" . $nama_folder . "/" . $file2);

?>