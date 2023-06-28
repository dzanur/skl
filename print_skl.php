<?php ob_start();
require "config/database.php";
require "config/function.php";
require "config/functions.crud.php";
include "assets/back/vendors/phpqrcode/qrlib.php";
session_start();
// if (!isset($_SESSION['id_siswaskl'])) {
//     die('Anda tidak diijinkan mengakses langsung');
// }
$siswa = fetch($koneksi, 'siswa', ['id' => dekripsi($_GET['id'])]);
$skl = fetch($koneksi, 'skl', ['id_skl' => 1]);
$tempdir = "temp/"; //Nama folder tempat menyimpan file qrcode
if (!file_exists($tempdir)) //Buat folder bername temp
    mkdir($tempdir);

    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $actual_link = str_replace("/admin/mod_siswa/","/",$actual_link);

//isi qrcode jika di scan
    $codeContents = $actual_link;

//simpan file kedalam temp
//nilai konfigurasi Frame di bawah 4 tidak direkomendasikan

QRcode::png($codeContents, $tempdir . $siswa['nisn'] . '.png', QR_ECLEVEL_M, 4);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>

    <title><?= $siswa['nama'] ?></title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="assets/back/vendors/bootstrap/dist/css/bootstrap.min.css">


</head>

<body>
    <?php if ($skl['header'] == '') { ?>
        <h3><?= $setting['nama_sekolah'] ?></h3>
        <p><small> <?= $setting['alamat'] ?></small></p>
    <?php } else { ?>
        <img src="<?= $skl['header'] ?>" width="100%">
    <?php } ?>
    <center>
        <h4 class="mt-3"><u><?= $skl['nama_surat'] ?></u></h4>
        No. Surat : <?= sprintf("%03d", $siswa['id']); ?><?= $skl['no_surat'] ?><?= date('Y') ?>
    </center>
    <br><br>
    <div class="col-md-12">
        <?= $skl['pembuka'] ?>
        <table style="margin-left: 80px;margin-right:80px" class="table table-sm border-0">
            <tr>
                <td>Nama</td>
                <td><?= $siswa['nama'] ?></td>
            </tr>
            <tr>
                <td>Tempat, Tgl Lahir</td>
                <td><?= $siswa['tempat'] ?>, <?= $siswa['tgl_lahir'] ?></td> 
            </tr>
            <tr>
                <td>NISN / No Pendaftaran</td>
                <td><?= $siswa['nisn'] ?> / <?= $siswa['nis'] ?></td>
            </tr>
            <!-- <?php if ($siswa['jurusan'] <> null) { ?>
                <tr>
                    <td>Jurusan</td>
                    <td><?= $siswa['jurusan'] ?></td>
                </tr>
            <?php } ?> -->
        </table>
        <p><?= $skl['isi_surat'] ?> </p>
        <br>
        <center>
            <?php if ($siswa['keterangan'] == 1) { ?>
                <h1>DITERIMA</h1>
            <?php } elseif ($siswa['keterangan'] == 2) { ?>
                <h1>LULUS BERSYARAT</h1>
            <?php } else { ?>
                <h1>TIDAK DITERIMA</h1>
            <?php } ?>
        </center>
        <br>
        <?= $skl['penutup'] ?>
        <br><br>
        <table width="100%">
            <tr>
                <td style="text-align: center">
                    <img class="img" src="temp/<?= $siswa['nisn'] ?>.png">
                </td>
                <td></td>
                <td style="text-align: center">
                    <?= $setting['kota'] ?>, <?= $skl['tgl_surat'] ?>
                    <p>PPDB <?= $setting['nama_sekolah'] ?></p>
                    <br><br><br><br><br>
                    <?= $setting['nama_kepsek'] ?>
                    <p><?= $setting['nip_kepsek'] ?></p>
                    <?php if ($skl['sttd'] == 1) { ?>
                        <img style="z-index: 800;position:absolute;margin-top:-150px;margin-left:150px" class="img" src="<?= $skl['ttd'] ?>" width="<?= $skl['wttd'] ?>">
                    <?php } ?>
                    <?php if ($skl['sstempel'] == 1) { ?>
                        <img style="z-index: 920;position:relative;margin-top:-120px;margin-left:-145px;opacity:0.7" class="img" src="<?= $skl['stempel'] ?>" width="<?= $skl['wstempel'] ?>">
                    <?php } ?>
                </td>
            </tr>
        </table>
        <?php if ($siswa['keterangan'] == 1) { ?>
            <?php if ($skl['header'] == '') { ?>
                <h3><?= $setting['nama_sekolah'] ?></h3>
                <p><small> <?= $setting['alamat'] ?></small></p>
            <?php } else { ?>
                <img src="<?= $skl['header'] ?>" width="100%">
            <?php } ?>
            <div class="container-fluid">
                <h4 class="text-center my-3">INFORMASI DAFTAR ULANG</h4>
                <p>Bagi yang <strong>DITERIMA JALUR AFIRMASI</strong> agar melakukan Daftar Ulang pada tanggal 3 - 7 Juli 2023 dengan menyerahkan dokumen:</p>
                <ol>
                    <li>Surat Keterangan Lulus (SKL) Asli;</li>
                    <li>Tanda Bukti Pengajuan Pendaftaran (Lembar 1 dan Lembar 2)</li>
                    <li>Print out Bukti Tanda Lulus Seleksi Jalur Afirmasi dari website PPDB SMAN 9 Tangerang;</li>
                    <li>Fotocopy Kartu Keluarga;</li>
                    <li>Fotocopy Akte Kelahiran;</li>
                    <li>Fotocopy KKS/KIP/PKH/KIS;</li>
                    <li>Pas photo ukuran 3 x 4 sebanyak 1 lembar.</li>
                    <li>Nomor 1 s.d 6 dimasukan ke dalam Map <strong>BIRU</strong> untuk <strong>LAKI-LAKI</strong>, Map <strong>MERAH</strong> untuk <strong>PEREMPUAN</strong>.</li>
                </ol>
                <p>
                    Terima kasih.
                </p>
                <table width="100%" class="border-0 mt-5">
                    <tr>
                        <td style="text-align: center; border-style: solid;" width="130">
                            <strong>Isian Online</strong><hr>
                            <img src="temp/isianonline.jpg" width="150" alt="Isian Online 2023"><br/>
                            <span class="text-center">SCAN Me</span>
                        </td>
                        <td></td>
                        <td width="100%" style="text-align: center;">
                            PANITIA PPDB <br/>
                            SMAN 9 TANGERANG
                        </td>
                    </tr>
                </table>
            <?php } ?>
        </div>
    </div>
</body>

</html>
<?php

$html = ob_get_clean();
require_once 'vendor/autoload.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream($siswa['nama'] . ".pdf", array("Attachment" => false));
exit(0);
?>