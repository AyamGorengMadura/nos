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
        $mpdf = new Mpdf();
        // $html = '<h1>Program Report</h1>';
        $query = "SELECT * FROM program WHERE program_title = ?";
        $queary = "SELECT 
            ds.SiteID, ds.SiteName, ds.Latitude, ds.Longitude, 
            ds.SiteNameDonor, st.site_name_plan_combat, st.departement_nop, 
            st.address, st.desa, st.kecamatan, st.kabupaten, 
            st.longitude AS st_longitude, st.latitude AS st_latitude, 
            st.prediksi_revenue_sifa, st.plan_infra_combat, 
            st.site_id_combat_donor, st.site_name_combat_donor, 
            st.infra_combat_donor, st.combat_donor_status, 
            st.justifikasi_combat_donor, st.infra_combat_donor_location, 
            st.jarak_infra_donor_ke_lokasi_baru, st.plan_transmisi, 
            st.donor_radio_ip, st.plan_far_end, st.daya_pln, 
            st.donor_license, st.sitac, st.issue, st.remark_sqa, 
            st.remark_rtpe, st.pic_nop, rb.jasa_pengurusan_izin_penempatan, 
            rb.jasa_deinst_combat_arrow_dan_perangkat, 
            rb.jasa_deinst_cmon_dan_perangkat, 
            rb.jasa_mobilisasi_per_50_km_combat_arrow, 
            rb.jasa_mobilisasi_per_50_km_cmon, 
            rb.pengadaan_custom_pole_cmon_1_sector, 
            rb.pengadaan_custom_pole_cmon_3_sector, 
            rb.jasa_inst_combat_arrow_dan_perangkat, 
            rb.jasa_instalasi_cmon_dan_perangkat, 
            rb.jasa_deinst_transport_radio_ipmw_less_1_2m, 
            rb.jasa_inst_transport_radio_ipmw_less_1_2m, 
            rb.jasa_pengurusan_penyambungan_listrik, 
            rb.listrik_multiguna, rb.sewa_lahan 
        FROM 
            datasite ds 
        LEFT JOIN 
            sitetb st ON ds.SiteID = st.SiteID 
        LEFT JOIN 
            rabtb rb ON ds.SiteID = rb.SiteID 
        WHERE 
            ds.SiteID = ?";

        $stmt = $dbconn->prepare($query);
        $stmt->bind_param('s', $prog);
        $stmt->execute();
        $result = $stmt->get_result();    
        
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
        // $coverHtml = '<h1 style="text-align: center; font-size: 50px;">Laporan</h1>';
        $coverHtml .= '<h3 style="text-align: center; font-size: 30px;">' . $prog . '</h3>';
        // $coverHtml .= '<p style="text-align: center; font-size: 30px;">' . date('Y-m-d') . '</p>';
        while ($row = $result->fetch_assoc()) {
            $coverHtml .= '<p style="text-align: center; font-size: 30px;">' . $row['siteid'] . '</p>';


        }
        $mpdf->WriteHTML($coverHtml);

        // Reset result set pointer
        $result->data_seek(0);
        
        while ($row = $result->fetch_assoc()) {
            
            $mpdf->AddPage();
            $siteQuery = "SELECT * FROM sitetb WHERE siteId = ?";
            $stmtSite = $dbconn->prepare($siteQuery);
            $stmtSite->bind_param('s', $row['siteid']);
            $stmtSite->execute();
            $siteResult = $stmtSite->get_result();
            
                    while ($siteRow = $siteResult->fetch_assoc()) {

                $html .= '<p style="font-size: 30px; text-align: center;">Technical Assessment Combat ' . $siteRow['siteId'] . '</p>';  
                $html .= '<h1> Latar Belakang </h1>';
                $html .= '<p>Latar belakang dilaksanakannya pekerjaan ini adalah sebagai berikut;</p>';     
                $html .= '
                <table>
                <tr><td>A.</td><td></td><td>Adanya komplain yang disebabkan oleh Utilisasi trafik yang tinggi di area ' . $siteRow['desa'] . ' Kecamatan '. $siteRow['kecamatan'] .' Kabupaten/Kota Boyolali khususnya pada saat busy hour.</td></tr>
                <tr><td>B.</td><td></td><td>Informasi dari Dinas Kominfo kab Boyolali bahwa di Desa ' . $siteRow['desa'] . ' Kecamatan '.$siteRow['kecamatan'] .' adalah desa blank spot seluler semua operator.</td></tr>
                <tr><td>C.</td><td></td><td>Adanya surat permintaan layanan sinyal Telkomsel oleh pemernintah Desa ' . $siteRow['desa'] . ' Kecamatan ' . $siteRow['kecamatan'] . ' Kabupaten ' . $kabupaten . ' mengetehui Dinas Kominfo Kabupaten ' . $siteRow['kabupaten'] . '.</td></tr>
                <tr><td>D.</td><td></td><td>Bad Coverage di Desa ' . $siteRow['desa'] . ' kecamatan ' . $siteRow['kecamatan'] . ' dimana mayoritas penduduknya merupakan customer Telkomsel.</td></tr>
                <tr><td>E.</td><td></td><td>Customer Complain mengenai kualitas sinyal Telkomsel yang tidak bagus di Desa ' . $siteRow['desa'] . ' Kecamatan ' . $siteRow['kecamatan'] . ' Kabupaten ' . $siteRow['kabupaten'] . '.</td></tr>
                </table>';
                   
                $html .= '<h1>Analisa Bisnis dan Teknis</h1>'; 
                $html .= '
                <table>
                <tr><td>A.</td><td></td><td>Potensi Site Kandidat Lokasi ' . $siteRow['siteId'] . '  ' . $siteRow['site_name_plan_combat'] . ' berada di kecamatan ' . $siteRow['kecamatan'] . ', Kabupaten ' . $siteRow['kabupaten'] . ', Jawa Tengah, Indonesia. Lokasi koordinatnya di Longitude ' . $siteRow['longitude'] . ', Latitude ' . $siteRow['latitude'] . '.</td></tr>
                <tr><td>B.</td><td></td><td>Coverage seluler di Desa ' . $siteRow['desa'] . ' blank sinyal  operator manapun termasuk Telkomsel.</td></tr>
                <tr><td>C.</td><td></td><td>Kebutuhan Telkomsel  di daerah tersebut cukup tinggi, karena meliputi pemukiman padat penduduk dengan jumlah 5625 penduduk ####.</td></tr>
                <tr><td>D.</td><td></td><td>Merupakan area blue ocean dimana tidak ada satupun operator celluler yang menjangkau Desa ' . $siteRow['desa'] . '.</td></tr>
                <tr><td>E.</td><td></td><td>Untuk mengamankan target Payload, Traffic, dan Revenue Telkomsel Tahun 2024, dan menambah coverage share dan pelanggan baru di Kab ' . $siteRow['kabupaten'] . '.</td></tr>
                </table>';

                $images = explode(',', $row['gambar']);
                    foreach ($images as $image) {
                    $html .= '<img src="' . $image . '" style="width: 150px; height: auto; margin: 5px;">';
                }

                $html .= '<p>Deskripsi: ' . $row['deskripsi_gambar'] . '</p>';

                   
                $html .= '<h1>Proyeksi Revenue</h1>'; 
                $html .= '
                <table>
                <tr><td></td><td></td><td>Proyeksi Revenue by SIFA = Rp. ' . $siteRow['prediksi_revenue_sifa'] . ' dengan perhitungan jumlah populasi 8500 jiwa, sedangakan proyeksi revenue by Branch Surakarta dengan ARPU Rp. 60.000, Rev. BB=Rp. 51.000.000, Rev. Digital=Rp. 10.000.000 dan Rev. Voice=Rp. 5.000.000, total proyeksi revenue Combat = Rp. 52.500.000.</td></tr>
                </table>';

                $html .= '<h1>Ruang Lingkup Pekerjaan</h1>'; 
                $html .= '<p>Berikut ini adalah deskripsi ruang lingkup pekerjaan dan lokasi rencana Mobilisasi dan Instalasi Combat ' . $siteRow['site_name_plan_combat'] . ' :</p>'; 
                $html .= '
                <table>
                <tr><td>A.</td><td></td><td>Donor Combat ' . $siteRow['site_name_plan_combat'] . ' dari Combat ' . $siteRow['site_name_combat_donor'] . '.</td></tr>
                <tr><td>B.</td><td></td><td>Lokasi Combat ' . $siteRow['site_name_plan_combat'] . ' ' . $siteRow['siteId'] . ' di desa ' . $siteRow['desa'] . ' Kecamatan ' . $siteRow['kecamatan'] . '  Kabupaten ' . $siteRow['kabupaten'] . '  Latitude ' . $siteRow['latitude'] . ', Longitude ' . $siteRow['latitude'] . '.</td></tr>
                <tr><td>C.</td><td></td><td>Dismantle infra dan perangkat Combat ' . $siteRow['site_name_plan_combat'] . '..</td></tr>
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
                
                // Tambahkan data lainnya sesuai kebutuhan dari tabel sitetb
            }    
            
            $mpdf->WriteHTML($html);
        }

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
