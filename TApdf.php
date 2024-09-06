<?php
require_once __DIR__ . '/vendor/autoload.php';
require 'koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mpdf\Mpdf;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prog = $_POST['program_id']; // Ambil ID program dari input form
    $siteid = [];

    // Generate PDF
if (isset($_POST['generate_pdf'])) {
    $mpdf = new \Mpdf\Mpdf();

    // Fetch program details
    $query = "SELECT * FROM program WHERE program_title = ?";
    $stmt = $dbconn->prepare($query);
    $stmt->bind_param('s', $prog);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch site IDs
    $siteQuery = "SELECT ds.SiteID FROM datasite ds 
                  INNER JOIN program p ON p.program_id 
                  WHERE p.program_id = ?";
    $stmtSite = $dbconn->prepare($siteQuery);
    $stmtSite->bind_param('s', $programID);
    $stmtSite->execute();
    $siteResult = $stmtSite->get_result();
    
    $siteIDs = [];
    while ($siteRow = $siteResult->fetch_assoc()) {
        $siteIDs[] = $siteRow['SiteID'];
    }
    
    // Add Cover Page
    $coverHtml = '<h1 style="text-align: center; font-size: 50px;">Laporan</h1>';
    $coverHtml .= '<h3 style="text-align: center; font-size: 30px;">' . $prog . '</h3>';
    
    while ($row = $result->fetch_assoc()) {
        $coverHtml .= '<p style="text-align: center; font-size: 30px;">' . $row['siteid'] . '</p>';
    }

    $mpdf->WriteHTML($coverHtml);        
    $mpdf->AddPage();
    
    // Reset result set pointer
    $result->data_seek(0);

    // Loop through each site in the result
    while ($row = $result->fetch_assoc()) {
        $siteQuery = "SELECT * FROM sitetb WHERE siteId = ?";
        $stmtSite = $dbconn->prepare($siteQuery);
        $stmtSite->bind_param('s', $row['siteid']);
        $stmtSite->execute();
        $siteResult = $stmtSite->get_result();
        
        while ($siteRow = $siteResult->fetch_assoc()) {
            // Site-specific content
            $html = '<h1 style="text-align: center;">Technical Assessment Combat ' . $siteRow['siteId'] . '</h1><br>';
            $html .= '<h1>Latar Belakang</h1>';
            $html .= '<p>Latar belakang dilaksanakannya pekerjaan ini adalah sebagai berikut;</p>';
            $html .= '
            <table>
                <tr><td>A.</td><td></td><td>Adanya komplain yang disebabkan oleh Utilisasi trafik yang tinggi di area ' . $siteRow['desa'] . ' Kecamatan ' . $siteRow['kecamatan'] . ' Kabupaten/Kota Boyolali khususnya pada saat busy hour.</td></tr>
                <tr><td>B.</td><td></td><td>Informasi dari Dinas Kominfo kab Boyolali bahwa di Desa ' . $siteRow['desa'] . ' Kecamatan ' . $siteRow['kecamatan'] . ' adalah desa blank spot seluler semua operator.</td></tr>
                <tr><td>C.</td><td></td><td>Adanya surat permintaan layanan sinyal Telkomsel oleh pemerintah Desa ' . $siteRow['desa'] . ' Kecamatan ' . $siteRow['kecamatan'] . ' Kabupaten ' . $siteRow['kabupaten'] . ' mengetehui Dinas Kominfo Kabupaten ' . $siteRow['kabupaten'] . '.</td></tr>
                <tr><td>D.</td><td></td><td>Bad Coverage di Desa ' . $siteRow['desa'] . ' kecamatan ' . $siteRow['kecamatan'] . ' dimana mayoritas penduduknya merupakan customer Telkomsel.</td></tr>
                <tr><td>E.</td><td></td><td>Customer Complain mengenai kualitas sinyal Telkomsel yang tidak bagus di Desa ' . $siteRow['desa'] . ' Kecamatan ' . $siteRow['kecamatan'] . ' Kabupaten ' . $siteRow['kabupaten'] . '.</td></tr>
            </table>';
            
            $html .= '<h1>Analisa Bisnis dan Teknis</h1>';
            $html .= '
            <table>
                <tr><td>A.</td><td></td><td>Potensi Site Kandidat Lokasi ' . $siteRow['siteId'] . ' ' . $siteRow['site_name_plan_combat'] . ' berada di kecamatan ' . $siteRow['kecamatan'] . ', Kabupaten ' . $siteRow['kabupaten'] . ', Jawa Tengah, Indonesia. Lokasi koordinatnya di Longitude ' . $siteRow['longitude'] . ', Latitude ' . $siteRow['latitude'] . '.</td></tr>
                <tr><td>B.</td><td></td><td>Coverage seluler di Desa ' . $siteRow['desa'] . ' blank sinyal operator manapun termasuk Telkomsel.</td></tr>
                <tr><td>C.</td><td></td><td>Kebutuhan Telkomsel di daerah tersebut cukup tinggi, karena meliputi pemukiman padat penduduk dengan jumlah 5625 penduduk ####.</td></tr>
                <tr><td>D.</td><td></td><td>Merupakan area blue ocean dimana tidak ada satupun operator seluler yang menjangkau Desa ' . $siteRow['desa'] . '.</td></tr>
                <tr><td>E.</td><td></td><td>Untuk mengamankan target Payload, Traffic, dan Revenue Telkomsel Tahun 2024, dan menambah coverage share dan pelanggan baru di Kab ' . $siteRow['kabupaten'] . '.</td></tr>
            </table>';
            
            // Add images
            $images = explode(',', $row['gambar']);
            foreach ($images as $image) {
                $html .= '<div style="text-align: center; margin: 5px; margin-top: 20px;"><img src="' . $image . '" style="width: 175px; height: auto;"></div>';
            }

            $html .= '<p>Deskripsi: ' . $row['deskripsi_gambar'] . '</p>';

            $html .= '<h1>Proyeksi Revenue</h1>';
            $html .= '
            <table>
                <tr><td></td><td></td><td>Proyeksi Revenue by SIFA = Rp. ' . $siteRow['prediksi_revenue_sifa'] . ' dengan perhitungan jumlah populasi 8500 jiwa, sedangkan proyeksi revenue by Branch Surakarta dengan ARPU Rp. 60.000, Rev. BB=Rp. 51.000.000, Rev. Digital=Rp. 10.000.000 dan Rev. Voice=Rp. 5.000.000, total proyeksi revenue Combat = Rp. 52.500.000.</td></tr>
            </table>';
            
            $html .= '<h1>Ruang Lingkup Pekerjaan</h1>';
            $html .= '<p>Berikut ini adalah deskripsi ruang lingkup pekerjaan dan lokasi rencana Mobilisasi dan Instalasi Combat ' . $siteRow['site_name_plan_combat'] . ' :</p>';
            $html .= '
            <table>
                <tr><td>A.</td><td></td><td>Donor Combat ' . $siteRow['site_name_plan_combat'] . ' dari Combat ' . $siteRow['site_name_combat_donor'] . '.</td></tr>
                <tr><td>B.</td><td></td><td>Lokasi Combat ' . $siteRow['site_name_plan_combat'] . ' ' . $siteRow['siteId'] . ' di desa ' . $siteRow['desa'] . ' Kecamatan ' . $siteRow['kecamatan'] . ' Kabupaten ' . $siteRow['kabupaten'] . ' Latitude ' . $siteRow['latitude'] . ', Longitude ' . $siteRow['longitude'] . '.</td></tr>
                <tr><td>C.</td><td></td><td>Dismantle infra dan perangkat Combat ' . $siteRow['site_name_plan_combat'] . '.</td></tr>
                <tr><td>D.</td><td></td><td>Mobilisasi infra Combat ke Lokasi Combat ' . $siteRow['site_name_plan_combat'] . '.</td></tr>
                <tr><td>E.</td><td></td><td>Memproses perijinan penempatan Combat dan melakukan site acquisition.</td></tr>
                <tr><td>F.</td><td></td><td>Masa sewa Lahan selama 6 bulan.</td></tr>
                <tr><td>G.</td><td></td><td>Kebutuhan transport, menggunakan Radio IP.</td></tr>
                <tr><td>H.</td><td></td><td>Melakukan instalasi/deinstalasi perangkat Radio IP.</td></tr>
                <tr><td>I.</td><td></td><td>Pengajuan Pemasangan Sambungan Baru PLN 5500 VA.</td></tr>
            </table>';

            $html .= '<h1>Summary</h1>';
            $html .= '<p>Berdasarkan Analisa Business dan Teknis diatas, maka ;</p>';
            $html .= '
            <table>
                <tr><td>B.</td><td></td><td>Potensi penambahan payload dan revenue baru dari new Combat Arrow sebesar 400 GB dan Rp 1.500.000,- per hari.</td></tr>
                <tr><td>A.</td><td></td><td>Dismantle, Mobilisasi dan Instalasi Combat Arrow ini menggunakan budget Opex Q2 2024 RNOP Jateng DIY.</td></tr>
            </table>';

            // Write the content for this site
            $mpdf->WriteHTML($html);
            $mpdf->AddPage();
        }
    }

    // Output the final PDF
    $mpdf->Output('');
}

        
        // Export to Excel
        if (isset($_POST['export_excel'])) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'Program');
        $sheet->setCellValue('B1', 'Departemen');
        $sheet->setCellValue('C1', 'Quartal');
        $sheet->setCellValue('D1', 'Site ID');
        $sheet->setCellValue('E1', 'Deskripsi Gambar');
        $sheet->setCellValue('F1', 'Gambar');

        $query = "SELECT * FROM program WHERE program_id = ?";
        $stmt = $dbconn->prepare($query);
        $stmt->bind_param('s', $prog);
        $stmt->execute();
        $result = $stmt->get_result();

        $rowNum = 2;
        while ($row = $result->fetch_assoc()) {
            $sheet->setCellValue('A' . $rowNum, $row['program']);
            $sheet->setCellValue('B' . $rowNum, $row['departemen']);
            $sheet->setCellValue('C' . $rowNum, $row['quartal']);
            $sheet->setCellValue('D' . $rowNum, $row['siteid']);
            $sheet->setCellValue('E' . $rowNum, $row['deskripsi_gambar']);
            $sheet->setCellValue('F' . $rowNum, $row['gambar']);
            $rowNum++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save('Program_' . $prog . '.xlsx');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Program_' . $prog . '.xlsx"');
        $writer->save("php://output");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Report</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Generate Report</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="program_id">Masukkan Program ID</label>
                <input type="text" class="form-control" name="program_id" id="program_id" required>
            </div>
            <button type="submit" name="generate_pdf" class="btn btn-primary mt-3">Generate PDF</button>
            <button type="submit" name="export_excel" class="btn btn-success mt-3">Export to Excel</button>
        </form>
    </div>
</body>
</html>