<?php
error_reporting(0);
	if(!isset($_SESSION['login_user'])){
		header('location: ../../login.php'); // Mengarahkan ke Home Page
	}

	if(isset($_SESSION['kategori']) AND $_SESSION['kategori'] <> 'TRUE')
	{
		echo"<div class='w3-container w3-red'><p>Dilarang mengakses file ini.</p></div>";
		die();
	}

	//link buat paging
	$linkaksi = 'med.php?mod=laporan';

	if(isset($_GET['act']))
	{
		$act = $_GET['act'];
		$linkaksi .= '&act='.$act;
	}
	else
	{
		$act = '';
	}


	switch ($act) {
		case 'lappemblian':
			echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
				<h4 style='margin-bottom:0;padding-bottom:0;'>Laporan Pembelian Barang</h4>
				<p style='margin-top:0;padding-top:0;'><i>Laporan Pembelian Barang</i></p>
			</div>";

			echo"<table style='margin-top:12px;'>
				<tr>
					<td>
						<form class='w3-tiny' action='mod/laporan/cetak-lap-pembelian.php' method='POST'>	
							<input type='hidden' name='mod' value='laporan'>
							<input type='hidden' name='act' value='lappemblian'>
							<input type='hidden' name='field' value='tgl_beli'>

							<div class='w3-row'>
								<div class='w3-col s1'>
									<label class='w3-label'>Tanggal</label>
								</div>
								<div class='w3-col s2'>
									<input type='date' name='tglawal' class='w3-input'>

								</div>
								<div class='w3-col s4'>
									<input type='date' name='tglakhir' class='w3-input'>
								</div>
								<div class='w3-col s1'>
									<button type='submit' class='w3-btn w3-tiny'><i class='fa fa-paper-plane'></i> CETAK</button>
								</div>
							</div>
						</form>
					</td>
					<td align='right'><a href='med.php?mod=laporan&act=lappemblian' class='w3-btn w3-dark-grey w3-small'><i class='fa fa-refresh'></i> Refresh</a>
					</td>
				</tr>
				
			</table>";

			echo"<div style='margin-top:12px;margin-bottom:12px;'>
			<table class='w3-table w3-striped w3-bordered w3-tiny w3-hoverable tbl'>
				<thead>
					
					<tr class='w3-yellow'>
						<th >NO</th>
						<th> TANGGAL</th>
						<th >NOMOR FAKTUR</th>
						<th>SUPPLIER</th>
						<th>PETUGAS</th>
						<th>JUMLAH PEMBELIAN</th>

						
					</tr>
				</thead>
				<tbody>";

				$p      = new Paging;
				$batas  = 10;
			    if(isset($_GET['show']) && is_numeric($_GET['show']))
				{
					$batas = (int)$_GET['show'];
					$linkaksi .="&show=$_GET[show]";
				}

				$posisi = $p->cariPosisi($batas);
$tglawal=$_GET['tglawal'];
					$tglakhir=$_GET['tglakhir'];
				$query = "SELECT * ,(
					SELECT sum(harga*qty) from tb_detail_penjualan  where no_faktur=p.no_faktur) as jumlah_pembelian
from tb_pembelian p JOIN tb_supplier s on p.kode_supplier=s.kode_supplier JOIN user on p.petugas=user.id_user ";
// echo $query;
$q 	= "SELECT * ,(
					SELECT sum(harga*qty) from tb_detail_penjualan  where no_faktur=p.no_faktur) as jumlah_pembelian
from tb_pembelian p JOIN tb_supplier s on p.kode_supplier=s.kode_supplier JOIN user on p.petugas=user.id_user WHERE p.tgl_beli between '$tglawal' and '$tglakhir'" ;

				

				if(!empty($_GET['tglawal']))
				{
					$tglawal=$_GET['tglawal'];
					$tglakhir=$_GET['tglakhir'];

					$hideinp = "<input type='hidden' name='field' value='$_GET[field]'>
								<input type='hidden' name='cari' value='$_GET[cari]'>";

					$linkaksi .= "&field=$_GET[field]&cari=$_GET[cari]";

					$query .= " WHERE p.tgl_beli between '$tglawal' and '$tglakhir'";
					$q .= " WHERE p.tgl_beli between '$tglawal' and '$tglakhir'";

					
					
					echo $q;
				}

				$query .= " LIMIT $posisi, $batas";
				$q 	.= " ";

				

				$sql_kul = mysql_query($query);
				$fd_kul = mysql_num_rows($sql_kul);
				$jumlah_p=0;
				$jumlah_pembelian=0;
				if($fd_kul > 0)
				{
					$no = $posisi + 1;
					while ($m = mysql_fetch_assoc($sql_kul)) {
						
						echo"<tr>
							<td>$no</td>

							<td>$m[tgl_beli]</td>
							<td>$m[no_faktur]</td>

							<td>$m[nama_toko]</td>
							<td>$m[usernm]</td>
							<td>Rp. ".number_format($m[jumlah_pembelian])."</td>
							
						</tr>";
						$no++;
						// $jumlah_p=0;
						$jumlah_pembelian=$m[jumlah_pembelian];
						$jumlah_p=$jumlah_p+$jumlah_pembelian;
					}
					echo "<tr>
					<td colspan='5'>Total</td>
					<td>Rp. ".number_format($jumlah_p)."</td>
						</tr>";

					$jmldata = mysql_num_rows(mysql_query($q));

					$jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
		    		$linkHalaman = $p->navHalaman($_GET['halaman'], $jmlhalaman, $linkaksi);
				}
				else
				{
					echo"<tr>
						<td colspan='10'><div class='w3-center'><i>Data Barang Not Found.</i></div></td>
					</tr>
					<tr>
							<td></td>
						</tr>";

				}
			}

?>