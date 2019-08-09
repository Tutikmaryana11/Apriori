<?php

$string = "<?php
	if(!isset(\$_SESSION['login_user'])){
		header('location: ../../login.php'); // Mengarahkan ke Home Page
	}

	//link buat paging
	\$linkaksi = 'med.php?mod=".$mod."';

	if(isset(\$_GET['act']))
	{
		\$act = \$_GET['act'];
		\$linkaksi .= '&act=\$act';
	}
	else
	{
		\$act = '';
	}

	\$aksi = 'mod/".$nama_folder."/".$file2."';

	switch (\$act) {
		case 'form':
			if(!empty(\$_GET['id']))
			{
				\$act = \"\$aksi?mod=".$mod."&act=edit\";
				\$query = mysql_query(\"SELECT * FROM ".$nama_table." WHERE ".$pk." = '\$_GET[id]'\");
				\$temukan = mysql_num_rows(\$query);
				if(\$temukan > 0)
				{
					\$c = mysql_fetch_assoc(\$query);
				}
				else
				{
					header(\"location:med.php?mod=".$mod."\");
				}

			}
			else
			{
				\$act = \"\$aksi?mod=".$mod."&act=simpan\";
			}

			echo\"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
				<h4 style='margin-bottom:0;padding-bottom:0;'>Form ".$nama_header."</h4>
				<p style='margin-top:0;padding-top:0;'><i>Form Input ".$nama_header."</i></p>
			</div>\";

			echo\"<form class='w3-small' method='POST' action='\$act'>
				<table>
					<tr>
						<td><label class='w3-label'>".strtoupper(label($pk))."</label></td>
						<td>:</td>
						<td><input type='text' name='id' class='w3-input' placeholder='".$pk."' value='\"?><?php echo isset(\$c['".$pk."']) ? \$c['".$pk."'] : '';?><?php echo\"'\"?> <?php echo isset(\$c['".$pk."']) ? ' readonly' : ' ';?><?php echo\" required>
						</td>
						
					</tr>";

					foreach ($non_pk as $row) {
						# code...
						
						$string .= "\n\t\t\t\t\t<tr>
						<td><label class='w3-label'>".strtoupper(label($row['column_name']))."</label></td>
						<td>:</td>
						<td><input type='text' name='".$row['column_name']."' class='w3-input' placeholder='".$row['column_name']."' value='\"?><?php echo isset(\$c['".$row['column_name']."']) ? \$c['".$row['column_name']."'] : '';?><?php echo\"' required>
						</td>
					</tr>";
					}

					
					$string .= "\n\t\t\t\t\t<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align='right'><button type='submit' name='submit' value='simpan' class='w3-btn'><i class='fa fa-save'></i> Simpan Data</button>&nbsp;

						<button type='button' class='w3-btn w3-orange' onclick='history.back()'><i class='fa fa-rotate-left'></i> Kembali</button></td>
					</tr>
				</table>

			</form>\";
			?>
				<script type=\"text/javascript\">
					$(function()
					{
						$( \".dp\" ).datepicker({
							dateFormat : \"yy-mm-dd\",
							showAnim : \"fold\"
						});
					});
				</script>
			<?php
		break;

		default :
			echo\"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
				<h4 style='margin-bottom:0;padding-bottom:0;'>".$nama_header."</h4>
				<p style='margin-top:0;padding-top:0;'><i>".$desc_header."</i></p>
			</div>\";

			flash('example_message');

			echo\"<table style='margin-top:12px;'>
				<tr>
					<td>
						<form class='w3-tiny' action='' method='GET'>	
							<input type='hidden' name='mod' value='".$mod."'>
							<div class='w3-row'>
								<div class='w3-col s1'>
									<label class='w3-label'>Search</label>
								</div>
								<div class='w3-col s2'>
									<select name='field' class='w3-select w3-padding'>
										<option value=''>- Pilih -</option>";
										foreach ($non_pk as $row) {
											# code...
											$string .= "\n\t\t\t\t\t\t\t\t\t\t<option value='".$row['column_name']."'>".strtoupper(label($row['column_name']))."</option>";
										}
									$string .="</select>
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
					<td align='right'><a href='med.php?mod=".$mod."' class='w3-btn w3-dark-grey w3-small'><i class='fa fa-refresh'></i> Refresh</a>
					<a href='med.php?mod=".$mod."&act=form' class='w3-btn w3-small w3-blue'><i class='fa fa-file'></i> Tambah</a></td>
				</tr>
				
			</table>\";

			echo\"<div style='margin-top:12px;margin-bottom:12px;'>
			<table class='w3-table w3-striped w3-bordered w3-small w3-hoverable tbl'>
				<thead>
					<tr class='w3-yellow'>
						<th>NO</th>";
					//membuat header table	
					foreach ($non_pk as $row) {
						# code...
						$string .= "\n\t\t\t\t\t\t<th>" . strtoupper(label($row['column_name'])) . "</th>";
					}
					
					$string .= "\n\t\t\t\t\t\t<th>AKSI</th>
					</tr>
				</thead>
				<tbody>\";

				\$p      = new Paging;
				\$batas  = 10;
			    if(isset(\$_GET['show']) && is_numeric(\$_GET['show']))
				{
					\$batas = (int)\$_GET['show'];
					\$linkaksi .=\"&show=\$_GET[show]\";
				}

				\$posisi = \$p->cariPosisi(\$batas);

				\$query = \"SELECT * FROM ".$nama_table." \";

				\$q 	= \"SELECT * FROM ".$nama_table."\";

				if(!empty(\$_GET['field']))
				{
					\$hideinp = \"<input type='hidden' name='field' value='\$_GET[field]'>
								<input type='hidden' name='cari' value='\$_GET[cari]'>\";

					\$linkaksi .= \"&field=\$_GET[field]&cari=\$_GET[cari]\";

					\$query .= \" WHERE \$_GET[field] LIKE '%\$_GET[cari]%'\";
					\$q .= \" WHERE \$_GET[field] LIKE '%\$_GET[cari]%'\";
				}

				\$query .= \" LIMIT \$posisi, \$batas\";
				\$q 	.= \" \";
				

				\$sql_kul = mysql_query(\$query);
				\$fd_kul = mysql_num_rows(\$sql_kul);

				if(\$fd_kul > 0)
				{
					\$no = \$posisi + 1;
					while (\$m = mysql_fetch_assoc(\$sql_kul)) {";
						$string .= "\n\t\t\t\t\t\techo\"<tr>
						\n\t\t\t\t\t\t\t<td>\$no</td>";
					//membuat header table	
					$total_field = count($non_pk) + 2;
					foreach ($non_pk as $row) {
						# code...
						
						$string .= "\n\t\t\t\t\t\t\t<td>\$m[" . $row['column_name'] . "]</td>";
					}
						

						$string .= "\n\t\t\t\t\t\t\t<td><a href='med.php?mod=".$mod."&act=form&id=\$m[".$pk."]'><i class='fa fa-pencil-square w3-large w3-text-blue'></i></a> 
							<a href='\$aksi?mod=".$mod."&act=hapus&id=\$m[".$pk."]' onclick=\\\"return confirm('Yakin hapus data');\\\"><i class='fa fa-trash w3-large w3-text-red'></i></a></td>
						\n\t\t\t\t\t\t</tr>\";\n\t\t\t\t\t\t\$no++;
					}
	

					\$jmldata = mysql_num_rows(mysql_query(\$q));

					\$jmlhalaman  = \$p->jumlahHalaman(\$jmldata, \$batas);
		    		\$linkHalaman = \$p->navHalaman(\$_GET['halaman'], \$jmlhalaman, \$linkaksi);
				}
				else
				{
					echo\"<tr>
						<td colspan='".$total_field."'><div class='w3-center'><i>".$nama_header." Not Found.</i></div></td>
					</tr>\";
				}
				

				echo\"</tbody>

			</table></div>\";

			echo\"<div class='w3-row'>
				<div class='w3-col s1'>
					<form class='w3-tiny' action='' method='GET'>
						<input type='hidden' name='mod' value='".$mod."'>\";
						if(!empty(\$hideinp))
						{
							echo \$hideinp;
						}
						echo\"<select class='w3-select w3-border' name='show' onchange='submit()'>
							<option value=''>- Show -</option>\";
							\$i=10;
							while(\$i <= 100)
							{
								if(isset(\$_GET['show']) AND (int)\$_GET['show'] == \$i)
								{
									echo\"<option value='\$i' selected>\$i</option>\";	
								}
								else
								{
									echo\"<option value='\$i'>\$i</option>\";
								}

								\$i+=10;
							}
						echo\"</select>
					</form>
				</div>
				<div class='w3-col s11'>
					<ul class='w3-pagination w3-right w3-tiny'>
						\$linkHalaman
					</ul>
				</div>
			</div>\";
		break;
	}

	
?>";

createFile($string, "../mod/" . $nama_folder . "/" . $file1);

?>