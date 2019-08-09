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

	

	

	elseif($mod == "returpembelian" AND $act == "simpan")
	{
		echo print_r($_POST);
		// $no_transaksi
		$pilih=$_POST['pilih'];
$jumlah_baris=count($pilih);
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
					// header("location:../../med.php?mod=returpembelian&act=printout&id=".$no_transaksi);
				}
				//commit();
			
				

	



?>