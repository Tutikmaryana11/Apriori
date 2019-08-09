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

	if($mod == "penjualan" AND $act == "add")
	{
		$qty = $_GET['qty'];
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
																	$qty,
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

	elseif($mod == "penjualan" AND $act == "simpan")
	{
		$qtmp = mysql_query("SELECT * FROM tb_detail_penjualan_tmp 
							WHERE petugas = '$_SESSION[login_id]' 
							ORDER BY timestmp ASC");

		if (mysql_num_rows($qtmp) > 0) {
			$no_transaksi = no_kwitansi_auto(); //no transaksi automatis
			$jmlbayar = $_POST['jmlbayar2'];
			$total_bayar = 0;

			$tgl = date('Y-m-d');
			while($tmp = mysql_fetch_assoc($qtmp))
			{
				$chart[] = $tmp;

				//hitung total
				$harga_disc = $tmp['harga'] - (($tmp['harga'] * $tmp['disc']) / 100);
				$sub_total = $harga_disc * $tmp['qty'];

				$total_bayar =  $total_bayar + $sub_total;
			}

			if ($_POST['potongan2'] > 0) {
				$total_bayar = $total_bayar - $_POST['potongan2'];
			}
			else
			{
				$total_bayar = $total_bayar;
			}
			
			//print_r($chart);
			$qpel = mysql_query("SELECT * FROM tb_pelanggan 
								WHERE kode_pelanggan = '".anti_inject($_POST['nama'])."'");
			if(mysql_num_rows($qpel) > 0)
			{
				$p = mysql_fetch_assoc($qpel);
				$kode_pel = $p['kode_pelanggan'];
				$nama_pelanggan = anti_inject($p['nama_pelanggan']);
			}
			else
			{
				$kode_pel = "$no_transaksi";
				$nama_pelanggan = anti_inject($_POST['nama']);
			}
			//echo $nama_pelanggan;

			//apakah pembayaran sudah cukup
			if (($total_bayar <= $jmlbayar) OR ($_POST['status'] == "HUTANG")) {
				//start transaction
				start_transaction();

				//pembuatan header
				$qsimpanheader = mysql_query("INSERT INTO tb_penjualan(no_transaksi,
																		kode_pelanggan, 
																		nama_pelanggan, 
																		tgl_transaksi, 
																		petugas, 
																		status,
																		bayar, 
																		potongan, 
																		timestmp)
																VALUES('$no_transaksi', 
																		'$kode_pel', 
																		'$nama_pelanggan',
																		'$tgl',  
																		'$_SESSION[login_id]',
																		'$_POST[status]', 
																		$jmlbayar, 
																		$_POST[potongan2], 
																		NOW())");
				if (!$qsimpanheader) {
					rollback();
					flash('example_message', '<p>Transaksi Gagal.</p>', 'w3-red');
					echo"<script>
						window.history.back();
					</script>";	
				}
				else
				{
					foreach ($chart as $row) {
						$qsimpandetail = mysql_query("INSERT INTO tb_detail_penjualan(no_transaksi,
																						kode_barang,
																						qty,
																						harga, 
																						disc, 
																						petugas, 
																						timestmp)
																				VALUES('$no_transaksi', 
																						'$row[kode_barang]', 
																						$row[qty], 
																						'$row[harga]', 
																						$row[disc], 
																						$row[petugas], 
																						'$row[timestmp]')");
						if (!$qsimpandetail) {
							rollback();
							flash('example_message', '<p>Transaksi gagal.</p>', 'w3-red' );
							echo"<script>
								window.history.back();
							</script>";	
						}
					}
					commit();
					$data = mysql_query("SELECT nama_barang from tb_detail_penjualan tb join tb_barang b on b.kode_barang=tb.kode_barang where tb.no_transaksi='$no_transaksi'");
					// $commit = mysql_fetch_assoc($data);
				   function own_function($element1, $element2) 
					{ 
					    return $element1 .",". $element2; 
					} 

					$new_arrays = " ";
					$training = " ";
					while($row = mysql_fetch_assoc($data)) {
						  
						$array = $row; 
						$new_arrayss = array_reduce($array, "own_function");
						$new_arrays .= $new_arrayss;
					}
					// print_r($new_arrays);
					$training = $new_arrays;
			        $simpantransaksi = mysql_query("INSERT INTO transaksi(transaction_date, produk) VALUES(curdate(),'$training')");

			        commit();

					header("location:../../med.php?mod=penjualan&act=printout&id=".$no_transaksi);
				}
				//commit();
			}
			else {
				flash('example_message', '<p>Pembayaran tidak cukup!</p>', 'w3-yellow');
				echo"<script>
					window.history.back();
				</script>";	
			}

				
		}
		else
		{
			flash('example_message', '<p>Tidak ada barang yang di jual!</p>', 'w3-red');
			echo"<script>
				window.history.back();
			</script>";	
		}
	}

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