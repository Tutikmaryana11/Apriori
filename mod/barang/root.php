<?php 	
		class barang{
			function __construct(){
				mysql_connect("localhost","root","") ;
				mysql_select_db("penjualan");
			}
			function tambah_barang($kode_barang,$nama_barang,$deskripsi,$tgl_input,$harga_beli,$harga_jual,$kategori_id,$jml_stok,$satuan){
				$s=mysql_query("INSERT INTO tb_barang SET kode_barang='$kode_barang', nama_barang='$nama_barang',deskripsi='$deskripsi',tgl_input='$tgl_input',harga_beli='$harga_beli',harga_jual='$harga_jual',kategori_id='$kategori_id',jml_stok='$jml_stok',satuan='$satuan' ") or die(mysql_error());
				?>
					<script>
						alert("DATA BERHASIL");
						window.location.href="index.php";
					</script>
				<?php	
				
			}
		}
 ?>