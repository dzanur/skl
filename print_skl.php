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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=STIX+Two+Text:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            /* font-family: 'Barlow', sans-serif; */
            /* font-family: 'Zilla Slab', serif; */
            font-family: 'STIX Two Text', serif;
        }
    </style>


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
            <?php if ($siswa['jurusan'] <> null) { ?>
                <tr>
                    <td>Jalur</td>
                    <td><?php echo ucwords(strtolower($siswa['jurusan'])); ?></td>
                </tr>
            <?php } ?>
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
        <br>
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
                <p style="text-align: justify; ">Bagi yang <strong>DITERIMA JALUR <?=$siswa['jurusan']?></strong> agar melakukan Daftar Ulang pada tanggal 12 - 14 Juli 2023 dengan menyerahkan dokumen:</p>
                <ol>
                    <?php 
                    $documents = array(
                        "zonasi" => array(
                            "Surat Keterangan Lulus (SKL) Asli;",
                            "Tanda Bukti Pengajuan Pendaftaran (Lembar 1 dan Lembar 2)",
                            "Print out Bukti Tanda Lulus Seleksi Jalur ".ucwords(strtolower($siswa['jurusan']))." dari website PPDB SMAN 9 Tangerang;",
                            "Fotocopy Kartu Keluarga;",
                            "Fotocopy Akte Kelahiran;",
                            "Tangkapan layar titik ke titik dari lokasi tempat tinggal dan Satuan Pendidikan",
                            "Pas photo ukuran 3 x 4 sebanyak 1 lembar.",
                        ),
                        "akademik" => array(
                            "Surat Keterangan Lulus (SKL) Asli;",
                            "Tanda Bukti Pengajuan Pendaftaran (Lembar 1 dan Lembar 2)",
                            "Print out Bukti Tanda Lulus Seleksi Jalur ".ucwords(strtolower($siswa['jurusan']))." dari website PPDB SMAN 9 Tangerang;",
                            "Fotocopy Kartu Keluarga;",
                            "Fotocopy Akte Kelahiran;",
                            "Foto Copy Rapor Semester 1-5",
                            "Pas photo ukuran 3 x 4 sebanyak 1 lembar.",
                        ),
                        "nonakademik" => array(
                            "Surat Keterangan Lulus (SKL) Asli;",
                            "Tanda Bukti Pengajuan Pendaftaran (Lembar 1 dan Lembar 2)",
                            "Print out Bukti Tanda Lulus Seleksi Jalur ".ucwords(strtolower($siswa['jurusan']))." dari website PPDB SMAN 9 Tangerang;",
                            "Fotocopy Kartu Keluarga;",
                            "Fotocopy Akte Kelahiran;",
                            "Foto Copy Sertifikat Kejuaraan",
                            "Pas photo ukuran 3 x 4 sebanyak 1 lembar.",
                        ),
                        "mutasi" => array(
                            "Surat Keterangan Lulus (SKL) Asli;",
                            "Tanda Bukti Pengajuan Pendaftaran (Lembar 1 dan Lembar 2)",
                            "Print out Bukti Tanda Lulus Seleksi Jalur ".ucwords(strtolower($siswa['jurusan']))." dari website PPDB SMAN 9 Tangerang;",
                            "Fotocopy Kartu Keluarga;",
                            "Fotocopy Akte Kelahiran;",
                            "Foto Copy Surat Penugasan Orang Tua",
                            "Pas photo ukuran 3 x 4 sebanyak 1 lembar.",
                        )
                    );
                    ?>
                    <?php
                    if ($siswa['jurusan'] == "ZONASI") {
                        $i = 1;
                        foreach ($documents['zonasi'] as $document) {
                            // var_dump($document[0]);
                            echo "<li>" . $document . "</li>";
                            $i++;
                        }
                    } elseif ($siswa['jurusan'] == "PRESTASI AKADEMIK") {
                        $i = 1;
                        foreach ($documents['akademik'] as $document) {
                            // var_dump($document[0]);
                            echo "<li>" . $document . "</li>";
                            $i++;
                        }
                    } elseif ($siswa['jurusan'] == "PRESTASI NON AKADEMIK") {
                        $i = 1;
                        foreach ($documents['nonakademik'] as $document) {
                            // var_dump($document[0]);
                            echo "<li>" . $document . "</li>";
                            $i++;
                        }
                    } else {
                        $i = 1;
                        foreach ($documents['mutasi'] as $document) {
                            // var_dump($document[0]);
                            echo "<li>" . $document . "</li>";
                            $i++;
                        }
                    }
                    
                    ?>
                    <li>Nomor 1 s.d <?php echo $i++ - 1; unset($i); ?> dimasukan ke dalam Map <strong>BIRU</strong> untuk <strong>LAKI-LAKI</strong>, Map <strong>MERAH</strong> untuk <strong>PEREMPUAN</strong>. </li>
                </ol>
                <p>
                    Terima kasih.
                </p>
                <table width="100%" class="border-0 mt-5">
                    <tr>
                        <td style="text-align: center; border-style: solid;" width="130">
                            <strong>Form Pendataan <br><span class="text-danger">*</span>Wajib di isi</strong><hr>
                            <img src="temp/isianonline.jpg" width="150" alt="Isian Online 2023"><br/>
                            <span class="text-center">SCAN Me</span>
                        </td>
                        <td width="15"></td>
                        <td width="100"><a href="https://dr.sman9tangerang.sch.id/isian-online-2023" class="btn btn-primary" target="_BLANK">Buka</a></td>
                        <td width="300" style="text-align: center;">
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
$customPaper = array(0,0,610,930);
$dompdf->setPaper($customPaper);
$dompdf->render();
$dompdf->stream($siswa['nama'] . ".pdf", array("Attachment" => false));
exit(0);
?>