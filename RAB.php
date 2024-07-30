<?PHP

include "koneksi.php";
require_once __DIR__ . '/vendor/autoload.php';

        // Check if the form is submitted
        if (isset($_POST['generate_pdf'])) {
            // Collect data from the form
            $data = [
                'izinPenempatan' => $_POST['izinPenempatan'],
                'deinstCombatArrow' => $_POST['deinstCombatArrow'],
                'mobilisasiCombatArrow' => $_POST['mobilisasiCombatArrow'],
                'mobilisasiCMON' => $_POST['mobilisasiCMON'],
                'pengadaanCustomPole' => $_POST['pengadaanCustomPole'],
                'pengadaanCustomPoleComis' => $_POST['pengadaanCustomPoleComis'],
                'instCombatArrow' => $_POST['instCombatArrow'],
                'instCMON' => $_POST['instCMON'],
                'deinstTransportRadio' => $_POST['deinstTransportRadio'],
                'instTransportRadio' => $_POST['instTransportRadio'],
                'pengurusanPenyambunganListrik' => $_POST['pengurusanPenyambunganListrik'],
                'listrikMultiguna' => $_POST['listrikMultiguna'],
                'sewaLahan' => $_POST['sewaLahan'],
            ];

            // Initialize mPDF
            $mpdf = new \Mpdf\Mpdf();

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

            $unitPrices = [1300000, 5000000, 2160000, 650000, 4000000, 5800000, 7500000, 3500000, 1000000, 2700000, 2000000, 10000000, 10000000];

            // $subtotal = ($unitPrices[0]) * ($items[1]);

            foreach ($items as $index => $item) {
                $html .= '<tr>
                    <td>' . ($index + 1) . '</td>
                    <td>' . htmlspecialchars($item[0]) . '</td>
                    <td>' . htmlspecialchars($item[1]) . '</td>
                    <td>' . htmlspecialchars($unitPrices[0]) . '</td>
                    <td>Unit</td>
                    <td></td>
                    <td></td>
                    <td>Time</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
                <tfoot>
                </tfoot>
            </table>';
            }

            $html .= '
';

            // Write HTML to PDF
            $mpdf->WriteHTML($html);

            // Output the PDF (force download)
            $mpdf->Output();
            exit;
        }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input dan PDF</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>





    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
