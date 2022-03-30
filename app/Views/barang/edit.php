<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h3 class="mb-3">Edit Barang</h3>

<?php echo form_open("admin/barang/" . $barang['id']) ?>
<input type="hidden" name="_method" value="PUT">
<?php csrf_field() ?>
<div class="form-group">
    <label for="idkategori">Kategori</label>
    <select class="form-control" name="kategori" id="kategori">
        <?php foreach ($kategori as $i => $kategori) : ?>
            <?php if ($kategori['id'] == $barang['idkategori']) : ?>
                <option value="<?= $kategori['id'] ?>" selected><?= $kategori['kategori'] ?></option>
            <?php else : ?>
                <option value="<?= $kategori['id'] ?>"><?= $kategori['kategori'] ?></option>
            <?php endif ?>
        <?php endforeach; ?>
    </select>
    <small id="emailHelp" class="form-text text-muted">Kategori barang yang hendak ditambahkan.</small>

    <label for="nama">Nama</label>
    <input type="text" class="form-control" id="nama" aria-describedby="emailHelp" name="nama" value="<?= $barang['nama'] ?>" required>
    <small id="emailHelp" class="form-text text-muted">Nama barang yang hendak ditambahkan.</small>

    <label for="harga">Harga</label>
    <input type="number" class="form-control" id="harga" aria-describedby="emailHelp" name="harga" value="<?= $barang['harga'] ?>" required>
    <small id=" emailHelp" class="form-text text-muted">Harga barang yang hendak ditambahkan.</small>

    <label for="stok">Stok</label>
    <input type="text" class="form-control" id="stok" aria-describedby="emailHelp" name="stok" value="<?= $barang['stock'] ?>" required>
    <small id=" emailHelp" class="form-text text-muted">Stok barang yang hendak ditambahkan.</small>
</div>
<button type="submit" class="btn btn-primary">Simpan</button>
</form>
<?= $this->endSection() ?>