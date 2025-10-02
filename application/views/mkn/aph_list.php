<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
</head>
<body>
<h2><?= $title ?></h2>
<a href="<?= site_url('mkn/add_aph') ?>">Tambah Akun APH</a>
<table border="1" cellpadding="5">
    <tr>
        <th>Nama</th>
        <th>Username</th>
        <th>Instansi</th>
        <th>Status</th>
    </tr>
    <?php foreach ($aph_list as $aph): ?>
        <tr>
            <td><?= $aph->nama_lengkap ?></td>
            <td><?= $aph->username ?></td>
            <td><?= $aph->instansi ?></td>
            <td><?= $aph->aktif ? 'Aktif' : 'Nonaktif' ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
