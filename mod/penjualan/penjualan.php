<head><script>
function tampilkan(){
  var status=document.getElementById("form1").status.value;
  if (status=="member")
    {
        document.getElementById("diskon").value = '5';
        
    }
  else if (status=="nonmember")
    {
       document.getElementById("diskon").value = '0';
    }
}
</script></head>
<?php
	if(!isset($_SESSION['login_user'])){
		header('location: ../../login.php'); // Mengarahkan ke Home Page
	}

	//link buat paging
	$linkaksi = 'med.php?mod=penjualan';

	if(isset($_GET['act']))
	{
		$act = $_GET['act'];
		$linkaksi .= '&act='.$act;
	}
	else
	{
		$act = '';
	}

	$aksi = 'mod/penjualan/act_penjualan.php';

	switch ($act) {
		default:
			echo"<div class='w3-container w3-wi w3-blue w3-leftbar w3-border-blue'>
				<h4 style='margin-bottom:0;padding-bottom:0;'>Transaksi Penjualan</h4>
				<p style='margin-top:0;padding-top:0;'><i>Menu Transaksi Penjualan Barang</i></p>
			</div>";

			flash('example_message');

			echo"<div class='w3-row-padding'>
				<div class='w3-col s15'>Data Barang
				<div style='border-bottom:1px dashed #ccc;'></div>";

					echo"<table style='margin-top:12px;'>
						<tr>
							<td>
								<form class='w3-tiny' action='' method='GET' id='form1'>	
									<input type='hidden' name='mod' value='penjualan'>
									<br><br>
									<label>Pilih Kategori: </label>
									  <select id='status' name='status' onchange='tampilkan()'>
									    <option value='0' disabled='disabled' selected/>Pilih</option>
									    <option value='member'>Member</option>
									    <option value='nonmember'>Non Member</option>
									  </select>
									  <br/><br/>
 

  
									<div class='w3-row'>
										<div class='w3-col s1'>
											<label class='w3-label'>Search</label>
										</div>
										<div class='w3-col s2'>
											<select name='field' class='w3-select w3-padding'>
												<option value=''>- Pilih -</option>
												<option value='kode_barang'>KODE BARANG</option>
												<option value='nama_barang'>NAMA BARANG</option>
												<option value='harga_jual'>HARGA</option>
											</select>
										</div>
										<div class='w3-col s6'>
											<input type='text' name='cari' class='w3-input' placeholder='cari ...'>
										</div>
										<div class='w3-col s1'>
											<button type='submit' class='w3-btn w3-tiny'><i class='fa fa-paper-plane'></i>CARI</button>
										</div>
										<div class='w3-col s1'>
											<a href='med.php?mod=penjualan' class='w3-btn w3-dark-grey w3-tiny'><i class='fa fa-refresh'></i> REFRESH</a>
										</div>
									</div>
								</form>
							</td>
						</tr>
						
					</table>";

					echo"<div style='margin-top:12px;margin-bottom:12px;'>
					<table class='w3-table w3-striped w3-bordered w3-tiny w3-hoverable tbl'>
						<thead>
							<tr class='w3-yellow'>
								<th>NO</th>
								<th>KODE</th>
								<th>NAMA BARANG</th>
								<th>HARGA</th>
								<th width='130px'>DISC.+ADD</th>
							</tr>
						</thead>
						<tbody>";

						$p      = new Paging;
						$batas  = 5;
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
								echo"<tr>
									<td>$no</td>
									<td>$m[kode_barang]</td>
									<td>$m[nama_barang]</td>
									<td>Rp. ".number_format($m['harga_jual'])."</td>
									<td><form action='$aksi'>
									
										<input type='hidden' name='mod' value='penjualan'>
										<input type='hidden' name='act' value='add'>
										<input type='hidden' name='id' value='$m[kode_barang]'>

										<div class='w3-row'>

											<div class='w3-col s4'>
											<input type='text' name='qty' class='w3-input w3-tiny w3-border' maxlength='3' placeholder='QTY'><input type='text' name='disc' class='w3-input w3-tiny w3-border' maxlength='3' placeholder='0%' id='diskon'></div>
											<div class='w3-col s8'><button type='submit' class='w3-btn w3-red w3-tiny'><i class='fa fa-cart-plus'></i> ADD</button>
											</div>
										</div>
									</form>
									</td>
								
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
						

						echo"</tbody>

					</table></div>";

					echo"<div class='w3-row'>
						<div class='w3-col s2'>
							<form class='w3-tiny' action='' method='GET'>
								<input type='hidden' name='mod' value='penjualan'>";
								if(!empty($hideinp))
								{
									echo $hideinp;
								}
								echo"<select class='w3-select w3-border' name='show' onchange='submit()'>
									<option value=''>- Show -</option>
									<option value='5'>5</option>";
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
						<div class='w3-col s10'>
							<ul class='w3-pagination w3-right w3-tiny'>
								$linkHalaman
							</ul>
						</div>
					</div>


				</div>";


				echo"<div class='w3-col s15 w3-card'>Keranjang Penjualan
				<div style='border-bottom:1px dashed #ccc;'></div><br>";
					echo"<table class='w3-table w3-tiny w3-hoverable tbl'>
						<thead>
						<tr class='w3-blue'>
							<th>#</th>
							<th>BARANG</th>
							<th>HARGA</th>
							<th>DISC.</th>
							<th colspan='2'>SUB TOTAL</th>
						</tr>
						</thead>

						<tbody>";

						$sql_tmp = mysql_query("SELECT a.kode_barang, a.qty, a.harga, b.nama_barang, a.disc 
												FROM tb_detail_penjualan_tmp a, tb_barang b
												WHERE a.kode_barang = b.kode_barang 
												AND a.petugas = '$_SESSION[login_id]' 
												ORDER BY a.timestmp ASC") or die(mysql_error());
						$no = 1;

						$sub_total = 0;
						$total_harga = 0;

						if(mysql_num_rows($sql_tmp) > 0)
						{
							while ($b = mysql_fetch_assoc($sql_tmp)) {
								$harga_disc = $b['harga'] - (($b['harga'] * $b['disc']) / 100);
								$sub_total = $harga_disc * $b['qty'];
								$total_harga = $total_harga + $sub_total;

								echo"<tr style='border-bottom:1px dashed #ccc;'>
									<td>$no</td>
									<td>$b[nama_barang]</td>
									<td>Rp. ".number_format($b['harga'])." X $b[qty]</td>
									<td><center>".number_format($b['disc'])."%</center></td>
									<td>Rp. ".number_format($sub_total)."</td>
									<td><a href='$aksi?mod=penjualan&act=batal&id=$b[kode_barang]' onclick=\"return confirm('Yakin ingin membatalkan?');\"><i class='fa fa-close w3-tiny w3-text-grey'></i></a></td>
								</tr>";

								$no++;
							}
						}
							
						else
						{
							echo"<tr>
								<td colspan='5'><center><i>Keranjang Kosong</i></center></td>
							</tr>";
						}

						echo"</tbody>

						<tfoot>
						<tr>
							<td colspan='2'><b>TOTAL</b></td>
							<td colspan='4'><b class='w3-text-red w3-small w3-right'>Rp. ".number_format($total_harga)."</b></td>
						</tr>
						<tr>
							<td colspan='3'><b>POTONGAN HARGA (Rp.)<b></td>
							<td colspan='3'><input type='text' name='potongan' id='potongan' class='w3-input w3-border w3-tiny w3-right' value='0'></td>
						</tr>
						<tr style='border-top:1px dashed #ccc;'>
							<td colspan='2'><b class='w3-text-blue'>TOTAL BAYAR</b><td>
							<td colspan='4'><b class='w3-text-red w3-small w3-right'>Rp. <span id='tot'>0</span></b></td>
						</tr>
						</tfoot>


					</table><hr>

					<div class='w3-card-2 w3-light-blue'>
						<form action='$aksi?mod=penjualan&act=simpan' method='POST' class='w3-container'>
							<input type='hidden' name='potongan2' id='potongan2' value='0'>
							<input type='hidden' name='total' id='total' value='"?><?php echo isset($total_harga) ? $total_harga : 0; ?><?php echo"'>

							<input type='hidden' name='jmlbayar2' id='bayar2'>
							<label class='w3-label w3-text-black'>Nama Pelanggan :</label>
							<input type='text' name='nama' id='nama' class='w3-input w3-tiny w3-border-0' required value='konsumen'>

							<label class='w3-label w3-text-black'>Bayar (Rp):</label>
							<input type='text' name='jmlbayar' id='bayar' class='w3-input w3-tiny w3-border-0' required>

							
							


							<p><button class='w3-btn w3-green' onclick=\"return confirm('Klik OK untuk melanjutkan');\"><i class='fa fa-save'></i> Simpan Transaksi</button></p>
						</form>
					</div><br>";

				echo"</div>
			</div>";

		break;


		case "printout" :
				
			if(isset($_GET['id']))
			{
				echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
					<h4 style='margin-bottom:0;padding-bottom:0;'>Printout Penjualan</h4>
					<p style='margin-top:0;padding-top:0;'><i>Data Penjualan Barang</i></p>
				</div><br>

				<div class='w3-container w3-padding-4 w3-tiny w3-pale-red'>
					<p><i>Jika terjadi kesalahan harap lapor Administrator.</i></p>
				</div>";

				$sqltrans = mysql_query("SELECT * FROM tb_penjualan WHERE no_transaksi = '$_GET[id]'") or die(mysql_error());
				$tra = mysql_fetch_assoc($sqltrans);

				echo"<table class='w3-table w3-tiny'>
					<tr style='border-bottom:1px dashed #ccc;'>
						<td width='150px'>No. Transaksi</td>
						<td width='10px'>:</td>
						<td><b>$tra[no_transaksi]</b></td>
					</tr>

					<tr style='border-bottom:1px dashed #ccc;'>
						<td>Nama / Kode</td>
						<td>:</td>
						<td><b>$tra[nama_pelanggan] / "?><?php echo !empty($tra['kode_pelanggan']) ? $tra['kode_pelanggan'] : "-"; ?><?php echo"</b></td>
					</tr>

					<tr style='border-bottom:1px dashed #ccc;'>
						<td>Tanggal Transaksi</td>
						<td>:</td>
						<td><b>$tra[timestmp]</b></td>
					</tr>

					<tr style='border-bottom:1px dashed #ccc;'>
						<td>Status</td>
						<td>:</td>
						<td><b>$tra[status]</b></td>
					</tr>
				</table>
				<div style='height:10px;'></div>";

				echo"<h4>Detail Barang</h4>
				<table class='w3-table w3-tiny w3-hoverable w3-bordered tbl'>
					<thead>
					<tr class='w3-blue'>
						<th>#</th>
						<th>KODE</th>
						<th>BARANG</th>
						<th>HARGA</th>
						<th>DISC.</th>
						<th colspan='2'>SUB TOTAL</th>
					</tr>
					</thead>

					<tbody>";

				$sql = mysql_query("SELECT a.*, b.nama_barang, b.satuan 
									FROM tb_detail_penjualan a LEFT JOIN tb_barang b 
									ON a.kode_barang = b.kode_barang
									WHERE a.no_transaksi = '$_GET[id]'") or die(mysql_error());
				$sub_total = 0;
				$total = 0;
				$no = 1;
				while($p = mysql_fetch_assoc($sql))
				{
					$harga_disc = $p['harga'] - (($p['harga'] * $p['disc']) / 100);
					$sub_total = $harga_disc * $p['qty'];

					$total = $total + $sub_total;
					echo"<tr>
						<td>$no</td>
						<td>$p[kode_barang]</td>
						<td>$p[nama_barang]</td>
						<td>Rp. ".number_format($p['harga'],0)." X $p[qty] $p[satuan]</td>
						<td>".number_format($p['disc'],0)."%</td>
						<td>Rp. ".number_format($sub_total)."</td>
					</tr>";

					$no++;
				}
				$total_bayar = $total - $tra['potongan'];
				$sisa = $tra['bayar'] - $total_bayar;

				echo"</tbody>
					<tfoot>
					<tr class='w3-light-grey'>
						<td colspan='5'>Total Harga</b></td>
						<td>Rp. ".number_format($total)."</td>
					</tr>
					<tr class='w3-light-grey'>
						<td colspan='5'>Potongan Harga</td>
						<td>Rp. ".number_format($tra['potongan'])."</td>
					</tr>
					<tr class='w3-light-grey'>
						<td colspan='5'><b>Total Bayar</b></td>
						<td><b>Rp. ".number_format($total_bayar)."</b></td>
					</tr>
					<tr class='w3-light-grey'>
						<td colspan='5'><b>Pembayaran</b></td>
						<td><b>Rp. ".number_format($tra['bayar'])."</b></td>
					</tr>
					<tr class='w3-light-grey'>
						<td colspan='5'><b>Kembali</b></td>
						<td><b>Rp. ".number_format($sisa)."</b></td>
					</tr>
					</tfoot>
				</table>

				<p>
					<button class='w3-btn w3-tiny' onclick=\"window.history.back()\"><i class='fa fa-mail-reply-all'></i> Back</button>
					<a href='med.php?mod=penjualan' class='w3-btn w3-red w3-tiny'><i class='fa fa-cart-plus'></i> Transaksi Baru</a>
					<a href='popup/popup.php?mod=cetakkwitansi&id=$_GET[id]' class='w3-btn w3-dark-grey w3-tiny' target='_blank'><i class='fa fa-print'></i> Cetak Kwitansi</a>
				</p>";

			}
		break;


		case "list":
			echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
				<h4 style='margin-bottom:0;padding-bottom:0;'>Data Transaksi Penjualan</h4>
				<p style='margin-top:0;padding-top:0;'><i>Data Semua Transaksi Penjualan</i></p>
			</div>";

			flash('example_message');

			echo"<table style='margin-top:12px;'>
				<tr>
					<td>
						<form class='w3-tiny' action='' method='GET'>	
							<input type='hidden' name='mod' value='penjualan'>
							<input type='hidden' name='act' value='list'>
							<div class='w3-row'>
								<div class='w3-col s1'>
									<label class='w3-label'>Search</label>
								</div>
								<div class='w3-col s2'>
									<select name='field' class='w3-select w3-padding'>
										<option value=''>- Pilih -</option>
										<option value='no_transaksi'>NO. TRANSAKSI</option>
										<option value='nama_pelanggan'>NAMA PELANGGAN</option>
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
					<td align='right'><a href='med.php?mod=penjualan&act=list' class='w3-btn w3-dark-grey w3-small'><i class='fa fa-refresh'></i> Refresh</a>
					</td>
				</tr>
				
			</table>";

			echo"<div style='margin-top:12px;margin-bottom:12px;'>
			<table class='w3-table w3-striped w3-bordered w3-tiny w3-hoverable tbl'>
				<thead>
					<tr class='w3-yellow'>
						<th>NO</th>
						<th>NO. TRANSAKSI</th>
						<th>KODE PEL.</th>
						<th>NAMA PELANGGAN</th>
						<th>TGL. TRANSAKSI</th>
						<th>PETUGAS</th>
						<th>TOTAL</th>
						<th>POTONGAN</th>
						<th>STATUS</th>
						<th>#</th>
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

				$query = "SELECT * FROM tb_penjualan ";

				$q 	= "SELECT * FROM tb_penjualan";

				if(!empty($_GET['field']))
				{
					$hideinp = "<input type='hidden' name='field' value='$_GET[field]'>
								<input type='hidden' name='cari' value='$_GET[cari]'>";

					$linkaksi .= "&field=$_GET[field]&cari=$_GET[cari]";

					$query .= " WHERE $_GET[field] LIKE '%$_GET[cari]%'";
					$q .= " WHERE $_GET[field] LIKE '%$_GET[cari]%'";
				}

				$query .= " ORDER BY timestmp DESC LIMIT $posisi, $batas";
				$q 	.= " ORDER BY timestmp DESC";
				

				$sql_kul = mysql_query($query);
				$fd_kul = mysql_num_rows($sql_kul);

				if($fd_kul > 0)
				{
					$no = $posisi + 1;
					while ($m = mysql_fetch_assoc($sql_kul)) {
						echo"<tr>
							<td>$no</td>
							<td><a class='w3-text-blue w3-hover-text-red' href='med.php?mod=penjualan&act=printout&id=$m[no_transaksi]'>$m[no_transaksi]</a></td>
							<td>$m[kode_pelanggan]</td>
							<td>$m[nama_pelanggan]</td>
							<td>$m[timestmp]</td>
							<td>".nama_petugas($m['petugas'])."</td>
							<td>".total_penjualan($m['no_transaksi'])."</td>
							<td>Rp. ".number_format($m['potongan'])."</td>
							<td>$m[status]</td>
							<td><a href='$aksi?mod=penjualan&act=hapus&id=$m[no_transaksi]' onclick=\"return confirm('Yakin hapus data');\"><i class='fa fa-trash w3-large w3-text-red'></i></a>
							</td>
						
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
						<td colspan='8'><div class='w3-center'><i>Data Transaksi Not Found.</i></div></td>
					</tr>";
				}
				

				echo"</tbody>

			</table></div>";

			echo"<div class='w3-row'>
				<div class='w3-col s1'>
					<form class='w3-tiny' action='' method='GET'>
						<input type='hidden' name='mod' value='penjualan'>
						<input type='hidden' name='act' value='list'>";
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

<script type="text/javascript">
	$(function(){
		$("#bayar").number(true);
		$("#potongan").number(true);

		$('#bayar').keyup(function(){
			var bayar = $('#bayar').val();
			$('#bayar2').val(bayar);
		});

		$('#potongan').keyup(function(){
			var potongan = $('#potongan').val();
			$('#potongan2').val(potongan);

			var total = $("#total").val();
			var pot = $("#potongan2").val();
			
			var tot_bayar = total - pot;
			if (tot_bayar > 0) {
				$("#tot").text(tot_bayar).number(true);
			}
			else
			{
				$("#tot").text(0);
			}
			console.log(tot_bayar);
		});
		
		<?php
			$sqlTags = mysql_query("SELECT * FROM tb_pelanggan 
								ORDER BY kode_pelanggan ASC") or die(mysql_error());

			$tags = array();
			while($t = mysql_fetch_assoc($sqlTags))
			{
				$tags[] = '{label : "'.$t['nama_pelanggan'].'", value : "'.$t['kode_pelanggan'].'"}';
			}
		?>
		var availableTags = [<?php echo implode(", \n\t\t\t", $tags); ?>];
	    $( "#nama" ).autocomplete({
	    	source: availableTags,
	    	select:function(event, ui) {
	    		$("#bayar").focus();
	    		console.log(ui.item.label);
	    	}
	    });
	});
</script>