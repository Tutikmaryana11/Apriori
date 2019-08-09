<link rel="stylesheet" type="text/css" href="../../css/pace.css">
<script src="../../js/pace.min.js"></script>
<?php
	date_default_timezone_set('Asia/Jakarta');
	session_start();
	include"../../lib/conn.php";
	include"../../lib/all_function.php";
	include"../../lib/fungsi_transaction.php";


	if(!isset($_SESSION['login_user'])){
		header('location: ../../login.php'); // Mengarahkan ke Home Page
	}

	if(isset($_GET['mod']) && isset($_GET['act']))
	{
		$mod = $_GET['mod'];
		$act = $_GET['act'];
	}
	else
	{
		$mod = "";
		$act = "";
	}

	if($mod == "returpenjualan" AND $act == "add")
	{
		$cek_barang = mysql_query("SELECT * FROM tb_barang 
								WHERE kode_barang = '$_GET[id]'") or die(mysql_error());

		if (mysql_num_rows($cek_barang) > 0) {
			$disc = 0;
			if(!empty($_GET['disc']) AND is_numeric($_GET['disc']))
			{
				$disc = $_GET['disc'];
			}

			$b = mysql_fetch_assoc($cek_barang);

			$cek_det_barang = mysql_query("SELECT * FROM tb_detail_penjualan_tmp 
										WHERE kode_barang = '$_GET[id]' 
										AND petugas = '$_SESSION[login_id]'") or die(mysql_error());
			if(mysql_num_rows($cek_det_barang) > 0)
			{
				mysql_query("UPDATE tb_detail_penjualan_tmp SET qty = qty + 1 
							WHERE kode_barang = '$_GET[id]' 
							AND petugas = '$_SESSION[login_id]'") or die(mysql_error());
			}
			else
			{
				mysql_query("INSERT INTO tb_detail_penjualan_tmp (kode_barang,
																	harga,
																	disc,
																	qty,
																	petugas,
																	timestmp)
															VALUES('$_GET[id]',
																	'$b[harga_jual]',
																	$disc,
																	1,
																	'$_SESSION[login_id]',
																	NOW())") or die(mysql_error());
			}
				
				

			echo"<script>
				window.history.back();
			</script>";	
		}
		else
		{
			echo"Tidak barang!";
		}

	}

	elseif ($mod == "penjualan" AND $act == "batal") {
		mysql_query("DELETE FROM tb_detail_penjualan_tmp 
					WHERE kode_barang = '$_GET[id]' 
					AND petugas = '$_SESSION[login_id]'") or die(mysql_error());

		echo"<script>
			window.history.back();
		</script>";	
	}

	elseif($mod == "returpenjualan" AND $act == "simpan")
	{
		// $no_transaksi
		$pilih=$_POST['pilih'];
$jumlah_baris=count($pilih);
// echo $jumlah_baris;
		// print_r($_POST);
		// exit();
		$no_transaksi=$_POST['no_transaksi'];
	$kode_barang=$_POST['kode_barang'];
	$qty=$_POST['qty'];
	$harga=$_POST['harga'];
	$disc=$_POST['disc'];
	$keterangan=$_POST['keterangan'];
	// $petugas=$_POST['petugas'];
	$timestmp=$_POST['timestmp'];
for ($i=0; $i < $jumlah_baris; $i++) {
	
$qsimpandetail ="INSERT tb_retur_penjualan(no_transaksi,
										  	kode_barang,
										 	qty,
											harga, 
											disc, 
											petugas, 
											keterangan
											)
							VALUES('$no_transaksi', 
									'$kode_barang', 
									'$qty', 
									'$harga', 
									'$disc', 
									'$_SESSION[login_id]', 
									'$keterangan')";
	// echo $qsimpandetail;
	// exit();
	$simpan=mysql_query($qsimpandetail);
						if (!$simpan) {
							rollback();
							flash('example_message', '<p>Transaksi gagal.</p>', 'w3-red' );
							echo"<script>
								window.history.back();
							</script>";	
						}
					}
					commit();
					header("location:../../med.php?mod=returpenjualan&act=printout&id=".$no_transaksi);
				}
				//commit();
			
				

	elseif ($mod == "penjualan" AND $act == "hapus") {
		if(isset($_SESSION['hapuspenjualan']) AND $_SESSION['hapuspenjualan'] <> 'TRUE')
		{
			echo"<div class='w3-container w3-red'><p>Dilarang mengakses file ini.</p></div>";
			die();
		}
		else
		{
			mysql_query("DELETE FROM tb_penjualan WHERE no_transaksi = '$_GET[id]'") or die(mysql_error());
			flash('example_message', '<p>Berhasil menghapus data transaksi.</p>' );
			echo"<script>
				window.history.back();
			</script>";	
		}
			
	}



?>