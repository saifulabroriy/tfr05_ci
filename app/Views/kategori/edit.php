<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h3 class="mb-3">Edit Kategori</h3>

<?php echo form_open("admin/kategori/" . $kategori['id']) ?>
<input type="hidden" name="_method" value="PUT">
<?php csrf_field() ?>
<div class="form-group">
    <label for="kategori">Kategori</label>
    <input type="text" class="form-control" id="kategori" aria-describedby="emailHelp" name="kategori" value="<?= $kategori['kategori'] ?>" required>
    <small id="emailHelp" class="form-text text-muted">Nama Kategori yang hendak ditambahkan.</small>
</div>
<button type="submit" class="btn btn-primary">Simpan</button>
</form>
<?= $this->endSection() ?>