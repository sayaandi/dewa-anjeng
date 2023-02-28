<?php
echo '<h2>Selamat Datang ' . $_SESSION['nama'] . '</h2>';

?>

<a href="<?= base_url('masyarakat/form_aduan') ?>" class="btn btn-sm-6 btn-success mt-3">Buat Pengaduan</a>

<div class="row row-cols-1 row-cols-md-3 g-4 pt-5">

    <?php
    foreach ($aduan as $data) {


        if ($data['status'] == 0) {
            $status = 'Menunggu';
        } else {
            $status = $data['status'];
        }
        echo '<div class="col">
    <div class="card">
        <img src="' . base_url('img/') . $data['foto'] . '" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">Status:' . $status . '</h5>
            <p class="card-text">Laporan :' . $data['isi_laporan'] . '</p>
        </div>
    </div>
</div>';
    }

    ?>

</div>