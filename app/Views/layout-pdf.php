<style>
    * {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
    }

    table {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
        border: 1px solid #ddd;
    }

    body {
        padding: 32px;
    }

    th,
    td {
        text-align: left;
        padding: 8px;
        font-size: 14px;
    }

    tr:nth-child(odd) {
        background-color: #f2f2f2;
    }

    #title {
        text-align: center;
        margin-bottom: 16px;
        margin-top: 32px;
    }

    .clearfix::after {
        content: '';
        display: table;
        clear: both;
    }
</style>

<div style="" class="clearfix">
    <div style="float: left; width: 20%">
        <img style="width: 100%" src="https://upload.wikimedia.org/wikipedia/id/8/89/Logo_Apotek_K-24.png" alt="">
    </div>
    <div style="float: left; width: 79%; margin-left: 1%">
        <h4>UD Maju Terus</h4>
        <p>083117208776</p>
        <p>Jl. Raya Karang Nongko Sukodono No.30, Pekorungan, Pekarungan, Kec. Sidoarjo, Kabupaten Sidoarjo, Ja, Kab. Sidoarjo</p>
        <p>Telp. 083117208777, Email : akbarumar88@gmail.com, Website : <a href="https://vmedis.com">https://vmedis.com</a></p>
    </div>
</div>

<?= $this->renderSection('content') ?>