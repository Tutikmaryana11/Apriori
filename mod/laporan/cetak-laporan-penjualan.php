<?php
error_reporting(0);
mysql_connect("localhost", "root", "");
mysql_select_db("penjualan");
$tglawal=$_POST['tglawal'];
$tglakhir=$_POST['tglakhir'];

// print_r($_POST);

$sql = "SELECT * FROM tb_penjualan p JOIN user u on p.petugas=u.id_user where p.tgl_transaksi between '$tglawal' and '$tglakhir'";
// echo $sql;
// echo $sql;
		
$query = mysql_query($sql);

echo '<center>
	<h2>Laporan Data Penjualan <br>Toko Postulatio<br>Jl. Asem Gedhe, Condong Catur</h2>
	<h3>Periode '; echo $tglawal; echo 'Sampai '; echo $tglakhir; echo'</h3>
	<table border="1">
		<thead>
			<tr>
				<th>NO</th>
						<th>TANGGAL</th>
						<th>NOMOR TRANSAKSI</th>
						<th>PETUGAS</th>
						<th>JUMLAH TRANSAKSI</th>
						<th>POTONGAN</th>
						<th>JUMLAH BAYAR</th>
			</tr>
		</thead>
		<tbody>';
	if ($query === FALSE) {
    die(mysql_error());
}	
$no=1;
while ($m = mysql_fetch_assoc($query)) 
{
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
						$jumlah_pembelian=$m[bayar];
						$jumlah_p=$jumlah_p+$jumlah_pembelian;
}
echo "<tr>
					<td colspan='6'>Total</td>
					<td>Rp. ".number_format($jumlah_p)."</td>
						</tr>";
echo '
	</tbody>
</table>';
?>
<script>
	window.print();
</script>