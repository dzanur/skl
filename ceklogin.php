<?php
require "config/database.php";
require "config/function.php";
require "config/functions.crud.php";
session_start();

if ($pg == 'login') {

    $username = mysqli_escape_string($koneksi, $_POST['username']);
    $password = mysqli_escape_string($koneksi, $_POST['password']);
    $siswaQ = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nopes='$username'");
    if ($username <> "" and $password <> "") {
        if (mysqli_num_rows($siswaQ) == 0) {
            $data = [
                'pesan' => 'maaf no peserta salah!'
            ];
            echo json_encode($data);
        } else {
            $siswa = mysqli_fetch_array($siswaQ);

            if ($password <> $siswa['password']) {
                $data = [
                    'pesan' => 'Password Salah !'
                ];
                echo json_encode($data);
            } else {
                $_SESSION['id_siswaskl'] = $siswa['id'];
                $data = [
                    'pesan' => 'ok'
                ];
                echo json_encode($data);
            }
        }
    }
}
if ($pg == 'bukaamplop') {
    $cek = rowcount($koneksi, 'log', ['id_user' => $_POST['id'], 'type' => 1]);
    if ($cek == 0) {
        $data = [
            'id_user' => $_POST['id'],
            'log' => 'Membuka amplop kelulusan',
            'type' => 1
        ];
        $exec = insert($koneksi, 'log', $data);
    }
}
