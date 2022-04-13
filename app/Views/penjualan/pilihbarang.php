<?php

$request = service('request');

$s = session();
$iduser = $s->get('id');
$entri = $request->getGet('entri', 10);
$currentPage = $request->getGet('page', 1);
$offset = ($currentPage - 1) * $entri;
$cart = $s->get($iduser . '_cart') ?: [];

// dd(json_encode($data));
$idbarang_in_cart = array_map(function ($el) {
    return $el['id'];
}, $cart);
// dd($idbarang_in_cart);
?>

<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<?php if (session()->has('success')) : ?>
    <div class="alert alert-success">
        <?= session('success') ?>
    </div>
<?php endif ?>

<h3 class="mb-3">Data Barang</h3>

<a href="<?= base_url('admin/penjualan') ?>" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Kembali</a>

<div class="datatable-wrapper shadow-lg rounded mt-4">
    <div class="datatable-heading p-4 border-bottom">
        <b>Data Barang</b>
    </div>

    <div class="datatable-content p-4">
        <div class="datatable-search-wrap d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <p class="mb-0">Menampilkan</p>

                <form action="<?= base_url('') ?>/admin/penjualan/pilihbarang" class="mx-2">
                    <input type="hidden" name="page" value="<?= $request->getGet('page') ?>">
                    <input type="hidden" name="entri" value="<?= $request->getGet('q') ?>">
                    <select onchange="this.form.submit()" name="entri" id="" class="form-control py-0 px-2">
                        <option value="10" <?= $entri == 10 ? 'selected' : '' ?>>10</option>
                        <option value="25" <?= $entri == 25 ? 'selected' : '' ?>>25</option>
                    </select>
                </form>

                <p class="mb-0">entri</p>
            </div>
            <div class="d-flex align-items-center">
                <p class="mb-0 mr-2">Pencarian: </p>
                <form action="<?= base_url('') ?>/admin/penjualan/pilihbarang" method="get">
                    <input type="hidden" name="page" value="1">
                    <input type="hidden" name="entri" value="<?= $request->getGet('entri') ?>">
                    <input type="text" name="q" id="" class="form-control" value="<?= $request->getGet('q') ?>" />
                </form>
            </div>
        </div>

        <table class="table mt-4">
            <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Stok</th>
                    <th scope="col">Pilih</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $i => $barang) : ?>
                    <?php
                    $checked = '';
                    if (in_array($barang->id, $idbarang_in_cart)) {
                        $checked = 'checked';
                    }
                    ?>
                    <tr>
                        <th scope="row"><?= $offset + $i + 1 ?></th>
                        <td><?= $barang->kategori ?></td>
                        <td><?= $barang->nama ?></td>
                        <td><?= number_format($barang->harga) ?></td>
                        <td><?= $barang->stock ?></td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input position-static centang" type="checkbox" id="blankCheckbox" value="<?= htmlspecialchars(json_encode($barang), ENT_QUOTES, 'UTF-8'); ?>" aria-label="..." <?= $checked ?>>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?= $pager->links() ?>
    </div>

</div>

<script>
    $(".centang").change(function(e) {
        let value = JSON.parse($(this).val())
        // console.log("value", value);return
        let url = ""
        if (this.checked) {
            // Set ke session
            url = "<?= base_url('') . '/admin/penjualan/centang' ?>"
        } else {
            // Hapus dari session
            url = "<?= base_url('') . '/admin/penjualan/uncentang' ?>"
        }
        NProgress.start()
        $.ajax({
            type: "POST",
            url,
            data: ({
                '<?= csrf_token() ?>': "<?= csrf_hash() ?>",
                ...value
            }),
            dataType: "JSON",
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            success: function(res) {
                NProgress.done()
                console.log('success centang/uncentang', res)
            },
            error: function(jqXHR, textStatus, errorThrown) {
                NProgress.done()
                console.log('ERROR centang/uncentang', {
                    resText: jqXHR.responseText,
                    textStatus,
                    errorThrown
                })
            }
        });
    })
</script>
<?= $this->endSection() ?>