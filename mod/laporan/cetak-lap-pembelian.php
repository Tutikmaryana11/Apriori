<?php
error_reporting(0);
mysql_connect("localhost", "root", "");
mysql_select_db("penjualan");
$tglawal=$_POST['tglawal'];
$tglakhir=$_POST['tglakhir'];
// print_r($_POST);
$sql = "SELECT * ,(
					SELECT sum(harga*qty) from tb_detail_penjualan  where no_faktur=p.no_faktur) as jumlah_pembelian
from tb_pembelian p JOIN tb_supplier s on p.kode_supplier=s.kode_supplier JOIN user on p.petugas=user.id_user where p.tgl_beli between '$tglawal' and '$tglakhir'";
// echo $sql;
		
$query = mysql_query($sql);

echo '<center>
	<h2>Laporan Data Pembelian <br>Toko Postulatio<br>Jl. Asem Gedhe, Condong Catur</h2>
	<h3>Periode '; echo $tglawal; echo 'Sampai '; echo $tglakhir; echo'</h3>
	<table border="1">
		<thead>
			<tr>
				<th >NO</th>
						<th> TANGGAL</th>
						<th >NOMOR FAKTUR</th>
						<th>SUPPLIER</th>
						<th>PETUGAS</th>
						<th>JUMLAH PEMBELIAN</th>
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

							<td>$m[tgl_beli]</td>
							<td>$m[no_faktur]</td>

							<td>$m[nama_toko]</td>
							<td>$m[usernm]</td>
							<td>Rp. ".number_format($m[jumlah_pembelian])."</td>
							
						</tr>";
						$no++;
						$jumlah_pembelian=$m[jumlah_pembelian];
						$jumlah_p=$jumlah_p+$jumlah_pembelian;
}
echo "<tr>
					<td colspan='5'>Total</td>
					<td>Rp. ".number_format($jumlah_p)."</td>
						</tr>";
echo '
	</tbody>
</table>';
?>
<script>
	window.print();
</script>