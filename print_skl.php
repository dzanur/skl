<?php ob_start();
require "config/database.php";
require "config/function.php";
require "config/functions.crud.php";
include "assets/back/vendors/phpqrcode/qrlib.php";
session_start();
if (!isset($_SESSION['id_siswaskl'])) {
    die('Anda tidak diijinkan mengakses langsung');
}
$siswa = fetch($koneksi, 'siswa', ['id' => dekripsi($_GET['id'])]);
$skl = fetch($koneksi, 'skl', ['id_skl' => 1]);
$tempdir = "temp/"; //Nama folder tempat menyimpan file qrcode
if (!file_exists($tempdir)) //Buat folder bername temp
    mkdir($tempdir);

//isi qrcode jika di scan
$codeContents = $siswa['nis'] . '-' . $siswa['nama'];

//simpan file kedalam temp
//nilai konfigurasi Frame di bawah 4 tidak direkomendasikan

QRcode::png($codeContents, $tempdir . $siswa['nis'] . '.png', QR_ECLEVEL_M, 4);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>

    <title>ZONASI_<?= $siswa['nama'] ?></title>

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
    <hr>
    <center>
        <h4><u><?= $skl['nama_surat'] ?></u></h4>
        No. Surat : <?= sprintf("%03d", $siswa['id']); ?><?= $skl['no_surat'] ?><?= date('Y') ?>
    </center>
    <br><br>
    <div class="col-md-12">
        <?= $skl['pembuka'] ?>
        <table style="margin-left: 80px;margin-right:80px" class="table table-sm table-bordered">
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
                    <td>Jurusan</td>
                    <td><?= $siswa['jurusan'] ?></td>
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
        <br><br>
        <table width="100%">
            <tr>
                <td style="text-align: center">
                    <img class="img" src="temp/<?= $siswa['nis'] ?>.png">
                </td>
                <td></td>
                <td style="text-align: center">
                    <?= $setting['kota'] ?>, <?= $skl['tgl_surat'] ?>
                    <p><?= $setting['nama_sekolah'] ?></p>
                    <br><br><br>
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
$dompdf->stream("KHS-PPDB-S9-" . $siswa['nama'] . ".pdf", array("Attachment" => false));
exit(0);
?>