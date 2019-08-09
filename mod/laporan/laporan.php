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
	$linkaksi = 'med.php?mod=xlaporan';

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
		case 'stokbarang':
			echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
				<h4 style='margin-bottom:0;padding-bottom:0;'>Laporan Stok Barang</h4>
				<p style='margin-top:0;padding-top:0;'><i>Laporan sisa stok seluruh barang</i></p>
			</div>";

			echo"<table style='margin-top:12px;'>
			
				<tr>

					<td>
						<form class='w3-tiny' action='' method='GET'>	
							<input type='hidden' name='mod' value='laporan'>
							<input type='hidden' name='act' value='stokbarang'>
							<div class='w3-row'>
								<div class='w3-col s1'>
									<label class='w3-label'>Search</label>
								</div>
								<div class='w3-col s2'>
									<select name='field' class='w3-select w3-padding'>
										<option value=''>- Pilih -</option>
										<option value='nama_barang'>NAMA BARANG</option>
										<option value='tgl_input'>TGL INPUT</option>
										<option value='harga_beli'>HARGA BELI</option>
										<option value='harga_jual'>HARGA JUAL</option>
										<option value='jml_stok'>JML STOK</option>
									</select>
								</div>
								<div class='w3-col s4'>
									<input type='text' name='cari' class='w3-input' placeholder='cari ...'>
								</div>
								<div class='w3-col s1'>
									<button type='submit' class='w3-btn w3-tiny'><i class='fa fa-paper-plane'></i> GO</button>
								</div>
							</div>
						</form>
					</td>
					<td align='right'><a href='med.php?mod=laporan&act=stokbarang' class='w3-btn w3-dark-grey w3-small'><i class='fa fa-refresh'></i> Refresh</a>
					</td>
				</tr>
				
			</table>";

			echo"<div style='margin-top:12px;margin-bottom:12px;'>
			<table class='w3-table w3-striped w3-bordered w3-tiny w3-hoverable tbl'>
				<thead>
				<tr>
			<td>
			<a href='mod/laporan/cetak-stok.php' class='w3-btn w3-dark-grey w3-small'><i class='fa fa-print'></i> Cetak</a>
			
			</td></tr>
					<tr class='w3-yellow'>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th colspan='4'><center>STOK</center></th>
						<th colspan='2'><center>RETUR</center></th>
						<th></th>
					</tr>
					<tr class='w3-yellow'>
						<th rowspan='2'>NO</th>
						<th rowspan='2'>KODE BARANG</th>
						<th rowspan='2'>NAMA BARANG</th>
						<th rowspan='2'>SATUAN</th>
						<th rowspan='2'>KATEGORI</th>
						<th>AWAL</th>
						<th>MASUK</th>
						<th>KELUAR</th>
						<th>TOTAL</th>
						<th>JUAL</th>
						<th>BELI</th>
						<th>SISA</th>
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

				$query = "select * from barang_laris b JOIN tb_barang tb on b.kode_barang=tb.kode_barang JOIN tb_kategori_barang kt on tb.kategori_id=kt.kategori_id ORDER by b.jumlah desc ";

				$q 	= "select * from barang_laris b JOIN tb_barang tb on b.kode_barang=tb.kode_barang JOIN tb_kategori_barang kt on tb.kategori_id=kt.kategori_id ORDER by b.jumlah desc";

				if(!empty($_GET['field']))
				{
					$hideinp = "<input type='hidden' name='field' value='$_GET[field]'>
								<input type='hidden' name='cari' value='$_GET[cari]'>";

					$linkaksi .= "&field=$_GET[field]&cari=$_GET[cari]";

					$query .= " WHERE $_GET[field] LIKE '%$_GET[cari]%'";
					$q .= " WHERE $_GET[field] LIKE '%$_GET[cari]%'";
				}

				$query .= " LIMIT $posisi, $batas";
				$q 	.= " ";
				

				$sql_kul = mysql_query($query);
				$fd_kul = mysql_num_rows($sql_kul);
				
				if($fd_kul > 0)
				{
					$no = $posisi + 1;
					while ($m = mysql_fetch_assoc($sql_kul)) {
						$stok_masuk = stok_masuk($m['kode_barang']);
						$stok_keluar = stok_keluar($m['kode_barang']);
						$total_stok = ($m['jml_stok'] + $stok_masuk) - $stok_keluar;

						$retur_jual = stok_retur_jual($m['kode_barang']);
						$retur_beli = stok_retur_beli($m['kode_barang']);

						$sisa = ($total_stok + $retur_jual) - $retur_beli;
						echo"<tr>
							<td>$no</td>
							<td>$m[kode_barang]</td>
							<td>$m[nama_barang]</td>
							<td>$m[satuan]</td>
							<td>".nama_kategori($m['kategori_id'])."</td>
							<td><center>$m[jml_stok]</center></td>
							<td><center>".$stok_masuk."</center></td>
							<td><center>".$stok_keluar."</center></td>
							<td><center>".$total_stok."</center></td>
							<td><center>".$retur_jual."</center></td>
							<td><center>".$retur_beli."</center></td>
							<td><center>".$sisa."</center></td>
						</tr>";
						$no++;
					}
	

					$jmldata = mysql_num_rows(mysql_query($q));

					$jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
		    		$linkHalaman = $p->navHalaman($_GET['halaman'], $jmlhalaman, $linkaksi);
				}
				else
				{
					echo"<tr>
						<td colspan='10'><div class='w3-center'><i>Data Barang Not Found.</i></div></td>
					</tr>";
				}
			}

				switch ($act) {
		case 'laplaris':
			echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
				<h4 style='margin-bottom:0;padding-bottom:0;'>Laporan Barang Terlaris</h4>
				<p style='margin-top:0;padding-top:0;'><i>Laporan Barang Terlaris</i></p>
			</div>";

			echo"<table style='margin-top:12px;'>
				<tr>
					<td>
						<form class='w3-tiny' action='' method='GET'>	
							<input type='hidden' name='mod' value='laporan'>
							<input type='hidden' name='act' value='stokbarang'>
							<div class='w3-row'>
								<div class='w3-col s1'>
									<label class='w3-label'>Search</label>
								</div>
								<div class='w3-col s2'>
									<select name='field' class='w3-select w3-padding'>
										<option value=''>- Pilih -</option>
										<option value='nama_barang'>NAMA BARANG</option>
										<option value='tgl_input'>TGL INPUT</option>
										<option value='harga_beli'>HARGA BELI</option>
										<option value='harga_jual'>HARGA JUAL</option>
										<option value='jml_stok'>JML STOK</option>
									</select>
								</div>
								<div class='w3-col s4'>
									<input type='text' name='cari' class='w3-input' placeholder='cari ...'>
								</div>
								<div class='w3-col s1'>
									<button type='submit' class='w3-btn w3-tiny'><i class='fa fa-paper-plane'></i> GO</button>
								</div>
							</div>
						</form>
					</td>
					<td align='right'><a href='med.php?mod=laporan&act=stokbarang' class='w3-btn w3-dark-grey w3-small'><i class='fa fa-refresh'></i> Refresh</a>
					</td>
				</tr>
				
			</table>";

			echo"<div style='margin-top:12px;margin-bottom:12px;'>
			<table class='w3-table w3-striped w3-bordered w3-tiny w3-hoverable tbl'>
				<thead>
					<tr class='w3-yellow'>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th colspan='4'><center>STOK</center></th>
						<th colspan='2'><center>RETUR</center></th>
						<th></th>
					</tr>
					<tr class='w3-yellow'>
						<th rowspan='2'>NO</th>
						<th rowspan='2'>KODE BARANG</th>
						<th rowspan='2'>NAMA BARANG</th>
						<th rowspan='2'>SATUAN</th>
						<th rowspan='2'>KATEGORI</th>
						<th>AWAL</th>
						<th>MASUK</th>
						<th>KELUAR</th>
						<th>TOTAL</th>
						<th>JUAL</th>
						<th>BELI</th>
						<th>SISA</th>
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

				$query = "SELECT * FROM tb_barang ";

				$q 	= "SELECT * FROM tb_barang";

				if(!empty($_GET['field']))
				{
					$hideinp = "<input type='hidden' name='field' value='$_GET[field]'>
								<input type='hidden' name='cari' value='$_GET[cari]'>";

					$linkaksi .= "&field=$_GET[field]&cari=$_GET[cari]";

					$query .= " WHERE $_GET[field] LIKE '%$_GET[cari]%'";
					$q .= " WHERE $_GET[field] LIKE '%$_GET[cari]%'";
				}

				$query .= " LIMIT $posisi, $batas";
				$q 	.= " ";
				

				$sql_kul = mysql_query($query);
				$fd_kul = mysql_num_rows($sql_kul);
				
				if($fd_kul > 0)
				{
					$no = $posisi + 1;
					while ($m = mysql_fetch_assoc($sql_kul)) {
						$stok_masuk = stok_masuk($m['kode_barang']);
						$stok_keluar = stok_keluar($m['kode_barang']);
						$total_stok = ($m['jml_stok'] + $stok_masuk) - $stok_keluar;

						$retur_jual = stok_retur_jual($m['kode_barang']);
						$retur_beli = stok_retur_beli($m['kode_barang']);

						$sisa = ($total_stok + $retur_jual) - $retur_beli;
						echo"<tr>
							<td>$no</td>
							<td>$m[kode_barang]</td>
							<td>$m[nama_barang]</td>
							<td>$m[satuan]</td>
							<td>".nama_kategori($m['kategori_id'])."</td>
							<td><center>$m[jml_stok]</center></td>
							<td><center>".$stok_masuk."</center></td>
							<td><center>".$stok_keluar."</center></td>
							<td><center>".$total_stok."</center></td>
							<td><center>".$retur_jual."</center></td>
							<td><center>".$retur_beli."</center></td>
							<td><center>".$sisa."</center></td>
						</tr>";
						$no++;
					}
	

					$jmldata = mysql_num_rows(mysql_query($q));

					$jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
		    		$linkHalaman = $p->navHalaman($_GET['halaman'], $jmlhalaman, $linkaksi);
				}
				else
				{
					echo"<tr>
						<td colspan='10'><div class='w3-center'><i>Data Barang Not Found.</i></div></td>
					</tr>";
				}
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
						<form class='w3-tiny' action='med.php?mod=laporan&act=lappemblian' method='GET'>	
							<input type='hidden' name='mod' value='laporan'>
							<input type='hidden' name='act' value='stokbarang'>
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
									<button type='submit' class='w3-btn w3-tiny'><i class='fa fa-paper-plane'></i> GO</button>
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

				$query = "SELECT * ,(
SELECT sum(harga*qty) from tb_detail_penjualan  where no_faktur=p.no_faktur) as jumlah_pembelian
from tb_pembelian p JOIN tb_supplier s on p.kode_supplier=s.kode_supplier JOIN user on p.petugas=user.id_user";

				$q 	= "SELECT * ,(
