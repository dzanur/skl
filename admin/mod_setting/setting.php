<form id="form-setting">
    <div class="card" id="settings-card">
        <div class="card-header">
            <h4>Pengaturan Aplikasi</h4>
        </div>
        <div class="card-body">
            <p class="text-muted">pengaturan ini memuat dasar pengaturan aplikasi</p>
            <div class="form-group row align-items-center">
                <label for="site-title" class="form-control-label col-sm-3 text-md-right">Nama Sekolah</label>
                <div class="col-sm-6 col-md-9">
                    <input type="text" name="nama" class="form-control" value="<?= $setting['nama_sekolah'] ?>" required>
                </div>
            </div>
            <div class="form-group row align-items-center">
                <label for="site-title" class="form-control-label col-sm-3 text-md-right">NPSN Sekolah</label>
                <div class="col-sm-6 col-md-9">
                    <input type="text" name="npsn" class="form-control" value="<?= $setting['npsn'] ?>" required>
                </div>
            </div>
            <div class="form-group row align-items-center">
                <label for="site-description" class="form-control-label col-sm-3 text-md-right">Alamat Sekolah</label>
                <div class="col-sm-6 col-md-9">
                    <textarea class="form-control" name="alamat"><?= $setting['alamat'] ?></textarea>
                </div>
            </div>
            <div class="form-group row align-items-center">
                <label for="site-title" class="form-control-label col-sm-3 text-md-right">Kabupaten / Kota</label>
                <div class="col-sm-6 col-md-9">
                    <input type="text" name="kota" class="form-control" value="<?= $setting['kota'] ?>" required>
                </div>
            </div>
            <div class="form-group row align-items-center">
                <label class="form-control-label col-sm-3 text-md-right">Logo Sekolah</label>
                <div class="col-sm-6 col-md-9">

                    <div class="custom-file">
                        <input type="file" name="logo" class="form-control" id="site-logo">

                    </div>
                    <div class="form-text text-muted">The image must have a maximum size of 1MB</div>
                </div>

            </div>
            <div class="form-group row align-items-center">
                <label class="form-control-label col-sm-3 text-md-right">Preview</label>
                <div class="col-sm-6 col-md-6">
                    <img src="../<?= $setting['logo'] ?>" class="img-thumbnail" width="100">
                </div>
            </div>

            <div class="form-group row align-items-center">
                <label for="site-title" class="form-control-label col-sm-3 text-md-right">Kepala Sekolah</label>
                <div class="col-sm-6 col-md-9">
                    <input type="text" name="nama_kepsek" class="form-control" value="<?= $setting['nama_kepsek'] ?>" required>
                </div>
            </div>
            <div class="form-group row align-items-center">
                <label for="site-title" class="form-control-label col-sm-3 text-md-right">Nip</label>
                <div class="col-sm-6 col-md-9">
                    <input type="text" name="nip_kepsek" class="form-control" value="<?= $setting['nip_kepsek'] ?>" required>
                </div>
            </div>
            <div class="form-group row align-items-center">
                <label for="site-title" class="form-control-label col-sm-3 text-md-right">Tanggal Pengumuman</label>
                <div class="col-sm-6 col-md-9">
                    <input type="text" name="tgl_pengumuman" class="form-control" value="<?= $setting['tgl_pengumuman'] ?>" placeholder="YYYY-MM-DD HH:MM:SS" required>
                </div>
            </div>
            <div class="form-group row align-items-center">
                <label class="form-control-label col-sm-3 text-md-right">Background</label>
                <div class="col-sm-6 col-md-9">

                    <div class="custom-file">
                        <input type="file" name="bc" class="form-control" id="site-logo">

                    </div>
                    <div class="form-text text-muted">The image must have a maximum size of 1MB</div>
                </div>

            </div>
            <div class="form-group row align-items-center">
                <label class="form-control-label col-sm-3 text-md-right">Preview</label>
                <div class="col-sm-6 col-md-6">
                    <img src="../<?= $setting['banner'] ?>" class="img-thumbnail" width="100">
                </div>
            </div>

        </div>
        <div class="card-footer bg-whitesmoke text-md-right">
            <button type="submit" class="btn btn-primary" id="save-btn">Save Changes</button>
            <button class="btn btn-secondary" type="button">Reset</button>
        </div>
    </div>
</form>
<script>
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
    $('#form-setting').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: 'mod_setting/crud_setting.php?pg=ubah',
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function() {
                $('form button').on("click", function(e) {
                    e.preventDefault();
                });
            },
            success: function(data) {

                iziToast.success({
                    title: 'Mantap!',
                    message: data,
                    position: 'topRight'
                });
                setTimeout(function() {
                    window.location.reload();
                }, 2000);


            }
        });
    });
</script>