<!DOCTYPE html>
<html>
<head>
	<title>Sistem Pendukung Keputusan Metode PSI</title>
</head>
<style>
    table {
        border-collapse: collapse;
    }
    table, th, td {
        border: 1px solid black;
    }
</style>
<body>
<h4>Hasil Akhir Perankingan</h4>
<table border="1" width="100%">
	<thead>
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
<script>
	window.print();
</script>
</body>
</html>