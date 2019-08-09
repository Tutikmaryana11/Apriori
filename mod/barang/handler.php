<?php 	
include 'root.php';
$db=new barang();
$aksi=$_GET["aksi"];
if ($aksi=="tambah") {
	$db->tambah_barang($_POST['kode_barang'],$_POST['nama_barang'],$_POST['deskripsi'],$_POST['tgl_input'],$_POST['harga_beli'],$_POST['harga_jual'],$_POST['kategori_id'],$_POST['	jml_stok'],$_POST['satuan']);
}
 ?>