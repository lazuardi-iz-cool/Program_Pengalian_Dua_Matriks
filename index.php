<?php
// Inisialisasi awal variabel sesuai kamus flowchart
$b1 = isset($_POST['b1']) ? intval($_POST['b1']) : 3;
$k1 = isset($_POST['k1']) ? intval($_POST['k1']) : 2;
$b2 = isset($_POST['b2']) ? intval($_POST['b2']) : 2;
$k2 = isset($_POST['k2']) ? intval($_POST['k2']) : 3;

$pesan = "";
$step = isset($_POST['step']) ? intval($_POST['step']) : 1;
$m1 = [];
$m2 = [];
$mh = [];

// Aksi ketika tombol ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Pengecekan dimensi matriks saat melangkah ke step 2
    if (isset($_POST['set_dimensi'])) {
        if ($k1 !== $b2) {
            $pesan = "Dimensi tidak valid! Kolom Matriks 1 harus sama dengan baris Matriks 2.";
            $step = 1;
        } else {
            $step = 2;
        }
    }

    // Proses hitung perkalian matriks
    if (isset($_POST['hitung'])) {
        // Membaca input Matriks 1
        $i = 0;
        while ($i < $b1) {
            $j = 0;
            while ($j < $k1) {
                $m1[$i][$j] = floatval($_POST["m1_{$i}_{$j}"] ?? 0);
                $j++;
            }
            $i++;
        }

        // Membaca input Matriks 2
        $i = 0;
        while ($i < $b2) {
            $j = 0;
            while ($j < $k2) {
                $m2[$i][$j] = floatval($_POST["m2_{$i}_{$j}"] ?? 0);
                $j++;
            }
            $i++;
        }

        // Logika Perkalian Matriks yang Benar:
        // Baris Hasil = Baris M1 ($b1)
        // Kolom Hasil = Kolom M2 ($k2)
        $i = 0;
        while ($i < $b1) {
            $j = 0;
            while ($j < $k2) {
                $mh[$i][$j] = 0;
                $k = 0;
                // Batas k adalah Kolom M1 atau Baris M2 ($k1)
                while ($k < $k1) {
                    $mh[$i][$j] = $mh[$i][$j] + ($m1[$i][$k] * $m2[$k][$j]);
                    $k++;
                }
                $j++;
            }
            $i++;
        }
        $step = 3; // Menuju halaman hasil
    }

    // Reset ulang aplikasi
    if (isset($_POST['reset'])) {
        $b1 = 3; $k1 = 2; $b2 = 2; $k2 = 3;
        $step = 1;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kalkulator Perkalian Matriks</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .error { color: red; font-weight: bold; }
        .matrix-table { margin-bottom: 20px; border-collapse: collapse; }
        .matrix-table td { padding: 5px; }
        .matrix-table input { width: 50px; text-align: center; }
        .result-box { margin-top: 20px; padding: 10px; background: #f0f0f0; border: 1px solid #ccc; display: inline-block; }
    </style>
</head>
<body>

    <h2>Kalkulator Perkalian Matriks</h2>

    <?php if (!empty($pesan)): ?>
        <p class="error"><?php echo $pesan; ?></p>
    <?php endif; ?>

    <!-- STEP 1: Input Ukuran / Dimensi Matriks -->
    <?php if ($step == 1): ?>
        <form method="post">
            <input type="hidden" name="step" value="1">
            <h3>Step 1: Tentukan Dimensi Matriks</h3>
            <p><strong>Matriks 1:</strong>
                <input type="number" name="b1" value="<?php echo $b1; ?>" min="1"> Baris x
                <input type="number" name="k1" value="<?php echo $k1; ?>" min="1"> Kolom
            </p>
            <p><strong>Matriks 2:</strong>
                <input type="number" name="b2" value="<?php echo $b2; ?>" min="1"> Baris x
                <input type="number" name="k2" value="<?php echo $k2; ?>" min="1"> Kolom
            </p>
            <button type="submit" name="set_dimensi">Lanjut ke Input Elemen</button>
        </form>
    <?php endif; ?>

    <!-- STEP 2: Input Nilai Elemen Matriks -->
    <?php if ($step == 2): ?>
        <form method="post">
            <input type="hidden" name="step" value="2">
            <input type="hidden" name="b1" value="<?php echo $b1; ?>">
            <input type="hidden" name="k1" value="<?php echo $k1; ?>">
            <input type="hidden" name="b2" value="<?php echo $b2; ?>">
            <input type="hidden" name="k2" value="<?php echo $k2; ?>">

            <h3>Step 2: Masukkan Nilai Elemen Matriks</h3>

            <h4>Matriks 1 (<?php echo "$b1 x $k1"; ?>)</h4>
            <table class="matrix-table">
                <?php for($i=0; $i<$b1; $i++): ?>
                    <tr>
                        <?php for($j=0; $j<$k1; $j++): ?>
                            <td><input type="number" step="any" name="m1_<?php echo "{$i}_{$j}"; ?>" value="0" required></td>
                        <?php endfor; ?>
                    </tr>
                <?php endfor; ?>
            </table>

            <h4>Matriks 2 (<?php echo "$b2 x $k2"; ?>)</h4>
            <table class="matrix-table">
                <?php for($i=0; $i<$b2; $i++): ?>
                    <tr>
                        <?php for($j=0; $j<$k2; $j++): ?>
                            <td><input type="number" step="any" name="m2_<?php echo "{$i}_{$j}"; ?>" value="0" required></td>
                        <?php endfor; ?>
                    </tr>
                <?php endfor; ?>
            </table>

            <button type="submit" name="hitung">Hitung Perkalian</button>
            <button type="submit" name="reset">Kembali</button>
        </form>
    <?php endif; ?>

    <!-- STEP 3: Menampilkan Hasil Perkalian -->
    <?php if ($step == 3): ?>
        <h3>Step 3: Hasil Perkalian Matriks</h3>
        <div class="result-box">
            <table border="1" cellpadding="10" style="border-collapse: collapse; text-align: center;">
                <?php foreach ($mh as $row): ?>
                    <tr>
                        <?php foreach ($row as $val): ?>
                            <td><strong><?php echo $val; ?></strong></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <br><br>
        <form method="post">
            <button type="submit" name="reset">Hitung Lagi</button>
        </form>
    <?php endif; ?>

</body>
</html>
