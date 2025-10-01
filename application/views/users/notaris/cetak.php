<!DOCTYPE html>
<html>
<head>
	<title>Data Notaris</title>
  <base href="<?php echo base_url();?>"/>
	<link rel="shortcut icon" href="assets/favicon.png" type="image/x-icon" />
</head>
<body onload="window.print();">
	<style type="text/css">
	table {
		border-collapse: collapse;
		width: 100%;
	}

	table, th, td {
		border: 1px solid black;
		font-size: 11pt;
		text-align: center;
	}
</style>
<center>
	<h3>DATA NOTARIS NTB</h3>
</center>
  <table>
  	<thead>
  		<tr>
  			<th width="1%">No</th>
  			<th width="20%">NAMA</th>
			<th>No. SK</th>
  			<th>ALAMAT</th>
  			<th>TEMPAT KEDUDUKAN</th>
  			<th width="9%">No.REGISTRASI</th>
  			<th>NO TELP</th>
			<th>EMAIL</th>
  			<!--<th>FOTO</th>-->
  		</tr>
  	</thead>
  	<tbody>
  		<?php
      $no=1;
      foreach ($query->result() as $key => $value): ?>
        <tr>
  				<td valign="top"><b><?php echo $no++; ?>.</b></td>
  				<td valign="top"><?php echo $value->nama; ?></td>
				<td valign="top"><?php echo $value->no_sk; ?></td>
  				<td valign="top"><?php echo $value->alamat_notaris; ?></td>
  				<td valign="top"><?php echo $value->tempat_kedudukan; ?></td>
				<td valign="top"><?php echo $value->no_idn; ?></td>
				<td valign="top"><?php echo $value->telpon; ?></td>
				<td valign="top"><?php echo $value->email_notaris; ?></td>
          <!--<td>
            <img src="<?php echo $value->foto; ?>" alt="" width="30">
          </td>-->
        </tr>
      <?php endforeach; ?>
  	</tbody>
  </table>
</body>
</html>
