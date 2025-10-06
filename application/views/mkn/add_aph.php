<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
</head>
<body>
<h2><?= $title ?></h2>
<form method="post">
    <label>Nama Lengkap</label><br>
    <input type="text" name="nama_lengkap" required><br><br>

    <label>Username</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <label>Instansi</label><br>
    <input type="text" name="instansi" required><br><br>

    <button type="submit">Simpan</button>
</form>
</body>
</html>
