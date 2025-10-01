<!DOCTYPE html>
<html>
<head>
	<title>Data Laporan</title>
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
	<h3>DATA LAPORAN</h3>
</center>
  <table>
  	<thead>
  		<tr>
  			<th width="1%">No</th>
  			<th width="15%">NAMA</th>
  			<th>ALAMAT</th>
  			<th>TANGGAL LAHIR</th>
  			<th>JENIS KELAMIN</th>
  			<th>NO TELP</th>
  			<th>FOTO</th>
  		</tr>
  	</thead>
  	<tbody>
  		<?php
      $no=1;
      foreach ($query->result() as $key => $value): ?>
        <tr>
  				<td valign="top"><b><?php echo $no++; ?>.</b></td>
  				<td valign="top"><?php echo $value->nama; ?></td>
  				<td valign="top"><?php echo $value->alamat; ?></td>
  				<td valign="top"><?php echo date('d-m-Y',strtotime($value->tgl_lahir)); ?></td>
  				<td valign="top"><?php echo $value->jk; ?></td>
  				<td valign="top"><?php echo $value->kontak; ?></td>
          <td>
            <img src="<?php echo $value->foto; ?>" alt="" width="30">
          </td>
        </tr>
      <?php endforeach; ?>
  	</tbody>
  </table>
</body>
</html>
