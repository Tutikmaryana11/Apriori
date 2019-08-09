<?php
error_reporting(0);
mysql_connect("localhost", "root", "");
mysql_select_db("penjualan");

// print_r($_POST);

$sql = "select * from barang_laris b JOIN tb_barang tb on b.kode_barang=tb.kode_barang JOIN tb_kategori_barang kt on tb.kategori_id=kt.kategori_id ORDER by b.jumlah desc";
// echo $sql;
// echo $sql;
		
$query = mysql_query($sql);

echo "<center>
	<h2>Laporan Data Stok Barang <br>Toko Postulatio<br>Jl. Asem Gedhe, Condong Catur</h2>
	
	<table border='1'>
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
	if ($query === FALSE) {
    die(mysql_error());
}	
$no=1;
while ($m = mysql_fetch_assoc($query)) 
{
						$stok_masuk = count($m['kode_barang']);;
						$stok_keluar =count($m['kode_barang']);
						$total_stok = ($m['jml_stok'] + $stok_masuk) - $stok_keluar;

						$retur_jual = count($m['kode_barang']);
						$retur_beli = count($m['kode_barang']);

						$sisa = ($total_stok + $retur_jual) - $retur_beli;
	echo"<tr>
							<td>$no</td>
							<td>$m[kode_barang]</td>
							<td>$m[nama_barang]</td>
							<td>$m[satuan]</td>
							<td>$m[nama_kategori]</td>
							<td><center>$m[jml_stok]</center></td>
							<td><center>".$stok_masuk."</center></td>
							<td><center>".$stok_keluar."</center></td>
							<td><center>".$total_stok."</center></td>
							<td><center>".$retur_jual."</center></td>
							<td><center>".$retur_beli."</center></td>
							<td><center>".$sisa."</center></td>
						</tr>";
						$no++;
						$jumlah_pembelian=$m[bayar];
						$jumlah_p=$jumlah_p+$jumlah_pembelian;
}

echo '
	</tbody>
</table>';
?>
<script>
	window.print();
</script>