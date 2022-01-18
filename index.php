<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple CRUD - 1303194003</title>

    <style>
        body{
            font-family: serif;
        }
    </style>
</head>

<body>

    <?php
    // --- koneksi ke database
    $koneksi = mysqli_connect("localhost", "root", "", "db-test");
    // --- Fngsi tambah data (Create)
    function tambah($koneksi)
    {

        if (isset($_POST['btn_simpan'])) {
            $id = time();
            $nama = $_POST['nama'];
            $hasil = $_POST['hasil'];
            $lama = $_POST['lama'];
            $tanggal = $_POST['tanggal'];

            if (!empty($nama) && !empty($hasil) && !empty($lama) && !empty($tanggal)) {
                $sql = "INSERT INTO tb_panen (id,nama, hasil, lama, tanggal) VALUES(" . $id . ",'" . $nama . "','" . $hasil . "','" . $lama . "','" . $tanggal . "')";
                $simpan = mysqli_query($koneksi, $sql);
                if ($simpan && isset($_GET['aksi'])) {
                    if ($_GET['aksi'] == 'create') {
                        header('location: index.php');
                    }
                }
            } else {
                $pesan = "Tidak dapat menyimpan, data belum lengkap!";
            }
        }
    ?>
        <form action="" method="POST">
            <fieldset>
                <legend>
                    <h2>Tambah data</h2>
                </legend>
                <label>Nama tanaman <input type="text" name="nama" /></label> <br>
                <label>Hasil panen <input type="number" name="hasil" /> kg</label><br>
                <label>Lama tanam <input type="number" name="lama" /> bulan</label> <br>
                <label>Tanggal panen <input type="date" name="tanggal" /></label> <br>
                <br>
                <label>
                    <input type="submit" name="btn_simpan" value="Simpan" />
                    <input type="reset" name="reset" value="Bersihkan" />
                </label>
                <br>
                <p><?php echo isset($pesan) ? $pesan : "" ?></p>
            </fieldset>
        </form>
        <?php
    }
    // --- Fungsi Baca Data (Read)
    function tampil_data($koneksi)
    {
        $sql = "SELECT * FROM tb_panen";
        $query = mysqli_query($koneksi, $sql);

        echo "<fieldset>";
        echo "<legend><h2>Data Panen</h2></legend>";

        echo "<table border='1' cellpadding='10'>";
        echo "<tr>
            <th>ID</th>
            <th>Nama Tanaman</th>
            <th>Hasil Panen</th>
            <th>Lama Tanam</th>
            <th>Tanggal Panen</th>
            <th>Tindakan</th>
          </tr>";

        while ($data = mysqli_fetch_array($query)) {
        ?>
            <tr>
                <td><?php echo $data['id']; ?></td>
                <td><?php echo $data['nama']; ?></td>
                <td><?php echo $data['hasil']; ?> Kg</td>
                <td><?php echo $data['lama']; ?> bulan</td>
                <td><?php echo $data['tanggal']; ?></td>
                <td>
                    <a href="index.php?aksi=update&id=<?php echo $data['id']; ?>&nama=<?php echo $data['nama']; ?>&hasil=<?php echo $data['hasil']; ?>&lama=<?php echo $data['lama']; ?>&tanggal=<?php echo $data['tanggal']; ?>">Ubah</a> |
                    <a href="index.php?aksi=delete&id=<?php echo $data['id']; ?>">Hapus</a>
                </td>
            </tr>
        <?php
        }
        echo "</table>";
        echo "</fieldset>";
    }
    // --- Fungsi Ubah Data (Update)
    function ubah($koneksi)
    {
        // ubah data
        if (isset($_POST['btn_ubah'])) {
            $id = $_POST['id'];
            $nama = $_POST['nama'];
            $hasil = $_POST['hasil'];
            $lama = $_POST['lama'];
            $tanggal = $_POST['tanggal'];

            if (!empty($nama) && !empty($hasil) && !empty($lama) && !empty($tanggal)) {
                $perubahan = "tanaman='" . $nama . "',hasil=" . $hasil . ",durasi=" . $lama . ",tanggal='" . $tanggal . "'";
                $sql_update = "UPDATE tb_panen SET " . $perubahan . " WHERE id=$id";
                $update = mysqli_query($koneksi, $sql_update);
                if ($update && isset($_GET['aksi'])) {
                    if ($_GET['aksi'] == 'update') {
                        header('location: index.php');
                    }
                }
            } else {
                $pesan = "Data tidak lengkap!";
            }
        }

        // tampilkan form ubah
        if (isset($_GET['id'])) {
        ?>
            <a href="index.php"> &laquo; Home</a> |
            <a href="index.php?aksi=create"> (+) Tambah Data</a>
            <hr>

            <form action="" method="POST">
                <fieldset>
                    <legend>
                        <h2>Ubah data</h2>
                    </legend>
                    <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>" />
                    <label>Nama tanaman <input type="text" name="nama" value="<?php echo $_GET['nama'] ?>" /></label> <br>
                    <label>Hasil panen <input type="number" name="hasil" value="<?php echo $_GET['hasil'] ?>" /> kg</label><br>
                    <label>Lama tanam <input type="number" name="lama" value="<?php echo $_GET['lama'] ?>" /> bulan</label> <br>
                    <label>Tanggal panen <input type="date" name="tanggal" value="<?php echo $_GET['tanggal'] ?>" /></label> <br>
                    <br>
                    <label>
                        <input type="submit" name="btn_ubah" value="Simpan Perubahan" /> atau <a href="index.php?aksi=delete&id=<?php echo $_GET['id'] ?>"> (x) Hapus data ini</a>!
                    </label>
                    <br>
                    <p><?php echo isset($pesan) ? $pesan : "" ?></p>

                </fieldset>
            </form>
    <?php
        }
    }
    // --- Fungsi Delete
    function hapus($koneksi)
    {
        if (isset($_GET['id']) && isset($_GET['aksi'])) {
            $id = $_GET['id'];
            $sql_hapus = "DELETE FROM tb_panen WHERE id=" . $id;
            $hapus = mysqli_query($koneksi, $sql_hapus);

            if ($hapus) {
                if ($_GET['aksi'] == 'delete') {
                    header('location: index.php');
                }
            }
        }
    }
    // --- Program Utama
    if (isset($_GET['aksi'])) {
        switch ($_GET['aksi']) {
            case "create":
                echo '<a href="index.php"> &laquo; Home</a>';
                tambah($koneksi);
                break;
            case "read":
                tampil_data($koneksi);
                break;
            case "update":
                ubah($koneksi);
                tampil_data($koneksi);
                break;
            case "delete":
                hapus($koneksi);
                break;
            default:
                echo "<h3>Aksi <i>" . $_GET['aksi'] . "</i> tidaka ada!</h3>";
                tambah($koneksi);
                tampil_data($koneksi);
        }
    } else {
        tambah($koneksi);
        tampil_data($koneksi);
    }
    ?>
</body>

</html>