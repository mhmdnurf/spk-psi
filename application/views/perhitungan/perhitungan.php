<?php 
$this->load->view('layouts/header_admin');

//Matrix Keputusan (X)
$matriks_x = array();
foreach($alternatifs as $alternatif):
	foreach($kriterias as $kriteria):
		
		$id_alternatif = $alternatif->id_alternatif;
		$id_kriteria = $kriteria->id_kriteria;
		
		$data_pencocokan = $this->Perhitungan_model->data_nilai($id_alternatif,$id_kriteria);
		$nilai = $data_pencocokan['nilai'];
		
		$matriks_x[$id_kriteria][$id_alternatif] = $nilai;
	endforeach;
endforeach;

//Normalisasi X
$nilai_r = array();
foreach($alternatifs as $alternatif):
	foreach($kriterias as $kriteria):
		$id_alternatif = $alternatif->id_alternatif;
		$id_kriteria = $kriteria->id_kriteria;
		
		$nilai = $matriks_x[$id_kriteria][$id_alternatif];
		$type_kriteria = $kriteria->jenis;
		
		$nilai_max = @(max($matriks_x[$id_kriteria]));
		$nilai_min = @(min($matriks_x[$id_kriteria]));
				
		if($type_kriteria == 'Benefit'):
			$r = $nilai/$nilai_max;
		elseif($type_kriteria == 'Cost'):
			$r = $nilai_min/$nilai;
		endif;
			
		$nilai_r[$id_kriteria][$id_alternatif] = $r;		
	endforeach;
endforeach;

$t_nilai_r = array();
$n_mean = array();
foreach($kriterias as $kriteria){
	$tot_r = 0;
	foreach($alternatifs as $alternatif){
		$id_alternatif = $alternatif->id_alternatif;
		$id_kriteria = $kriteria->id_kriteria;
		
		$r = $nilai_r[$id_kriteria][$id_alternatif];		
		$tot_r += $r;
	}
	$t_nilai_r[$id_kriteria] = $tot_r;
	$n_mean[$id_kriteria] = (1/count($alternatifs))*$tot_r;
}

//Nilai Variasi Preferensi
$nilai_vp = array();
foreach($alternatifs as $alternatif):
	foreach($kriterias as $kriteria):
		$id_alternatif = $alternatif->id_alternatif;
		$id_kriteria = $kriteria->id_kriteria;
		
		$r = $nilai_r[$id_kriteria][$id_alternatif];
		$mean = $n_mean[$id_kriteria];
		
		$vp = pow($r-$mean,2);
		$nilai_vp[$id_kriteria][$id_alternatif] = $vp;
	endforeach;
endforeach;

$t_nilai_vp = array();
$n_dalam_p = array();
$t_ndalam = 0;
foreach($kriterias as $kriteria){
	$tot_vp = 0;
	foreach($alternatifs as $alternatif){
		$id_alternatif = $alternatif->id_alternatif;
		$id_kriteria = $kriteria->id_kriteria;
		
		$vp = $nilai_vp[$id_kriteria][$id_alternatif];		
		$tot_vp += $vp;
	}
	$t_nilai_vp[$id_kriteria] = $tot_vp;
	$n_dalam_p[$id_kriteria] = 1-$tot_vp;
	$ndalam = 1-$tot_vp;
	$t_ndalam += $ndalam;
}

$bobot = array();
foreach($kriterias as $kriteria){
	$id_kriteria = $kriteria->id_kriteria;
	
	$ndalam = $n_dalam_p[$id_kriteria];
	$bobot[$id_kriteria] = round($ndalam/$t_ndalam,4);
	
}

