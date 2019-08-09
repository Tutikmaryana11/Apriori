<?php

include_once "database.php";
include_once "fungsi.php";
include_once "mining.php";
include_once "display_mining.php";
include "skin.php";
?>

<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
    <h4 style='margin-bottom:0;padding-bottom:0;'>Proses Perhitungan Apriori</h4>
    <p style='margin-top:0;padding-top:0;'><i>Hasil dan detail dari proses apriori yang dilakukan</i></p>
</div>
<?php
//object database class
$db_object = new database();

$pesan_error = $pesan_success = "";
if (isset($_GET['pesan_error'])) {
    $pesan_error = $_GET['pesan_error'];
}
if (isset($_GET['pesan_success'])) {
    $pesan_success = $_GET['pesan_success'];
}

if (isset($_POST['submit'])) {
// echo "<pre>";
// print_r($_POST);
// echo "</pre>";
// exit();
    ?>
    <div class="super_sub_content">
        <div class="container">
            <div class="row">
                <?php
                $can_process = true;
                if (empty($_POST['min_support']) || empty($_POST['min_confidence'])) {
                    $can_process = false;
                    ?>
                    <script> location.replace("?menu=proses_apriori&pesan_error=Min Support dan Min Confidence harus diisi");</script>
                    <?php
                }
                if(!is_numeric($_POST['min_support']) || !is_numeric($_POST['min_confidence'])){
                    $can_process = false;
                    ?>
                    <script> location.replace("?menu=proses_apriori&pesan_error=Min Support dan Min Confidence harus diisi angka");</script>
                    <?php
                }
                //  01/09/2016 - 30/09/2016
                
                if($can_process){
                    $tgl = explode(" - ", $_POST['range_tanggal']);
                    $start = format_date($tgl[0]);
                    $end = format_date($tgl[1]);
                    
                    if(isset($_POST['id_process'])){
                        $id_process = $_POST['id_process'];
                        //delete hitungan untuk id_process
                        reset_hitungan($db_object, $id_process);
                        
                        //update log process
                        $field = array(
                            "start_date"=>$start,
                            "end_date"=>$end,
                            "min_support"=>$_POST['min_support'],
                            "min_confidence"=>$_POST['min_confidence']
                        );
                        $where = array(
                            "id"=>$id_process
                        );
                        $query = $db_object->update_record("process_log", $field, $where);
                    }
                    else{
                        //insert log process
                        $field_value = array(
                            "start_date"=>$start,
                            "end_date"=>$end,
                            "min_support"=>$_POST['min_support'],
                            "min_confidence"=>$_POST['min_confidence']
                        );
                        $query = $db_object->insert_record("process_log", $field_value);
                        $id_process = $db_object->db_insert_id();
                    }
                    //show form for update
                    ?>
   <!--                  <form method="post" action="">
                        <div class="row">
                            <div class="col-lg-4 " >
                                <div class="form-group">
                                    <tr>
                                        <td width='220px'><label class='w3-label'><label>Min Support: </label></label></td>
                                        <td width='10px'>:</td>
                                        <td><input name="min_support" type="text" 
                                           value="<?php echo $_POST['min_support']; ?>"
                                           class='w3-input' placeholder="Min Support">
                                       </td>
                                   </tr>
                               </div>
                               <div class="form-group">

                                <tr>
                                    <td width='220px'><label class='w3-label'><label>Min Confidence: </label></label></td>
                                    <td width='10px'>:</td>
                                    <td><input name="min_confidence" type="text"
                                        value="<?php echo $_POST['min_confidence']; ?>"
                                        class='w3-input' placeholder="Min Confidence">
                                    </td>
                                </tr>
                            </div>
                            <input type="hidden" name="id_process" value="<?php echo $id_process; ?>">
                            <div class="form-group">
                                <input name="submit" type="submit" value="Proses" class='w3-btn'>
                            </div>
                        </div>
                        <hr>
                        <div class="col-lg-4 " >
                            <div class="form-group">
                                <tr>
                                    <td><label class='w3-label'><label>Tanggal: </label></label></td>
                                    <td width='10px'>:</td>
                                    <td><input type="text" class='w3-input' name="range_tanggal"
                                        id="reservation" required="" placeholder="Date range" 
                                        value="<?php echo $_POST['range_tanggal']; ?>">
                                    </td>
                                </tr>
                            </div>
                        </div>
                    </div>

                </form> -->
                <form method="post" action="">
                    <div class="row">
                        <div class="col-lg-4 " >
                            <div class="form-group">

                                <tr>
                                    <td>
                                       <input name="min_support" value="<?php echo $_POST['min_support']; ?>" type="text" class='w3-input' placeholder="Min Support">
                                   </td>

                               </tr>
                           </div>
                           <div class="form-group">
                            
                              <tr>
                                    <td>
                                       <input name="min_confidence" value="<?php echo $_POST['min_confidence']; ?>" type="text" class='w3-input' placeholder="Min Confidence">
                                   </td>
                                   
                               </tr>
                        </div>
                        <div class="form-group">
                            <input name="submit" type="submit" value="Proses" class='w3-btn'>
                        </div>
                    </div>
                    <hr>
                    <div class="col-lg-4 " >
                        <!-- Date range -->
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>*Anda bisa menggunakan menu search untuk memastikan apakah data transaksi ada atau tidak sebelum melakukan proses peramalan.
                                </div>
                                <tr>
                                    <td>
                                        <input type="text" class='w3-input' name="range_tanggal"
                                id="reservation" required="" placeholder="Date range" value="<?php echo $_POST['range_tanggal']; ?>">
                                   </td>
                                   
                               </tr>
                               
                            </div><!-- /.input group -->
                        </div><!-- /.form group -->
                        <div class="form-group">
                            <input name="search_display" type="submit" value="Search" class='w3-btn'>
                        </div>
                    </div>
                </div>
            </form>
                <hr>
                <?php

                echo "
                <div class='alert alert-info'>
                ";
                echo "Min Support Absolut: " . $_POST['min_support'];
                echo "<br>";
                $sql = "SELECT COUNT(*) FROM transaksi 
                WHERE transaction_date BETWEEN '$start' AND '$end' ";
                $res = $db_object->db_query($sql);
                $num = $db_object->db_fetch_array($res);
                $minSupportRelatif = ($_POST['min_support']/$num[0]) * 100;
                echo "Min Support Relatif: " . $minSupportRelatif;
                echo "<br>";
                echo "Min Confidence: " . $_POST['min_confidence'];
                echo "<br>";
                echo "Start Date: " . $_POST['range_tanggal'];
                echo "<br> </div>";
                


                $result = mining_process($db_object, $_POST['min_support'], $_POST['min_confidence'],
                    $start, $end, $id_process);
                if ($result) {
                    display_success("Proses mining selesai");
                } else {
                    display_error("Gagal mendapatkan aturan asosiasi");
                }

                display_process_hasil_mining($db_object, $id_process);
            }
            ?>

        </div>
    </div>
</div>
<?php
} 
else {
    $where = "ga gal";
    if(isset($_POST['range_tanggal'])){
        $tgl = explode(" - ", $_POST['range_tanggal']);
        $start = format_date($tgl[0]);
        $end = format_date($tgl[1]);
        
        $where = " WHERE transaction_date "
        . " BETWEEN '$start' AND '$end'";
    }
    $sql = "SELECT
        *
    FROM
    transaksi ".$where;
    
    $query = $db_object->db_query($sql);
    $jumlah = $db_object->db_num_rows($query);
    ?>

    <div class="super_sub_content">
        <div class="container">
            <div class="row">
                <form method="post" action="">
                    <div class="row">
                        <div class="col-lg-4 " >
                            <div class="form-group">

                                <tr>
                                    <td>
                                       <input name="min_support" type="text" class='w3-input' placeholder="Min Support">
                                   </td>

                               </tr>
                           </div>
                           <div class="form-group">
                            
                              <tr>
                                    <td>
                                       <input name="min_confidence" type="text" class='w3-input' placeholder="Min Confidence">
                                   </td>
                                   
                               </tr>
                        </div>
                        <div class="form-group">
                            <input name="submit" type="submit" value="Proses" class='w3-btn'>
                        </div>
                    </div>
                    <hr>
                    <div class="col-lg-4 " >
                        <!-- Date range -->
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>*Anda bisa menggunakan menu search untuk memastikan apakah data transaksi ada atau tidak sebelum melakukan proses peramalan.
                                </div>
                                <tr>
                                    <td>
                                        <input type="text" class='w3-input pull-right' name="range_tanggal"
                                id="reservation" required="" placeholder="Date range" value="<?php echo $_POST['range_tanggal']; ?>">
                                   </td>
                                   
                               </tr>
                               
                            </div><!-- /.input group -->
                        </div><!-- /.form group -->
                        <div class="form-group">
                            <input name="search_display" type="submit" value="Search" class='w3-btn'>
                        </div>
                    </div>
                </div>
            </form>
                    <hr>

            <?php
            if (!empty($pesan_error)) {
                display_error($pesan_error);
            }
            if (!empty($pesan_success)) {
                display_success($pesan_success);
            }

            if ($jumlah == 0) {
                # code...
            echo "Jumlah Data <button type='button' class='btn btn-danger btn-sm'>0</button><br>";
            } else {
            // echo "Jumlah data: " . $jumlah . "<br>";
            echo "Jumlah Data <button type='button' class='btn btn-primary btn-sm'>$jumlah</button><br>";

            }

            if ($jumlah == 0) {
                echo "
                <div class='alert alert-danger'>
                    Data yang Anda cari tidak ditemukan.
                </div>
                ";
            } 
            else {
                ?>
                <table class='w3-table w3-striped w3-bordered w3-tiny w3-hoverable tbl'>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Produk</th>
                    </tr>
                    <?php
                    $no = 1;
                    while ($row = $db_object->db_fetch_array($query)) {
                        echo "<tr>";
                        echo "<td>" . $no . "</td>";
                        echo "<td>" . $row['transaction_date'] . "</td>";
                        echo "<td>" . $row['produk'] . "</td>";
                        echo "</tr>";
                        $no++;
                    }
                    ?>
                </table>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<?php
}
?>

 <script>
      $(function () {
        //Date range picker
        $('#reservation').daterangepicker(
                {format: 'DD/MM/YYYY'}
                );
        $('#daterange-btn').daterangepicker(
            {
              ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
              },
              startDate: moment().subtract(29, 'days'),
              endDate: moment()
            },
        function (start, end) {
          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
        );

      });
    </script>
