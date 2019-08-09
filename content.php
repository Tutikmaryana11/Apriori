<?php
    include"class/paging.php";
    include"lib/fungsi_indotgl.php";
    include"lib/all_function.php";
    
    if(isset($_GET['mod']))
    {
        $mod = $_GET['mod']; //modul yang akan ditampilkan
        if ($mod == "home") {
            include"dashboard.php";
        }
        elseif($mod == "user")
        {
            include"mod/user/user.php";
        }
        elseif($mod == "menu")
        {
            include"mod/menu/menu.php";
        }
        elseif($mod == "modul")
        {
            include"mod/modul/modul.php";
        }

        elseif($mod == "kategori")
        {
            include"mod/kategori/kategori.php";
        }
        elseif($mod == "barang")
        {
            include"mod/barang/barang.php";
        }
        elseif($mod == "pelanggan")
        {
            include"mod/pelanggan/pelanggan.php";
        }
        elseif($mod == "supplier")
        {
            include"mod/supplier/supplier.php";
        }
        elseif($mod == "penjualan")
        {
            include"mod/penjualan/penjualan.php";
        }
        elseif($mod == "pembelian")
        {
            include"mod/pembelian/pembelian.php";
        }
        elseif($mod == "returpenjualan")
        {
            include"mod/retur/retur_penjualan.php";
        }
        elseif($mod == "returpembelian")
        {
            include"mod/returpembelian/returpembelian.php";
        }
        elseif($mod == "laporan")
        {
            include"mod/laporan/laporan.php";
        }
         elseif($mod == "lappembelian")
        {
            include"mod/laporan/lap_pembelian.php";
        }
        elseif($mod == "cetak-lap-pembelian")
        {
            include"mod/laporan/cetak-lap-pembelian.php";
        }
        elseif($mod == "laplaris")
        {
            include"mod/laporan/laporan_terlaris.php";
        }
         elseif($mod == "peramalan")
        {
            include"mod/peramalan/apriori/proses_apriori.php";
        }
        elseif($mod == "hasil_rule")
        {
            include"mod/peramalan/apriori/hasil_rule.php";
        }
        elseif($mod == "view_rule")
        {
            include"mod/peramalan/apriori/view_rule.php";
        }
        

    }
    else
    {
        header("location:index.php");
    }
?>