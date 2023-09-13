<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-chart-area"></i> Data Hasil Akhir</h1>
	
    <a href="<?= base_url('Laporan'); ?>" class="btn btn-success"> <i class="fa fa-print"></i> Cetak Data </a>
</div>

<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Hasil Akhir Perankingan</h6>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead class="bg-primary text-white">
                    <tr align="center">
                        <th>Alternatif</th>
                        <th>Nama</th>
                        <th>Nilai</th>
                        <th width="15%">Rank</th>
                        <th width="25%">Persentase Potongan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($hasil as $keys):
                        $alternatif = 'A' . $no;
						$nama = $keys->nama;
                        $nilai = $keys->nilai;
                        $rank = $no;

                        // Menghitung persentase berdasarkan ranking
                        if ($rank <= 2) {
                            $persentase = 100;
                        } elseif ($rank <= 5) {
                            $persentase = 75;
                        } elseif ($rank <= 10) {
                            $persentase = 50;
                        } else {
                            $persentase = 25;
                        }
                    ?>

                    <tr align="center">
                        <td><?= $alternatif ?></td>
                        <td><?= $nama ?></td>
                        <td><?= $nilai ?></td>
                        <td><?= $rank; ?></td>
                        <td><?= $persentase; ?>%</td>
                    </tr>

                    <?php
                    $no++;
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $this->load->view('layouts/footer_admin'); ?>