$nilai_p = array();
$tot_p = array();
foreach($alternatifs as $alternatif){
	$p=0;
	foreach($kriterias as $kriteria){
		$id_alternatif = $alternatif->id_alternatif;
		$id_kriteria = $kriteria->id_kriteria;
		
		$r = $nilai_r[$id_kriteria][$id_alternatif];		
		$b = $bobot[$id_kriteria];
		$np = $r*$b;
		$nilai_p[$id_kriteria][$id_alternatif] = $np;
		$p += $np;
	}
	$tot_p[$id_alternatif] = $p;
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-calculator"></i> Data Perhitungan</h1>
</div>

<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Matrix Keputusan (X)</h6>
    </div>

    <div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-primary text-white">
					<tr align="center">
						<th width="5%" rowspan="2">No</th>
						<th>Nama Alternatif</th>
						<?php foreach ($kriterias as $kriteria): ?>
							<th><?= $kriteria->kode_kriteria ?></th>
						<?php endforeach ?>
					</tr>
				</thead>
				<tbody>
					<?php 
						$no=1;
						foreach ($alternatifs as $alternatif): ?>
					<tr align="center">
						<td><?= $no; ?></td>
						<td align="left"><?= $alternatif->nama ?></td>
						<?php
						foreach ($kriterias as $kriteria):
							$id_alternatif = $alternatif->id_alternatif;
							$id_kriteria = $kriteria->id_kriteria;
							echo '<td>';
							echo $matriks_x[$id_kriteria][$id_alternatif];
							echo '</td>';
						endforeach;
						?>
					</tr>
					<?php
						$no++;
						endforeach;
					?>
					<tr align="center">
						<th colspan="2">Nilai Max</th>
						<?php foreach ($kriterias as $kriteria): ?>
						<th>
						<?php 
							$id_kriteria = $kriteria->id_kriteria;
							echo @(max($matriks_x[$id_kriteria]));
						?>
						</th>
						<?php endforeach; ?>
					</tr>
					<tr align="center">
						<th colspan="2">Nilai Min</th>
						<?php foreach ($kriterias as $kriteria): ?>
						<th>
						<?php 
							$id_kriteria = $kriteria->id_kriteria;
							echo @(min($matriks_x[$id_kriteria]));
						?>
						</th>
						<?php endforeach; ?>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Normalisasi X</h6>
    </div>

    <div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-primary text-white">
					<tr align="center">
						<th width="5%" rowspan="2">No</th>
						<th>Nama Alternatif</th>
						<?php foreach ($kriterias as $kriteria): ?>
							<th><?= $kriteria->kode_kriteria ?></th>
						<?php endforeach ?>
					</tr>
				</thead>
				<tbody>
					<?php 
						$no=1;
						foreach ($alternatifs as $alternatif): ?>
					<tr align="center">
						<td><?= $no; ?></td>
						<td align="left"><?= $alternatif->nama ?></td>
						<?php
						foreach ($kriterias as $kriteria):
							$id_alternatif = $alternatif->id_alternatif;
							$id_kriteria = $kriteria->id_kriteria;
							echo '<td>';
							echo $nilai_r[$id_kriteria][$id_alternatif];
							echo '</td>';
						endforeach;
						?>
					</tr>
					<?php
						$no++;
						endforeach;
					?>
					<tr align="center">
						<th colspan="2">Total</th>
						<?php foreach ($kriterias as $kriteria): ?>
						<th>
						<?php 
							$id_kriteria = $kriteria->id_kriteria;
							echo $t_nilai_r[$id_kriteria];
						?>
						</th>
						<?php endforeach; ?>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Nilai Mean</h6>
    </div>

    <div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-primary text-white">
					<tr align="center">
						<?php foreach ($kriterias as $kriteria): ?>
							<th><?= $kriteria->kode_kriteria ?></th>
						<?php endforeach ?>
					</tr>
				</thead>
				<tbody>
					<tr align="center">
						<?php foreach ($kriterias as $kriteria): ?>
						<td>
						<?php 
							$id_kriteria = $kriteria->id_kriteria;
							echo $n_mean[$id_kriteria];
						?>
						</td>
						<?php endforeach; ?>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Nilai Variasi Preferensi</h6>
    </div>

    <div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-primary text-white">
					<tr align="center">
						<th width="5%" rowspan="2">No</th>
						<th>Nama Alternatif</th>
						<?php foreach ($kriterias as $kriteria): ?>
							<th><?= $kriteria->kode_kriteria ?></th>
						<?php endforeach ?>
					</tr>
				</thead>
				<tbody>
					<?php 
						$no=1;
						foreach ($alternatifs as $alternatif): ?>
					<tr align="center">
						<td><?= $no; ?></td>
						<td align="left"><?= $alternatif->nama ?></td>
						<?php
						foreach ($kriterias as $kriteria):
							$id_alternatif = $alternatif->id_alternatif;
							$id_kriteria = $kriteria->id_kriteria;
							echo '<td>';
							echo $nilai_vp[$id_kriteria][$id_alternatif];
							echo '</td>';
						endforeach;
						?>
					</tr>
					<?php
						$no++;
						endforeach;
					?>
					<tr align="center">
						<th colspan="2">Total</th>
						<?php foreach ($kriterias as $kriteria): ?>
						<th>
						<?php 
							$id_kriteria = $kriteria->id_kriteria;
							echo $t_nilai_vp[$id_kriteria];
						?>
						</th>
						<?php endforeach; ?>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Nilai Dalam Preferensi</h6>
    </div>

    <div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-primary text-white">
					<tr align="center">
						<?php foreach ($kriterias as $kriteria): ?>
							<th><?= $kriteria->kode_kriteria ?></th>
						<?php endforeach ?>
					</tr>
				</thead>
				<tbody>
					<tr align="center">
						<?php foreach ($kriterias as $kriteria): ?>
						<td>
						<?php 
							$id_kriteria = $kriteria->id_kriteria;
							echo $n_dalam_p[$id_kriteria];
						?>
						</td>
						<?php endforeach; ?>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Nilai Bobot Kriteria</h6>
    </div>

    <div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-primary text-white">
					<tr align="center">
						<?php foreach ($kriterias as $kriteria): ?>
							<th><?= $kriteria->kode_kriteria ?></th>
						<?php endforeach ?>
					</tr>
				</thead>
				<tbody>
					<tr align="center">
						<?php foreach ($kriterias as $kriteria): ?>
						<td>
						<?php 
							$id_kriteria = $kriteria->id_kriteria;
							echo $bobot[$id_kriteria];
						?>
						</td>
						<?php endforeach; ?>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Menghitung Nilai Preferensi</h6>
    </div>

    <div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-primary text-white">
					<tr align="center">
						<th width="5%" rowspan="2">No</th>
						<th>Nama Alternatif</th>
						<?php foreach ($kriterias as $kriteria): ?>
							<th><?= $kriteria->kode_kriteria ?></th>
						<?php endforeach ?>
						<th>Total Nilai</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$this->Perhitungan_model->hapus_hasil();
						$no=1;
						foreach ($alternatifs as $alternatif):
						$id_alternatif = $alternatif->id_alternatif;
						?>
					<tr align="center">
						<td><?= $no; ?></td>
						<td align="left"><?= $alternatif->nama ?></td>
						<?php
						foreach ($kriterias as $kriteria):
							$id_kriteria = $kriteria->id_kriteria;
							echo '<td>';
							echo $nilai_p[$id_kriteria][$id_alternatif];
							echo '</td>';
						endforeach;
						echo '<td>';
							echo $tot_nilai = $tot_p[$id_alternatif];
						echo '</td>';
						?>
					</tr>
					<?php
						$hasil_akhir = [
							'id_alternatif' => $alternatif->id_alternatif,
							'nilai' => $tot_nilai
						];
						$this->Perhitungan_model->insert_nilai_hasil($hasil_akhir);
						$no++;
						endforeach;
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php
$this->load->view('layouts/footer_admin');
?>