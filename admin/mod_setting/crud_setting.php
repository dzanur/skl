<?php
require "../../config/database.php";
require "../../config/function.php";
require "../../config/functions.crud.php";
session_start();
if (!isset($_SESSION['id_user'])) {
    die('Anda tidak diijinkan mengakses langsung');
}
if ($pg == 'ubah') {
    $data = [
        'nama_sekolah' => $_POST['nama'],
        'alamat' => $_POST['alamat'],
        'kota' => $_POST['kota'],
        'npsn' => $_POST['npsn'],
        'nama_kepsek' => $_POST['nama_kepsek'],
        'nip_kepsek' => $_POST['nip_kepsek'],
        'tgl_pengumuman' => $_POST['tgl_pengumuman']
    ];
    $where = [
        'id_setting' => 1
    ];
    $exec = update($koneksi, 'setting', $data, $where);
    echo mysqli_error($koneksi);
    if ($exec) {
        $ektensi = ['jpg', 'png', 'JPG', 'PNG'];
        if ($_FILES['logo']['name'] <> '') {
            $logo = $_FILES['logo']['name'];
            $temp = $_FILES['logo']['tmp_name'];
            $ext = explode('.', $logo);
            $ext = end($ext);
            if (in_array($ext, $ektensi)) {
                $dest = 'assets/img/logo/logo' . rand(1, 1000) . '.' . $ext;
                $upload = move_uploaded_file($temp, '../../' . $dest);
                if ($upload) {
                    $data2 = [
                        'logo' => $dest
                    ];
                    $exec = update($koneksi, 'setting', $data2, $where);
                } else {
                    echo "gagal";
                }
            }
        }
        if ($_FILES['bc']['name'] <> '') {
            $header = $_FILES['bc']['name'];
            $temp = $_FILES['bc']['tmp_name'];
            $ext = explode('.', $header);
            $ext = end($ext);
            if (in_array($ext, $ektensi)) {
                $dest = 'assets/img/header/banner' . rand(1, 1000) . '.' . $ext;
                $upload = move_uploaded_file($temp, '../../' . $dest);
                if ($upload) {
                    $data4 = [
                        'banner' => $dest
                    ];
                    $exec = update($koneksi, 'setting', $data4, $where);
                } else {
                    echo "gagal";
                }
            }
        }
    } else {
        echo "Gagal menyimpan";
    }
}
