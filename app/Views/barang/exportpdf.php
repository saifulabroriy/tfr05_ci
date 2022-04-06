<?= $this->extend('layout-pdf') ?>
<?= $this->section('content') ?>
<h3 id="title">Laporan Data Barang</h3>

<body>
    <table class="table mt-4">
        <thead>
            <tr style="background-color: transparent;">
                <th>No.</th>
                <th>Kategori Barang</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($barang->getResult() as $i => $barang) : ?>
                <tr>
                    <th scope="row"><?= $i + 1 ?></th>
                    <td> <?= $barang->kategori ?></td>
                    <td> <?= $barang->nama ?></td>
                    <td> <?= $barang->harga ?></td>
                    <td> <?= $barang->stock ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
<?= $this->endSection() ?>