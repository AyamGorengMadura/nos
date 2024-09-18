<?php

include "koneksi.php";
require_once __DIR__ . '/vendor/autoload.php';


if (isset($_POST['generate_pdf'])) {

    $data = [
        'izinPenempatan' => $_POST['izinPenempatan'] ?? '',
        'deinstCombatArrow' => $_POST['deinstCombatArrow'] ?? '',
        'mobilisasiCombatArrow' => $_POST['mobilisasiCombatArrow'] ?? '',
        'mobilisasiCMON' => $_POST['mobilisasiCMON'] ?? '',
        'pengadaanCustomPole' => $_POST['pengadaanCustomPole'] ?? '',
        'pengadaanCustomPoleComis' => $_POST['pengadaanCustomPoleComis'] ?? '',
        'instCombatArrow' => $_POST['instCombatArrow'] ?? '',
        'instCMON' => $_POST['instCMON'] ?? '',
        'deinstTransportRadio' => $_POST['deinstTransportRadio'] ?? '',
        'instTransportRadio' => $_POST['instTransportRadio'] ?? '',
        'pengurusanPenyambunganListrik' => $_POST['pengurusanPenyambunganListrik'] ?? '',
        'listrikMultiguna' => $_POST['listrikMultiguna'] ?? '',
        'sewaLahan' => $_POST['sewaLahan'] ?? '',
    ];

    $items = [
        ['Jasa Pengurusan izin penempatan', 'Jasa Pengurusan Izin Penempatan perangkat seperti CMON, Combat Arrow, Combat Cruiser'],
        ['Jasa DeInst. Combat Arrow dan Perangkat', 'Jasa Deinstalasi Combat Arrow beserta Perangkatnya, perapian material include commisioning, pembongkaran Combat dan segala pendukung dan semua asesorisnya.'],
        ['Jasa Mobilisasi per 50 km Combat Arrow', 'Jasa Mobilisasi Combat Arrow per kelipatan 50 km (50km pertama include biaya instalasi) dari Warehouse/Homebase menuju suatu lokasi yang ditunjuk oleh Telkomsel, Melakukan Pengecekan kondisi fisik dan kelengkapan perangkat combat, termasuk kendaraan pengangkut CombatMelakukan labeling seluruh material dan perangkat terkait dengan penempatan Combat, dengan identitas Site ID & nama perangkat /material'],
        ['Jasa Mobilisasi per 50 km CMON', 'Jasa Mobilisasi CMON per kelipatan 50 km (50km pertama include biaya instalasi) dari Warehouse/Homebase menuju suatu lokasi yang ditunjuk oleh Telkomsel, Melakukan Pengecekan kondisi fisik dan kelengkapan perangkat combat, termasuk kendaraan pengangkut CombatMelakukan labeling seluruh material dan perangkat terkait dengan penempatan Combat, dengan identitas Site ID & nama perangkat /material.'],
        ['Pengadaan Custom Pole', 'Menyediakan Custom pole setinggi 6 meter untuk digunakan 1 sector berisi antenna dan RRU, termasuk angkur'],
        ['Pengadaan Custom Pole Comis (3 Sector)', 'Pengadaan Custom pole untuk comis menggunakan galvanish untuk 3 sector untuk beban >300kg, 6meter termasuk kebutuhan angkur'],
        ['Jasa Inst. Combat Arrow dan Perangkat', 'Jasa Instalasi Combat Arrow beserta Perangkatnya, perapian material include commisioning, melakukan tower erection dan pemasangan kaki-kaki pendukung dan asesoris Combat.'],
        ['Jasa Instalasi CMON dan Perangkat', 'Jasa Instalasi CMON beserta Perangkatnya, perapian material include commisioning, melakukan tower erection dan pemasangan kaki-kaki pendukung dan asesoris Combat'],
        ['Jasa DeInst. Transport Radio IPMW<1.2m', 'Deinstalasi perangkat Transmisi termasuk IDU dan ODU, berikut asesoris Far End dan Near End. Termasuk melakukan Commissioning dan Cross Connect sampai ke BSC/RNC sesuai dengan Type Combat.'],
        ['Jasa Inst. Transport Radio IPMW<1.2m', 'Instalasi perangkat Transmisi termasuk Pekerjaan Instalasi IDU dan ODU, berikut asesoris Far End dan Near End. Termasuk melakukan Commissioning dan Cross Connect sampai ke BSC/RNC sesuai dengan Type Combat.'],
        ['Js Pengurusan Penyambungan Listrik', 'Jasa pengurusan penyambungan listrik dan jasa pembayaran pemakaian listrik'],
        ['Listrik Multiguna (10.6 kVA atau lebih)', 'PSB PLN Listrik Multiguna (10.6 kVA)'],
        ['Sewa lahan', 'Pembayaran sewa lahan sesuai dengan harga yang disepakati Telkomsel']
    ];

    $feed = [
        $_POST['fee1'] ?? 0,
        $_POST['fee2'] ?? 0,
        $_POST['fee3'] ?? 0,
        $_POST['fee4'] ?? 0,
        $_POST['fee5'] ?? 0,
        $_POST['fee6'] ?? 0,
        $_POST['fee7'] ?? 0,
        $_POST['fee8'] ?? 0,
        $_POST['fee9'] ?? 0,
        $_POST['fee10'] ?? 0,
        $_POST['fee11'] ?? 0,
        $_POST['fee12'] ?? 0,
        $_POST['fee13'] ?? 0
    ];

    $qtys1 = [
        $_POST['qty1a'] ?? 0,
        $_POST['qty1b'] ?? 0,
        $_POST['qty1c'] ?? 0,
        $_POST['qty1d'] ?? 0,
        $_POST['qty1e'] ?? 0,
        $_POST['qty1f'] ?? 0,
        $_POST['qty1g'] ?? 0,
        $_POST['qty1h'] ?? 0,
        $_POST['qty1i'] ?? 0,
        $_POST['qty1j'] ?? 0,
        $_POST['qty1k'] ?? 0,
        $_POST['qty1l'] ?? 0,
        $_POST['qty1m'] ?? 0
    ];

    $qtys2 = [
        $_POST['qty2a'] ?? 0,
        $_POST['qty2b'] ?? 0,
        $_POST['qty2c'] ?? 0,
        $_POST['qty2d'] ?? 0,
        $_POST['qty2e'] ?? 0,
        $_POST['qty2f'] ?? 0,
        $_POST['qty2g'] ?? 0,
        $_POST['qty2h'] ?? 0,
        $_POST['qty2i'] ?? 0,
        $_POST['qty2j'] ?? 0,
        $_POST['qty2k'] ?? 0,
        $_POST['qty2l'] ?? 0,
        $_POST['qty2m'] ?? 0
    ];

        $ket1 = [
        $_POST['ket1a'] ?? 0,
        $_POST['ket1b'] ?? 0,
        $_POST['ket1c'] ?? 0,
        $_POST['ket1d'] ?? 0,
        $_POST['ket1e'] ?? 0,
        $_POST['ket1f'] ?? 0,
        $_POST['ket1g'] ?? 0,
        $_POST['ket1h'] ?? 0,
        $_POST['ket1i'] ?? 0,
        $_POST['ket1j'] ?? 0,
        $_POST['ket1k'] ?? 0,
        $_POST['ket1l'] ?? 0,
        $_POST['ket1m'] ?? 0,
    ];

        $ket2 = [
        $_POST['ket2a'] ?? 0,
        $_POST['ket2b'] ?? 0,
        $_POST['ket2c'] ?? 0,
        $_POST['ket2d'] ?? 0,
        $_POST['ket2e'] ?? 0,
        $_POST['ket2f'] ?? 0,
        $_POST['ket2g'] ?? 0,
        $_POST['ket2h'] ?? 0,
        $_POST['ket2i'] ?? 0,
        $_POST['ket2j'] ?? 0,
        $_POST['ket2k'] ?? 0,
        $_POST['ket2l'] ?? 0,
        $_POST['ket2m'] ?? 0,
    ];

        $note = [
        $_POST['notea'] ?? 0,
        $_POST['noteb'] ?? 0,
        $_POST['notec'] ?? 0,
        $_POST['noted'] ?? 0,
        $_POST['notee'] ?? 0,
        $_POST['notef'] ?? 0,
        $_POST['noteg'] ?? 0,
        $_POST['noteh'] ?? 0,
        $_POST['notei'] ?? 0,
        $_POST['notej'] ?? 0,
        $_POST['notek'] ?? 0,
        $_POST['notel'] ?? 0,
        $_POST['notem'] ?? 0,
    ];

    $activityid = $_POST['activityid'];

    $stmt = $dbconn->prepare("SELECT judul, periode, tahun FROM activities WHERE activityid = ?");
    $stmt->bind_param("i", $activityid);
    $stmt->execute();
    $result = $stmt->get_result();

    $uom = ['Ls', 'LS', 'Ls', '', 'unit', 'unit', 'unit', 'unit', 'HOP', 'HOP', 'Ls', 'Ls', 'Ls'];

    $unitPrices = ['1.300.000', '5.000.000', '2.160.000', '650.000', '4.000.000', '5.800.000', '7.500.000', '3.500.000', '1.000.000', '2.700.000', '2.000.000', '10.000.000', '10.000.000'];

    $html = '
    <style>
    table {
        border-collapse: collapse;
        width: 100%;
        font-size: 5px;
    }
    table,
    th,
    td {
        border: 1px solid black;
        font-size: 5px;
        padding: 2px;
    }
    th,
    td {
        padding: 8px;
        font-size: 5px;
    }
    th {
        background-color: #f2f2f2;
        font-size: 5px;
    }
    .kiri {
        text-align: left;
    }
    .tengah {
        text-align: center;
    }
    </style>';

    $seen = [];
    while ($row = $result->fetch_assoc()) {
        $key = $row['judul'] . $row['periode'] . $row['tahun'];

        if (!in_array($key, $seen)) {
            $html .= '<p><strong>Judul:</strong> ' . htmlspecialchars($row['judul']) . '</p>';
            $html .= '<p><strong>Periode:</strong> ' . htmlspecialchars($row['periode']) . '</p>';
            $html .= '<p><strong>Tahun:</strong> ' . htmlspecialchars($row['tahun']) . '</p>';
            $seen[] = $key;
        }
    }

    if (isset($ket1[$index])) {
    $html .= htmlspecialchars($ket1[$index]);
} else {
    $html .= '-'; // Tampilkan default jika $ket1[$index] tidak ada
}   

    $html .= '
    <table class="table text-center table-bordered table-primary align-middle">
        <thead class="table-light">
            <tr>
                <th rowspan="3">NO</th>
                <th rowspan="3">Item Pekerjaan</th>
                <th rowspan="3">Spec Detail</th>
                <th colspan="6">BOQ</th>   
                <th colspan="6" rowspan="2">Price</th>
                <th colspan="" rowspan="3">Remarks</th>
            </tr>
            <tr>
                <th colspan="3">QTY</th>   
                <th colspan="3">FREQ</th>
            </tr>
            <tr>
                <th>Qty 1</th>   
                <th>UoM</th>
                <th>Ket</th>
                <th>Qty 2</th>
                <th>UoM</th>
                <th>Ket</th>
                <th>Unit Price</th>
                <th>Note</th>
                <th>Sub Total</th>
                <th>Fee / Item</th>
                <th>Fee Total</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($items as $index => $item) {
        $qty1 = (float) ($qtys1[$index] ?? 0);
        $qty2 = (float) ($qtys2[$index] ?? 0);
        $unitPrice = (float) str_replace('.', '', $unitPrices[$index] ?? 0);
        $fee = (float) ($feed[$index] ?? 0);
        $ketitem1 = !empty($ket1[$index]) ? $ket1[$index] : '-';
        $ketitem2 = !empty($ket2[$index]) ? $ket2[$index] : '-';
        $notex = !empty($note[$index]) ? $note[$index] : '-';


        $subtotal = ($qty1 + $qty2) * $unitPrice;
        $feeTotal = ($qty1 + $qty2) * $fee;
        $total = $subtotal + $feeTotal;

        $html .= '<tr>
                <td><p class="tengah">' . ($index + 1) . '</p></td>
                <td><p class="kiri">' . htmlspecialchars($item[0]) . '</p></td>
                <td><p class="kiri">' . htmlspecialchars($item[1]) . '</p></td>
                <td><p class="tengah">' . htmlspecialchars($qtys1[$index]) . '</p></td>
                <td><p class="tengah">' . htmlspecialchars($uom[$index]) . '</p></td>
                <td><p class="tengah">' . htmlspecialchars($ketitem1) . '</td>
                <td><p class="tengah">' . htmlspecialchars($qtys2[$index]) . '</p></td>
                <td><p class="tengah"> Time </p></td>
                <td><p class="tengah">' . htmlspecialchars($ketitem2) . '</p></td>
                <td><p class="tengah">' . number_format($unitPrice, 0, ',', '.') . '</p></td>
                <td><p class="tengah">' . htmlspecialchars($notex) . '</p></td> 
                <td><p class="tengah">' . number_format($subtotal, 0, ',', '.') . '</p></td>
                <td><p class="tengah">' . number_format($fee, 0, ',', '.') . '</p></td>
                <td><p class="tengah">' . number_format($feeTotal, 0, ',', '.') . '</p></td>
                <td><p class="tengah">' . number_format($total, 0, ',', '.') . '</p></td>
                <td></td>
            </tr>';
    }

    $html .= '
    </tbody>
    <tfoot>
    </tfoot>
</table>



';


    // Inisialisasi mpdf
    $mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);
    ;

    // Memuat CSS
    $stylesheet = file_get_contents('./index.css'); // Pastikan path file CSS benar
    $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

    $mpdf->WriteHTML($html);

    // Output pdf
    $mpdf->Output();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        
        td {
            font-size: 10px;
        }

        .kiri {
            text-align: left;
        }

            input[type="number"] {
        width: 40px; /* atur lebar input */
        font-size: 10px; 
        padding: 2px;
        margin: 0; 
    }

    .spec {
        width: 190px;
    }

    .itempkr {
        width: 100px;
    }

    </style>