SELECT sum(harga*qty) from tb_detail_penjualan  where no_faktur=p.no_faktur) as jumlah_pembelian
from tb_pembelian p JOIN tb_supplier s on p.kode_supplier=s.kode_supplier JOIN user on p.petugas=user.id_user";

				if(!empty($_GET['field']))
				{
					$tglawal=$_POST['tglawal'];
					$tglakhir=$_POST['tglakhir'];

					$hideinp = "<input type='hidden' name='field' value='$_GET[field]'>
								<input type='hidden' name='cari' value='$_GET[cari]'>";

					$linkaksi .= "&field=$_GET[field]&cari=$_GET[cari]";

					$query .= " WHERE $_GET[field] LIKE '%$_GET[cari]%'";
					$q .= " WHERE $_GET[field] between $tglawal and $tglakhir";
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
					</tr>";
				}

}
switch ($act) {
		case 'lappenjualan':
			echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
				<h4 style='margin-bottom:0;padding-bottom:0;'>Laporan Penjualan Barang</h4>
				<p style='margin-top:0;padding-top:0;'><i>Laporan Penjualan Barang</i></p>
			</div>";

			echo"<table style='margin-top:12px;'>
				<tr>
					<td>
						<form class='w3-tiny' action='mod/laporan/cetak-laporan-penjualan.php' method='POST'>	
							<input type='hidden' name='mod' value='laporan'>
							<input type='hidden' name='act' value='lappenjualan'>
							<div class='w3-row'>
								
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
					<td align='right'><a href='med.php?mod=laporan&act=stokbarang' class='w3-btn w3-dark-grey w3-small'><i class='fa fa-refresh'></i> Refresh</a>
					</td>
				</tr>
				
			</table>";

			echo"<div style='margin-top:12px;margin-bottom:12px;'>
			<table class='w3-table w3-striped w3-bordered w3-tiny w3-hoverable tbl'>
				<thead>
					<tr>
					
					<tr class='w3-yellow'>
						<th>NO</th>
						<th>TANGGAL</th>
						<th>NOMOR TRANSAKSI</th>
						<th>PETUGAS</th>
						<th>JUMLAH TRANSAKSI</th>
						<th>POTONGAN</th>
						<th>JUMLAH BAYAR</th>

						
					</tr>
				</thead>
				<tbody>";

				$p      = new Paging;
				$batas  = 10;
				$jumlah_transaksi=0;
			    if(isset($_GET['show']) && is_numeric($_GET['show']))
				{
					$batas = (int)$_GET['show'];
					$linkaksi .="&show=$_GET[show]";
				}

				$posisi = $p->cariPosisi($batas);

				$query = "SELECT * FROM tb_penjualan p JOIN user u on p.petugas=u.id_user";

				$q 	= "SELECT * FROM tb_penjualan p JOIN user u on p.petugas=u.id_user";

				if(!empty($_GET['field']))
				{
					$hideinp = "<input type='hidden' name='field' value='$_GET[field]'>
								<input type='hidden' name='cari' value='$_GET[cari]'>";

					$linkaksi .= "&field=$_GET[field]&cari=$_GET[cari]";

					$query .= " WHERE $_GET[field] LIKE '%$_GET[cari]%'";
					$q .= " WHERE $_GET[field] LIKE '%$_GET[cari]%'";
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
						$jumlah_transaksi=$m[potongan]+$m[bayar];
						
						echo"<tr>
							<td>$no</td>

							<td>$m[tgl_transaksi]</td>
							<td>$m[no_transaksi]</td>

							<td>$m[usernm]</td>
							<td>Rp. ".number_format($jumlah_transaksi)."</td>
							<td>Rp. ".number_format($m[potongan])."</td>

							<td>Rp. ".number_format($m[bayar])."</td>
							
						</tr>";
						$no++;
						// $jumlah_p=0;
						$jumlah_pembelian=$m[bayar];
						$jumlah_p=$jumlah_p+$jumlah_pembelian;
					}
					echo "<tr>
					<td colspan='6'>Total</td>
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
					</tr>";
				}

			
				

				echo"</tbody>

			</table></div>";

			echo"<div class='w3-row'>
				<div class='w3-col s1'>
					<form class='w3-tiny' action='' method='GET'>
						<input type='hidden' name='mod' value='laporan'>
						<input type='hidden' name='act' value='stokbarang'>";
						if(!empty($hideinp))
						{
							echo $hideinp;
						}
						echo"<select class='w3-select w3-border' name='show' onchange='submit()'>
							<option value=''>- Show -</option>";
							$i=10;
							while($i <= 100)
							{
								if(isset($_GET['show']) AND (int)$_GET['show'] == $i)
								{
									echo"<option value='$i' selected>$i</option>";	
								}
								else
								{
									echo"<option value='$i'>$i</option>";
								}

								$i+=10;
							}
						echo"</select>
					</form>
				</div>
				<div class='w3-col s11'>
					<ul class='w3-pagination w3-right w3-tiny'>
						$linkHalaman
					</ul>
				</div>
			</div>";
		break;

	}
?>