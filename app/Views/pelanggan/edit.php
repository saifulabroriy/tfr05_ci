<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h3 class="mb-3">Edit Pelanggan</h3>

<?php echo form_open("admin/pelanggan/" . $pelanggan['id']) ?>
<input type="hidden" name="_method" value="PUT">
<?php csrf_field() ?>
<div class="form-group">
    <label for="exampleInputEmail1">Nama Pelanggan</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="nama" value="<?= $pelanggan['nama'] ?>">
    <small id="emailHelp" class="form-text text-muted">Nama pelanggan yang hendak ditambahkan</small>
</div>

<div class="form-group">
    <label for="exampleInputEmail1">Alamat</label>
    <textarea name="alamat" id="" cols="30" rows="2" class="form-control"><?= $pelanggan['alamat'] ?></textarea>
    <small id="emailHelp" class="form-text text-muted">Alamat Pelanggan</small>
</div>

<div class="form-group">
    <label for="exampleInputEmail1">No. Telp</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="notelp" value="<?= $pelanggan['notelp'] ?>">
    <small id="emailHelp" class="form-text text-muted">No. Telepon pelanggan</small>
</div>

<button type="submit" class="btn btn-primary">Simpan</button>
</form>
<?= $this->endSection() ?>