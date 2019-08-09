<script src="js/chart/canvasjs.min.js"></script>
<script src="js/chart/jquery.canvasjs.min.js"></script>

<link href="css/dashboard.min.css" rel="stylesheet">
<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

<?php
date_default_timezone_set('Asia/Jakarta');

if(!isset($_SESSION['login_user'])){
		header('location: login.php'); // Mengarahkan ke Home Page
	}

	$jmlsup = mysql_num_rows(mysql_query("SELECT * FROM tb_supplier"));
	$jmlpel = mysql_num_rows(mysql_query("SELECT * FROM tb_pelanggan"));
	$jmlpj = mysql_num_rows(mysql_query("SELECT * FROM tb_penjualan"));
	$jmlbrg = mysql_num_rows(mysql_query("SELECT * FROM tb_barang"));

	?>
<!-- <div class="w3-row-padding" style="margin-top: 20px;">
	<div class="w3-col s8">
		<span style="display: inherit; background: #4689db; color: #fff; padding: 10px;">Dashboard</span>
		<div style="border-bottom:1px dashed #ccc;"></div><br>

		<?php
			$jmlsup = mysql_num_rows(mysql_query("SELECT * FROM tb_supplier"));
			$jmlpel = mysql_num_rows(mysql_query("SELECT * FROM tb_pelanggan"));
			$jmlpj = mysql_num_rows(mysql_query("SELECT * FROM tb_penjualan"));
			$jmlbrg = mysql_num_rows(mysql_query("SELECT * FROM tb_barang"));
		?>

		<div class="w3-row-padding">
			<div class="w3-col s9">
				<div class="w3-card-4 w3-green w3-leftbar w3-border-blue" style="width:100%;"><h3><b><i class="fa fa-cubes"></i><?php echo $jmlbrg; ?></b></h3>
				<span class="w3-tiny">Data Barang</span></div>
			</div>

			<div class="w3-col s9">
				<div class="w3-card-4 w3-yellow w3-leftbar w3-border-red" style="width:100%;"><h3><b><i class="fa fa-user"></i> <?php echo $jmlsup; ?></b></h3>
				<span class="w3-tiny">Data Supplier</span></div>
			</div>

			<div class="w3-col s9">
				<div class="w3-card-4 w3-indigo w3-leftbar w3-border-red" style="width:100%;"><h3><b><i class="fa fa-group"></i> <?php echo $jmlpel; ?></b></h3>
				<span class="w3-tiny">Data Pelanggan</span></div>
			</div>


			<div class="w3-col s9">
				<div class="w3-card-4 w3-blue w3-leftbar w3-border-red" style="width:100%;"><h3><b><i class="fa fa-shopping-cart"></i> <?php echo $jmlpj; ?></b></h3>
				<span>Transaksi Penjualan</span></div>
			</div>
		</div>
	</div>
	<div class="w3-col s4 w3-card">
		<?php
		if(isset($_SESSION['level']) AND ($_SESSION['level'] == "admin")) :
		?>
		Aktifitas Transaksi
		<div style="border-bottom:1px solid #ccc;"></div><br>
		<table class='w3-table w3-tiny w3-striped'>
		<?php
			$rlog = mysql_query("SELECT * FROM tb_log 
									ORDER BY id_log DESC LIMIT 10");
			$no = 1;
			while ($l = mysql_fetch_assoc($rlog)) {
				echo"<tr style='border-bottom:1px dashed #ccc;'>
					<td>$no.</td>
					<td><i>$l[timestmp] - ".$l['deskripsi']."</i></td>
				</tr>";

				$no++;
			}

		?>
		</table><br>
		<?php
		endif;
		?>
	</div>
</div> -->

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
		<!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
	</div>

	<!-- Content Row -->
	<div class="row">

		<!-- Earnings (Monthly) Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-primary shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Data Barang</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $jmlbrg; ?></div>
						</div>
						<div class="col-auto">
							<i class="fa fa-calendar"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Earnings (Monthly) Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1">Data Supplier</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $jmlsup; ?></div>
						</div>
						<div class="col-auto">
							<i class="fa fa-dollar"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Earnings (Monthly) Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-danger shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Data Pelanggan</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $jmlpel; ?></div>
						</div>
						<div class="col-auto">
							<i class="fa fa-user"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Pending Requests Card Example -->
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-warning shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Transaksi Penjualan</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $jmlpj; ?></div>
						</div>
						<div class="col-auto">
							<i class="fa fa-user"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Content Row -->

	<div class="row">

		<!-- Area Chart -->
		<div class="col-xl-8 col-lg-7">
			<div class="card shadow mb-4">
				<!-- Card Header - Dropdown -->
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">Aktifitas Transaksi</h6>
					
				</div>
				<!-- Card Body -->
				<div class="card-body">
					<div class="chart-area">
						<!-- <div class="w3-col s4 w3-card"> -->
							<?php
							if(isset($_SESSION['level']) AND ($_SESSION['level'] == "admin")) :
								?>
							<!-- Aktifitas Transaksi -->
							<!-- <div style="border-bottom:1px solid #ccc;"></div><br> -->
							<table class='w3-table w3-tiny w3-striped'>
								<?php
								$rlog = mysql_query("SELECT * FROM tb_log 
									ORDER BY id_log DESC LIMIT 10");
								$no = 1;
								while ($l = mysql_fetch_assoc($rlog)) {
									echo"<tr style='border-bottom:1px dashed #ccc;'>
									<td>$no.</td>
									<td><i>$l[timestmp] - ".$l['deskripsi']."</i></td>
									</tr>";

									$no++;
								}

								?>
							</table><br>
							<?php
						endif;
						?>
					<!-- </div> -->
				</div>
			</div>
		</div>
	</div>

	<!-- Pie Chart -->
	<div class="col-xl-4 col-lg-5">
	<!-- <div class="col-lg-6 mb-4"> -->

		<!-- Illustrations -->
		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary">Mulai belanja!</h6>
			</div>
			<div class="card-body">
				<div class="text-center">
					<img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="undraw_posting_photo.svg" alt="">
				</div>
				<p>Mulai transaksi penjualan Anda di  <a target="_blank" rel="nofollow" href="http://localhost/penjualan/med.php?mod=penjualan">Transaksi Penjualan</a>, dan dapatkan diskon menarik untuk setiap penjualan yang dilakukan</p>
				<a target="_blank" rel="nofollow" href="http://localhost/penjualan/med.php?mod=penjualan">Transaksi Penjualan &rarr;</a>
			</div>
		</div>

	</div>
	</div>
</div>