</head>

<body>

    
    <div class="card col-11 mx-auto mt-5">
        
        <form action="" method="POST">
            <div class="mt-3 ms-2">
                <label for="activityid">Activity ID:</label>
                <input type="text" id="activityid" name="activityid" required>
            </div>
            <a name="" id="" class="btn btn-primary ms-2 mt-2" href="index.php" role="button">Kembali</a>
        <table class="table table-hover table-bordered align-middle text-center">
        <div class="table-responsive  mt-2 col-11 mx-auto rounded">
                    <thead class="table-light align-middle">
                        <tr>
                            <th rowspan="3">NO</th>
                            <th rowspan="3">Item Pekerjaan</th>
                            <th rowspan="3">Spec Detail</th>
                            <th colspan="6">BOQ</th>   
                            <th colspan="6" rowspan="2">Price</th>
                            <th colspan="" rowspan="3">Remarks</th>
                        </tr>
                        <tr>
                            <th colspan="3">QTY</th>   
                            <th colspan="3">FREQ</th>
                        </tr>
                        <tr>
                            <th>Qty 1</th>   
                            <th>UoM</th>
                            <th>Ket</th>
                            <th>Qty 2</th>
                            <th>UoM</th>
                            <th>Ket</th>
                            <th>Unit Price</th>
                            <th>Note</th>
                            <th>Sub Total</th>
                            <th>Fee / Item</th>
                            <th>Fee Total</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <!-- Start row untuk input kebutuhan -->
                        <tr class="table-light ">
                            <td scope="row">1</td>
                            <td class="kiri itempkr">Jasa Pengurusan izin penempatan</td>
                            <td class="kiri spec">Jasa Pengurusan Izin Penempatan perangkat seperti CMON, Combat Arrow, Combat Cruiser</td>
                            <td><input type="number" name="qty1a" min="0" id="qty1a" required></td>
                            <td>Ls</td>
                            <td><input type="text" name="ket1a" min="0" id="ket1a"></td>
                            <td><input type="number" name="qty2a" min="0" id="qty2a" required></td>
                            <td>Time</td>
                            <td><input type="text" name="ket2a" min="0" id="ket2a"></td>
                            <td>1300000</td>
                            <td><input type="text" name="notea" min="0" id="notea"></td>
                            <td></td>
                            <td><input type="number" name="fee1" min="0" id="fee1" required></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="table-light">
                            <td scope="row">2</td>
                            <td class="kiri">Jasa DeInst. Combat Arrow dan Perangkat</td>
                            <td class="kiri spec">Jasa Deinstalasi Combat Arrow beserta Perangkatnya, perapian material include commisioning, pembongkaran Combat dan segala pendukung dan semua asesorisnya.</td>
                            <td><input type="number" name="qty1b" min="0" id="qty1b" required></td>
                            <td>Ls</td>
                            <td><input type="text" name="ket1b" min="0" id="ket1b"></td>
                            <td><input type="number" name="qty2b" min="0" id="qty2b" required></td>
                            <td>Time</td>
                            <td><input type="text" name="ket2b" min="0" id="ket2b"></td>
                            <td>5000000</td>
                            <td><input type="text" name="noteb" min="0" id="noteb"></td>
                            <td></td>
                            <td><input type="number" name="fee2" min="0" id="fee2" required></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="table-light">
                            <td scope="row">3</td>
                            <td class="kiri">Jasa Mobilisasi per 50 km Combat Arrow</td>
                            <td class="kiri spec">Jasa Mobilisasi Combat Arrow per kelipatan 50 km (50km pertama include biaya instalasi) dari Warehouse/Homebase menuju suatu lokasi yang ditunjuk oleh Telkomsel, Melakukan Pengecekan kondisi fisik dan kelengkapan perangkat combat, termasuk kendaraan pengangkut CombatMelakukan labeling seluruh material dan perangkat terkait dengan penempatan Combat, dengan identitas Site ID & nama perangkat /material</td>
                            <td><input type="number" name="qty1c" min="0" id="qty1c" required></td>
                            <td>Ls</td>
                            <td><input type="text" name="ket1c" min="0" id="ket1c"></td>
                            <td><input type="number" name="qty2c" min="0" id="qty2c" required></td>
                            <td>Time</td>
                            <td><input type="text" name="ket2c" min="0" id="ket2c"></td>
                            <td>2160000</td>
                            <td><input type="text" name="notec" min="0" id="notec"></td>
                            <td></td>
                            <td><input type="number" name="fee3" min="0" id="fee3" required></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="table-light">
                            <td scope="row">4</td>
                            <td class="kiri">Jasa Mobilisasi per 50 km CMON</td>
                            <td class="kiri spec">Jasa Mobilisasi CMON per kelipatan 50 km (50km pertama include biaya instalasi) dari Warehouse/Homebase menuju suatu lokasi yang ditunjuk oleh Telkomsel, Melakukan Pengecekan kondisi fisik dan kelengkapan perangkat combat, termasuk kendaraan pengangkut CombatMelakukan labeling seluruh material dan perangkat terkait dengan penempatan Combat, dengan identitas Site ID & nama perangkat /material.</td>
                            <td><input type="number" name="qty1d" min="0" id="qty1d" required></td>
                            <td></td>
                            <td><input type="text" name="ket1d" min="0" id="ket1d"></td>
                            <td><input type="number" name="qty2d" min="0" id="qty2d" required></td>
                            <td>Time</td>
                            <td><input type="text" name="ket2d" min="0" id="ket2d"></td>
                            <td>650000</td>
                            <td><input type="text" name="noted" min="0" id="noted"></td>
                            <td></td>
                            <td><input type="number" name="fee4" min="0" id="fee4" required></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="table-light">
                            <td scope="row">5</td>
                            <td class="kiri">Pengadaan Custom Pole</td>
                            <td class="kiri spec">Menyediakan Custom pole setinggi 6 meter untuk digunakan 1 sector berisi antenna dan RRU, termasuk angkur</td>
                            <td><input type="number" name="qty1e" min="0" id="qty1e" required></td>
                            <td>Unit</td>
                            <td><input type="text" name="ket1e" min="0" id="ket1e"></td>
                            <td><input type="number" name="qty2e" min="0" id="qty2e" required></td>
                            <td>Time</td>
                            <td><input type="text" name="ket2e" min="0" id="ket2e"></td>
                            <td>4000000</td>
                            <td><input type="text" name="notee" min="0" id="notee"></td>
                            <td></td>
                            <td><input type="number" name="fee5" min="0" id="fee5" required></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="table-light">
                            <td scope="row">6</td>
                            <td class="kiri">Pengadaan Custom Pole Comis (3 Sector)</td>
                            <td class="kiri spec">Pengadaan Custom pole untuk comis menggunakan galvanish untuk 3 sector untuk beban >300kg, 6meter termasuk kebutuhan angkur</td>
                            <td><input type="number" name="qty1f" min="0" id="qty1f" required></td>
                            <td>Unit</td>
                            <td><input type="text" name="ket1f" min="0" id="ket1f"></td>
                            <td><input type="number" name="qty2f" min="0" id="qty2f" required></td>
                            <td>Time</td>
                            <td><input type="text" name="ket2f" min="0" id="ket2f"></td>
                            <td>5800000</td>
                            <td><input type="text" name="notef" min="0" id="notef"></td>
                            <td></td>
                            <td><input type="number" name="fee6" min="0" id="fee6" required></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="table-light">
                            <td scope="row">7</td>
                            <td class="kiri">Jasa Inst. Combat Arrow dan Perangkat</td>
                            <td class="kiri spec">Jasa Instalasi Combat Arrow beserta Perangkatnya, perapian material include commisioning, melakukan tower erection dan pemasangan kaki-kaki pendukung dan asesoris Combat.</td>
                            <td><input type="number" name="qty1g" min="0" id="qty1g" required></td>
                            <td>Unit</td>
                            <td><input type="text" name="ket1g" min="0" id="ket1g"></td>
                            <td><input type="number" name="qty2g" min="0" id="qty2g" required></td>
                            <td>Time</td>
                            <td><input type="text" name="ket2g" min="0" id="ket2g"></td>
                            <td>7500000</td>
                            <td><input type="text" name="noteg" min="0" id="noteg"></td>
                            <td></td>
                            <td><input type="number" name="fee7" min="0" id="fee7" required></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="table-light">
                            <td scope="row">8</td>
                            <td class="kiri">Jasa Instalasi CMON dan Perangkat</td>
                            <td class="kiri spec">Jasa Instalasi CMON beserta Perangkatnya, perapian material include commisioning, melakukan tower erection dan pemasangan kaki-kaki pendukung dan asesoris Combat</td>
                            <td><input type="number" name="qty1h" min="0" id="qty1h" required></td>
                            <td>Unit</td>
                            <td><input type="text" name="ket1h" min="0" id="ket1h"></td>
                            <td><input type="number" name="qty2h" min="0" id="qty2h" required></td>
                            <td>Time</td>
                            <td><input type="text" name="ket2h" min="0" id="ket2h"></td>
                            <td>3500000</td>
                            <td><input type="text" name="noteh" min="0" id="noteh"></td>
                            <td></td>
                            <td><input type="number" name="fee8" min="0" id="fee8" required></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="table-light">
                            <td scope="row">9</td>
                            <td class="kiri">Jasa DeInst. Transport Radio IPMW<1.2m</td>
                            <td class="kiri spec">Deinstalasi perangkat Transmisi termasuk IDU dan ODU, berikut asesoris Far End dan Near End. Termasuk melakukan Commissioning dan Cross Connect sampai ke BSC/RNC sesuai dengan Type Combat.</td>
                            <td><input type="number" name="qty1i" min="0" id="qty1i" required></td>
                            <td>HOP</td>
                            <td><input type="text" name="ket1i" min="0" id="ket1i"></td>
                            <td><input type="number" name="qty2i" min="0" id="qty2i" required></td>
                            <td>Time</td>
                            <td><input type="text" name="ket2i" min="0" id="ket2i"></td>
                            <td>1000000</td>
                            <td><input type="text" name="notei" min="0" id="notei"></td>
                            <td></td>
                            <td><input type="number" name="fee9" min="0" id="fee9" required></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="table-light">
                            <td scope="row">10</td>
                            <td class="kiri">Jasa Inst. Transport Radio IPMW < 1.2m</td>
                            <td class="kiri spec">Instalasi perangkat Transmisi termasuk Pekerjaan Instalasi IDU dan ODU, berikut asesoris Far End dan Near End. Termasuk melakukan Commissioning dan Cross Connect sampai ke BSC/RNC sesuai dengan Type Combat.</td>
                            <td><input type="number" name="qty1j" min="0" id="qty1j" required></td>
                            <td>HOP</td>
                            <td><input type="text" name="ket1j" min="0" id="ket1j"></td>
                            <td><input type="number" name="qty2j" min="0" id="qty2j" required></td>
                            <td>Time</td>
                            <td><input type="text" name="ket2j" min="0" id="ket2j"></td>
                            <td>2700000</td>
                            <td><input type="text" name="notej" min="0" id="notej"></td>
                            <td></td>
                            <td><input type="number" name="fee10" min="0" id="fee10" required></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="table-light">
                            <td scope="row">11</td>
                            <td class="kiri">Js Pengurusan Penyambungan Listrik</td>
                            <td class="kiri spec">Jasa pengurusan penyambungan listrik dan jasa pembayaran pemakaian listrik</td>
                            <td><input type="number" name="qty1k" min="0" id="qty1k" required></td>
                            <td>Ls</td>
                            <td><input type="text" name="ket1k" min="0" id="ket1k"></td>
                            <td><input type="number" name="qty2k" min="0" id="qty2k" required></td>
                            <td>Time</td>
                            <td><input type="text" name="ket2k" min="0" id="ket2k"></td>
                            <td>2000000</td>
                            <td><input type="text" name="notek" min="0" id="notek"></td>
                            <td></td>
                            <td><input type="number" name="fee11" min="0" id="fee11" required></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="table-light">
                            <td scope="row">12</td>
                            <td class="kiri">Listrik Multiguna (10.6 kVA atau lebih)</td>
                            <td class="kiri spec">PSB PLN Listrik Multiguna (10.6 kVA)</td>
                            <td><input type="number" name="qty1l" min="0" id="qty1l" required></td>
                            <td>Ls</td>
                            <td><input type="text" name="ket1l" min="0" id="ket1l"></td>
                            <td><input type="number" name="qty2l" min="0" id="qty2l" required></td>
                            <td>Time</td>
                            <td><input type="text" name="ket2l" min="0" id="ket2l"></td>
                            <td>10000000</td>
                            <td><input type="text" name="notel" min="0" id="notel"></td>
                            <td></td>
                            <td><input type="number" name="fee12" min="0" id="fee12" required></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="table-light">
                            <td scope="row">13</td>
                            <td class="kiri">Sewa lahan</td>
                            <td class="kiri spec">Pembayaran sewa lahan sesuai dengan harga yangdisepakati Telkomsel</td>
                            <td><input type="number" name="qty1m" min="0" id="qty1m" required></td>
                            <td>Ls</td>
                            <td><input type="text" name="ket1m" min="0" id="ket1m"></td>
                            <td><input type="number" name="qty2m" min="0" id="qty2m" required></td>
                            <td>Time</td>
                            <td><input type="text" name="ket2m" min="0" id="ket2m"></td>
                            <td>10000000</td>
                            <td><input type="text" name="notem" min="0" id="notem"></td>
                            <td></td>
                            <td><input type="number" name="fee13" min="0" id="fee13" required></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <!-- End input row -->
                    </tbody>
                </table>
                <button type="submit" name="generate_pdf" class="btn btn-primary mb-2 ms-2">Generate PDF</button>
            </form>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jadikan bentuk seperti excel rab 
    side bar jadikan bottom/navbar? -->

</body>

</html>