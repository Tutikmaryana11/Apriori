<?php

function display_process_hasil_mining($db_object, $id_process) {
//    ?>
    <?php
    $sql1 = "SELECT * FROM confidence "
                . " WHERE id_process = ".$id_process
                . " AND from_itemset=3 "
                ;//. " ORDER BY lolos DESC";
    $query1 = $db_object->db_query($sql1);
    ?>
    Confidence dari itemset 3
    <table class='w3-table w3-striped w3-bordered w3-tiny w3-hoverable tbl'>
        <tr>
        <th>No</th>
        <th>X => Y</th>
        <th>Support X U Y</th>
        <th>Support X </th>
        <th>Confidence</th>
        <th>Keterangan</th>
        </tr>
        <?php
            $no=1;
            $data_confidence = array();
            while($row=$db_object->db_fetch_array($query1)){
                    echo "<tr>";
                    echo "<td>".$no."</td>";
                    echo "<td>".$row['kombinasi1']." => ".$row['kombinasi2']."</td>";
                    echo "<td>".price_format($row['support_xUy'])."</td>";
                    echo "<td>".price_format($row['support_x'])."</td>";
                    echo "<td>".price_format($row['confidence'])."</td>";
                    $keterangan = ($row['confidence'] <= $row['min_confidence'])?"Tidak Lolos":"Lolos";
                    echo "<td>".$keterangan."</td>";
                echo "</tr>";
                $no++;
                if($row['lolos']==1){
                $data_confidence[] = $row;
                }
            }
            ?>
    </table>
    
    
    <?php
    $sql1 = "SELECT * FROM confidence "
                . " WHERE id_process = ".$id_process
                . " AND from_itemset=2 "
                ;//. " ORDER BY lolos DESC";
    $query1 = $db_object->db_query($sql1);
    ?>
    Confidence dari itemset 2
    <table class='w3-table w3-striped w3-bordered w3-tiny w3-hoverable tbl'>
        <tr>
        <th>No</th>
        <th>X => Y</th>
        <th>Support X U Y</th>
        <th>Support X </th>
        <th>Confidence</th>
        <th>Keterangan</th>
        </tr>
        <?php
            $no=1;
            //$data_confidence = array();
            while($row=$db_object->db_fetch_array($query1)){
                    echo "<tr>";
                    echo "<td>".$no."</td>";
                    echo "<td>".$row['kombinasi1']." => ".$row['kombinasi2']."</td>";
                    echo "<td>".price_format($row['support_xUy'])."</td>";
                    echo "<td>".price_format($row['support_x'])."</td>";
                    echo "<td>".price_format($row['confidence'])."</td>";
                    $keterangan = ($row['confidence'] <= $row['min_confidence'])?"Tidak Lolos":"Lolos";
                    echo "<td>".$keterangan."</td>";
                echo "</tr>";
                $no++;
                if($row['lolos']==1){
                $data_confidence[] = $row;
                }
            }
            ?>
    </table>

    <strong>Rule Asosiasi yang terbentuk:</strong>
    <table class='w3-table w3-striped w3-bordered w3-tiny w3-hoverable tbl'>
        <tr>
            <th>No</th>
            <th>X => Y</th>
            <th>Confidence</th>
            <th>Nilai Uji lift</th>
            <th>Korelasi rule</th>
            <!-- <th></th> -->
        </tr>
        <?php
        
        $no = 1;
        //while ($row1 = $db_object->db_fetch_array($query1)) {
        foreach($data_confidence as $key => $val){
            echo "<tr>";
            echo "<td>" . $no . "</td>";
            echo "<td>" . $val['kombinasi1']." => ".$val['kombinasi2'] . "</td>";
            echo "<td>" . price_format($val['confidence']) . "</td>";
            echo "<td>" . price_format($val['nilai_uji_lift']) . "</td>";
            echo "<td>" . ($val['korelasi_rule']) . "</td>";
            //echo "<td>" . ($val['lolos'] == 1 ? "Lolos" : "Tidak Lolos") . "</td>";
            echo "</tr>";
            $no++;
        }
        ?>
    </table>

    <?php
}
?>