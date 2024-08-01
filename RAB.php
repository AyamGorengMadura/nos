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
    
    $fee = [
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
    
    $uom = ['Ls', 'LS', 'Ls', '', 'unit', 'unit', 'unit', 'unit', 'HOP', 'HOP', 'Ls', 'Ls', 'Ls'];

    $unitPrices = ['1.300.000', '5.000.000', '2.160.000', '650.000', '4.000.000', '5.800.000', '7.500.000', '3.500.000', '1.000.000', '2.700.000', '2.000.000', '10.000.000', '10.000.000'];
    
    $html = '
    <h1>Summary Rab</h1>
    <table class="table text-center table-bordered table-primary align-middle">
            <thead class="table-light">
                <tr>
                    <th rowspan="3">NO</th>
                    <th rowspan="3">Item Pekerjaan</th>
                    <th rowspan="3">Spec Detail</th>
                    <th colspan="6">BOQ</th>   
                    <th colspan="6" rowspan="2">Price</th>
                    <th colspan="3" rowspan="3">Remarks</th>
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
        $fee = (float) ($fees[$index] ?? 0);

        $subtotal = ($qty1 + $qty2) * $unitPrice;
        $feeTotal = ($qty1 + $qty2) * $fee;
        $total = $subtotal + $feeTotal;

        $html .= '<tr>
                    <td>' . ($index + 1) . '</td>
                    <td>' . htmlspecialchars($item[0]) . '</td>
                    <td>' . htmlspecialchars($item[1]) . '</td>
                    <td>' . htmlspecialchars($qtys1[$index]) . '</td>
                    <td>' . htmlspecialchars($uom[$index]) . '</td>
                    <td>ket</td>
                    <td>' . htmlspecialchars($qtys2[$index]) . '</td>
                    <td>Time</td>
                    <td>ket</td>
                    <td>' . number_format($unitPrice, 0, ',', '.') . '</td>
                    <td>note</td> 
                    <td>' . number_format($subtotal, 0, ',', '.') . '</td>
                    <td>' . number_format($fee, 0, ',', '.') . '</td>
                    <td>' . number_format($feeTotal, 0, ',', '.') . '</td>
                    <td>' . number_format($total, 0, ',', '.') . '</td>
                </tr>';
    }

    $html .= '
        </tbody>
        <tfoot>
        </tfoot>
    </table>';

    // Inisialisasi mpdf
    $mpdf = new \Mpdf\Mpdf();

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <a name="" id="" class="btn btn-primary" href="index.php" role="button">Kembali</a>

    <div class="card col-11 mx-auto">

        <div class="table-responsive mt-2 col-11 mx-auto rounded">
            <form action="" method="POST">
                <table class="table table-striped table-hover table-borderless table-primary align-middle text-center">
                    <thead class="table-light">
                        <tr class="">
                            <th>No</th>
                            <th>Item - Pekerjaan</th>
                            <th>Qty 1</th>
                            <th>Qty 2</th>
                            <th>Fee / Item</th>

                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <!-- Start row untuk input kebutuhan -->
                    <tr class="table-primary">
                        <td scope="row">1</td>
                        <td>Jasa Pengurusan izin penempatan</td>
                        <td><input type="number" name="qty1a" min="1" id="qty1a" required></td>
                        <td><input type="number" name="qty2a" min="1" id="qty2a" required></td>
                        <td><input type="number" name="fee1" min="1" id="fee1" required></td>
                    </tr>
                    <tr class="table-primary">
                        <td scope="row">2</td>
                        <td>Jasa DeInst. Combat Arrow dan Perangkat</td>
                        <td><input type="number" name="qty1b" min="1" id="qty1b" required></td>
                        <td><input type="number" name="qty2b" min="1" id="qty2b" required></td>
                        <td><input type="number" name="fee2" min="1" id="fee2" required></td>
                    </tr>
                    <tr class="table-primary">
                        <td scope="row">3</td>
                        <td>Jasa Mobilisasi per 50 km Combat Arrow</td>
                        <td><input type="number" name="qty1c" min="1" id="qty1c" required></td>
                        <td><input type="number" name="qty2c" min="1" id="qty2c" required></td>
                        <td><input type="number" name="fee3" min="1" id="fee3" required></td>
                    </tr>
                    <tr class="table-primary">
                        <td scope="row">4</td>
                        <td>Jasa Mobilisasi per 50 km CMON</td>
                        <td><input type="number" name="qty1d" min="1" id="qty1d" required></td>
                        <td><input type="number" name="qty2d" min="1" id="qty2d" required></td>
                        <td><input type="number" name="fee4" min="1" id="fee4" required></td>
                    </tr>
                    <tr class="table-primary">
                        <td scope="row">5</td>
                        <td>Pengadaan Custom Pole</td>
                        <td><input type="number" name="qty1e" min="1" id="qty1e" required></td>
                        <td><input type="number" name="qty2e" min="1" id="qty2e" required></td>
                        <td><input type="number" name="fee5" min="1" id="fee5" required></td>
                    </tr>
                    <tr class="table-primary">
                        <td scope="row">6</td>
                        <td>Pengadaan Custom Pole Comis (3 Sector)</td>
                        <td><input type="number" name="qty1f" min="1" id="qty1f" required></td>
                        <td><input type="number" name="qty2f" min="1" id="qty2f" required></td>
                        <td><input type="number" name="fee6" min="1" id="fee6" required></td>
                    </tr>
                    <tr class="table-primary">
                        <td scope="row">7</td>
                        <td>Jasa Inst. Combat Arrow dan Perangkat</td>
                        <td><input type="number" name="qty1g" min="1" id="qty1g" required></td>
                        <td><input type="number" name="qty2g" min="1" id="qty2g" required></td>
                        <td><input type="number" name="fee7" min="1" id="fee7" required></td>
                    </tr>
                    <tr class="table-primary">
                        <td scope="row">8</td>
                        <td>Jasa Instalasi CMON dan Perangkat</td>
                        <td><input type="number" name="qty1h" min="1" id="qty1h" required></td>
                        <td><input type="number" name="qty2h" min="1" id="qty2h" required></td>
                        <td><input type="number" name="fee8" min="1" id="fee8" required></td>
                    </tr>
                    <tr class="table-primary">
                        <td scope="row">9</td>
                        <td>Jasa DeInst. Transport Radio IPMW<1.2m</td>
                        <td><input type="number" name="qty1i" min="1" id="qty1i" required></td>
                        <td><input type="number" name="qty2i" min="1" id="qty2i" required></td>
                        <td><input type="number" name="fee9" min="1" id="fee9" required></td>
                    </tr>
                    <tr class="table-primary">
                        <td scope="row">10</td>
                        <td>Jasa Inst. Transport Radio IPMW<1.2m</td>
                        <td><input type="number" name="qty1j" min="1" id="qty1j" required></td>
                        <td><input type="number" name="qty2j" min="1" id="qty2j" required></td>
                        <td><input type="number" name="fee10" min="1" id="fee10" required></td>
                    </tr>
                    <tr class="table-primary">
                        <td scope="row">11</td>
                        <td>Js Pengurusan Penyambungan Listrik</td>
                        <td><input type="number" name="qty1k" min="1" id="qty1k" required></td>
                        <td><input type="number" name="qty2k" min="1" id="qty2k" required></td>
                        <td><input type="number" name="fee11" min="1" id="fee11" required></td>
                    </tr>
                    <tr class="table-primary">
                        <td scope="row">12</td>
                        <td>Listrik Multiguna (10.6 kVA atau lebih)</td>
                        <td><input type="number" name="qty1l" min="1" id="qty1l" required></td>
                        <td><input type="number" name="qty2l" min="1" id="qty2l" required></td>
                        <td><input type="number" name="fee12" min="1" id="fee12" required></td>
                    </tr>
                    <tr class="table-primary">
                        <td scope="row">13</td>
                        <td>Sewa lahan</td>
                        <td><input type="number" name="qty1m" min="1" id="qty1m" required></td>
                        <td><input type="number" name="qty2m" min="1" id="qty2m" required></td>
                        <td><input type="number" name="fee13" min="1" id="fee13" required></td>
                    </tr>
                        <!-- End input row -->
                    </tbody>
                </table>
                <button type="submit" name="generate_pdf" class="btn btn-primary">Generate PDF</button>
            </form>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jadikan bentuk seperti excel rab 
    side bar jadikan bottom/navbar? -->

</body>

</html>
