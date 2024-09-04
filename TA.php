<?php
ini_set('pcre.backtrack_limit', '10000000');
require_once __DIR__ . '/vendor/autoload.php';
require 'koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$siteData = [];
$siteIDs = [];

// Fetch all Site IDs from the database
$query = "SELECT SiteID FROM datasite";
$result = $dbconn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $siteIDs[] = $row['SiteID'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["site_id"]) && isset($_POST["activity"])) {
    $siteIDsSelected = $_POST["site_id"];
    $activity = $_POST["activity"];

    foreach ($siteIDsSelected as $siteID) {
        // Query to fetch site data from datasite based on Site ID
        $query = "
        SELECT  ds.SiteID, ds.SiteName, ds.Latitude, ds.Longitude, ds.SiteNameDonor, st.site_name_plan_combat, st.departement_nop, st.address, st.desa, st.kecamatan, st.kabupaten, st.longitude AS st_longitude, st.latitude AS st_latitude, st.prediksi_revenue_sifa, st.plan_infra_combat, st.site_id_combat_donor, st.site_name_combat_donor, st.infra_combat_donor, st.combat_donor_status, st.justifikasi_combat_donor, st.infra_combat_donor_location, st.jarak_infra_donor_ke_lokasi_baru, st.plan_transmisi, st.donor_radio_ip, st.plan_far_end, st.daya_pln, st.donor_license, st.sitac, st.issue, st.remark_sqa, st.remark_rtpe, st.pic_nop, rb.jasa_pengurusan_izin_penempatan, rb.jasa_deinst_combat_arrow_dan_perangkat, rb.jasa_deinst_cmon_dan_perangkat, rb.jasa_mobilisasi_per_50_km_combat_arrow, rb.jasa_mobilisasi_per_50_km_cmon, rb.pengadaan_custom_pole_cmon_1_sector, rb.pengadaan_custom_pole_cmon_3_sector, rb.jasa_inst_combat_arrow_dan_perangkat, rb.jasa_instalasi_cmon_dan_perangkat, rb.jasa_deinst_transport_radio_ipmw_less_1_2m, rb.jasa_inst_transport_radio_ipmw_less_1_2m, rb.jasa_pengurusan_penyambungan_listrik, rb.listrik_multiguna, rb.sewa_lahan FROM datasite ds LEFT JOIN sitetb st ON ds.SiteID = st.SiteID LEFT JOIN rabtb rb ON ds.SiteID = rb.SiteID WHERE ds.SiteID = ?";
        $stmt = $dbconn->prepare($query);
        $stmt->bind_param("s", $siteID);  // Hanya satu parameter
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $siteData[] = $result->fetch_assoc();
        } else {
            $siteData[] = ["SiteID" => $siteID, "Error" => "Site ID not found."];
        }
    }


    // Proses untuk export Excel atau PDF di sini...

        if (isset($_POST['export_excel'])) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header untuk kolom Excel, disesuaikan dengan kolom database
        $sheet->setCellValue('A1', 'Item');
        $sheet->setCellValue('A2', 'Site ID');
        $sheet->setCellValue('A3', 'Latitude');
        $sheet->setCellValue('A4', 'Longitude');
        $sheet->setCellValue('A5', 'Site Name Donor');
        $sheet->setCellValue('A6', 'Site Name Plan Combat');
        $sheet->setCellValue('A7', 'Departement NOP');
        $sheet->setCellValue('A8', 'Address');
        $sheet->setCellValue('A9', 'desa');
        $sheet->setCellValue('A10', 'kecamatan');
        $sheet->setCellValue('A11', 'kabupaten');
        $sheet->setCellValue('A12', 'Prediksi Revenue (SIFA)');
        $sheet->setCellValue('A13', 'Plan Infra Combat');
        $sheet->setCellValue('A14', 'Site ID Combat Donor');
        $sheet->setCellValue('A15', 'Site Name Combat Donor');
        $sheet->setCellValue('A16', 'Infra Combat Donor');
        $sheet->setCellValue('A17', 'Combat Donor Status');
        $sheet->setCellValue('A18', 'Justifikasi Combat Donor');
        $sheet->setCellValue('A19', 'Infra Combat Donor Location');
        $sheet->setCellValue('A20', 'Jarak Infra Donor ke Lokasi Baru');
        $sheet->setCellValue('A21', 'Plan Transmisi');
        $sheet->setCellValue('A22', 'Donor Radio IP');
        $sheet->setCellValue('A23', 'Plan Far End');
        $sheet->setCellValue('A24', 'Daya PLN');
        $sheet->setCellValue('A25', 'Donor License');
        $sheet->setCellValue('A26', 'SITAC');
        $sheet->setCellValue('A27', 'Issue');
        $sheet->setCellValue('A28', 'Remark SQA');
        $sheet->setCellValue('A29', 'Remark RTPE');
        $sheet->setCellValue('A30', 'PIC NOP');
        $sheet->setCellValue('A31', 'Jasa Pengurusan Izin Penempatan');
        $sheet->setCellValue('A32', 'Jasa DeInst Combat Arrow dan Perangkat');
        $sheet->setCellValue('A33', 'Jasa DeInst CMON dan Perangkat');
        $sheet->setCellValue('A34', 'Jasa Mobilisasi per 50 km Combat Arrow');
        $sheet->setCellValue('A35', 'Jasa Mobilisasi per 50 km CMON');
        $sheet->setCellValue('A36', 'Pengadaan Custom Pole CMON 1 sector');
        $sheet->setCellValue('A37', 'Pengadaan Custom Pole CMON (3 Sector)');
        $sheet->setCellValue('A38', 'Jasa Inst Combat Arrow dan Perangkat');
        $sheet->setCellValue('A39', 'Jasa Instalasi CMON dan Perangkat');
        $sheet->setCellValue('A40', 'Jasa DeInst Transport Radio IPMW<1.2m');
        $sheet->setCellValue('A41', 'Jasa Inst Transport Radio IPMW<1.2m');
        $sheet->setCellValue('A42', 'Js Pengurusan Penyambungan Listrik');
        $sheet->setCellValue('A43', 'Listrik Multiguna (10.6 kVA atau lebih)');
        $sheet->setCellValue('A44', 'Sewa Lahan');

        $col = 'B';
        foreach ($siteData as $index => $data) {
            // $sheet->setCellValue($col . '1', $index + 1);
            $sheet->setCellValue($col . '2', $siteIDsSelected[$index]);

            if (isset($data['Error'])) {
                $sheet->setCellValue($col . '50', $data['Error']);
            } else {
                $sheet->setCellValue($col . '1', $data['SiteName']);
                $sheet->setCellValue($col . '3', $data['Latitude']);
                $sheet->setCellValue($col . '4', $data['Longitude']);
                $sheet->setCellValue($col . '5', $data['SiteNameDonor']);
                $sheet->setCellValue($col . '6', isset($data['site_name_plan_combat']) ? $data['site_name_plan_combat'] : '');
                $sheet->setCellValue($col . '7', isset($data['departement_nop']) ? $data['departement_nop'] : '');
                $sheet->setCellValue($col . '8', isset($data['address']) ? $data['address'] : '');
                $sheet->setCellValue($col . '9', isset($data['desa']) ? $data['desa'] : '');
                $sheet->setCellValue($col . '10', isset($data['kecamatan']) ? $data['kecamatan'] : '');
                $sheet->setCellValue($col . '11', isset($data['kabupaten']) ? $data['kabupaten'] : '');
                $sheet->setCellValue($col . '12', isset($data['prediksi_revenue_sifa']) ? $data['prediksi_revenue_sifa'] : '');
                $sheet->setCellValue($col . '13', isset($data['plan_infra_combat']) ? $data['plan_infra_combat'] : '');
                $sheet->setCellValue($col . '14', isset($data['site_id_combat_donor']) ? $data['site_id_combat_donor'] : '');
                $sheet->setCellValue($col . '15', isset($data['site_name_combat_donor']) ? $data['site_name_combat_donor'] : '');
                $sheet->setCellValue($col . '16', isset($data['infra_combat_donor']) ? $data['infra_combat_donor'] : '');
                $sheet->setCellValue($col . '17', isset($data['combat_donor_status']) ? $data['combat_donor_status'] : '');
                $sheet->setCellValue($col . '18', isset($data['justifikasi_combat_donor']) ? $data['justifikasi_combat_donor'] : '');
                $sheet->setCellValue($col . '19', isset($data['infra_combat_donor_location']) ? $data['infra_combat_donor_location'] : '');
                $sheet->setCellValue($col . '20', isset($data['jarak_infra_donor_ke_lokasi_baru']) ? $data['jarak_infra_donor_ke_lokasi_baru'] : '');
                $sheet->setCellValue($col . '21', isset($data['plan_transmisi']) ? $data['plan_transmisi'] : '');
                $sheet->setCellValue($col . '22', isset($data['donor_radio_ip']) ? $data['donor_radio_ip'] : '');
                $sheet->setCellValue($col . '23', isset($data['plan_far_end']) ? $data['plan_far_end'] : '');
                $sheet->setCellValue($col . '24', isset($data['daya_pln']) ? $data['daya_pln'] : '');
                $sheet->setCellValue($col . '25', isset($data['donor_license']) ? $data['donor_license'] : '');
                $sheet->setCellValue($col . '26', isset($data['sitac']) ? $data['sitac'] : '');
                $sheet->setCellValue($col . '27', isset($data['issue']) ? $data['issue'] : '');
                $sheet->setCellValue($col . '28', isset($data['remark_sqa']) ? $data['remark_sqa'] : '');
                $sheet->setCellValue($col . '29', isset($data['remark_rtpe']) ? $data['remark_rtpe'] : '');
                $sheet->setCellValue($col . '30', isset($data['pic_nop']) ? $data['pic_nop'] : '');
                $sheet->setCellValue($col . '31', isset($data['jasa_pengurusan_izin_penempatan']) ? $data['jasa_pengurusan_izin_penempatan'] : '');
                $sheet->setCellValue($col . '32', isset($data['jasa_deinst_combat_arrow_dan_perangkat']) ? $data['jasa_deinst_combat_arrow_dan_perangkat'] : '');
                $sheet->setCellValue($col . '33', isset($data['jasa_deinst_cmon_dan_perangkat']) ? $data['jasa_deinst_cmon_dan_perangkat'] : '');
                $sheet->setCellValue($col . '34', isset($data['jasa_mobilisasi_per_50_km_combat_arrow']) ? $data['jasa_mobilisasi_per_50_km_combat_arrow'] : '');
                $sheet->setCellValue($col . '35', isset($data['jasa_mobilisasi_per_50_km_cmon']) ? $data['jasa_mobilisasi_per_50_km_cmon'] : '');
                $sheet->setCellValue($col . '36', isset($data['pengadaan_custom_pole_cmon_1_sector']) ? $data['pengadaan_custom_pole_cmon_1_sector'] : '');
                $sheet->setCellValue($col . '37', isset($data['pengadaan_custom_pole_cmon_3_sector']) ? $data['pengadaan_custom_pole_cmon_3_sector'] : '');
                $sheet->setCellValue($col . '38', isset($data['jasa_inst_combat_arrow_dan_perangkat']) ? $data['jasa_inst_combat_arrow_dan_perangkat'] : '');
                $sheet->setCellValue($col . '39', isset($data['jasa_instalasi_cmon_dan_perangkat']) ? $data['jasa_instalasi_cmon_dan_perangkat'] : '');
                $sheet->setCellValue($col . '40', isset($data['jasa_deinst_transport_radio_ipmw']) ? $data['jasa_deinst_transport_radio_ipmw'] : '');
                $sheet->setCellValue($col . '41', isset($data['jasa_inst_transport_radio_ipmw']) ? $data['jasa_inst_transport_radio_ipmw'] : '');
                $sheet->setCellValue($col . '42', isset($data['js_pengurusan_penyambungan_listrik']) ? $data['js_pengurusan_penyambungan_listrik'] : '');
                $sheet->setCellValue($col . '43', isset($data['listrik_multiguna']) ? $data['listrik_multiguna'] : '');
                $sheet->setCellValue($col . '44', isset($data['sewa_lahan']) ? $data['sewa_lahan'] : '');
            }
            $col++;
        }

        // Set proper headers for output
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="site_data.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }



    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML("<h1 style='text-align: center; font-size: 40px;'>$activity</h1>");

// Display selected Site IDs
foreach ($siteIDsSelected as $siteID) {
    $mpdf->WriteHTML("<h2 style='text-align: center; margin-top: 35px; font-size: 30px;'>Site ID: $siteID</h2>");
}

$mpdf->AddPage(); // Add a new page

foreach ($siteData as $data) {
    if (isset($data['Error'])) {
        $mpdf->WriteHTML("<p style='fon     t-size: 18px;'><strong>Site ID:</strong> " . htmlspecialchars($data['SiteID']) . " - " . htmlspecialchars($data['Error']) . "</p>");
    } else {
        // Adding content for each site
        $mpdf->WriteHTML("<h1> Latar Belakang </h1>");
        $mpdf->WriteHTML("<p> Latar belakang dilaksanakannya pekerjaan ini adalah sebagai berikut ; </p>");
        $mpdf->writeHTML("
            <table>
            <tr><td>A.</td><td></td><td>Adanya komplain yang disebabkan oleh Utilisasi trafik yang tinggi di area " . htmlspecialchars($data['desa']) . " Kecamatan " . htmlspecialchars($data['kecamatan']) . " Kabupaten " . htmlspecialchars($data['kabupaten']) . " khususnya pada saat busy hour.</td></tr>
            <tr><td>B.</td><td></td><td>Informasi dari Dinas Kominfo kab " . htmlspecialchars($data['kabupaten']) . " bahwa di Desa " . htmlspecialchars($data['desa']) . " Kecamatan " . htmlspecialchars($data['kecamatan']) . " adalah desa blank spot seluler semua operator</td></tr>
            <tr><td>C.</td><td></td><td>Adanya surat permintaan layanan sinyal Telkomsel oleh pemernintah Desa " . htmlspecialchars($data['desa']) . " Kecamatan " . htmlspecialchars($data['kecamatan']) . " Kabupaten " . htmlspecialchars($data['kabupaten']) . " mengetehui Dinas Kominfo Kabupaten " . htmlspecialchars($data['kabupaten']) . ".</td></tr>
            <tr><td>D.</td><td></td><td>Bad Coverage di Desa " . htmlspecialchars($data['desa']) . " kecamatan " . htmlspecialchars($data['kecamatan']) . " dimana mayoritas penduduknya merupakan customer Telkomsel</td></tr>
            <tr><td>E.</td><td></td><td>Customer Complain mengenai kualitas sinyal Telkomsel yang tidak bagus di Desa " . htmlspecialchars($data['desa']) . " Kecamatan " . htmlspecialchars($data['kecamatan']) . " Kabupaten " . htmlspecialchars($data['kabupaten']) . ".</td></tr>
            </table>
        ");

        $mpdf->WriteHTML("<h1> Analisa Bisnis dan Teknis </h1>");
        $mpdf->writeHTML("
            <table>
            <tr><td>A.</td><td></td><td>Kandidat Lokasi " . htmlspecialchars($data['SiteID'])  . " " .  htmlspecialchars($data['SiteName']) . " berada di kecamatan " . htmlspecialchars($data['kecamatan']) . ", Kabupaten " . htmlspecialchars($data['kabupaten']) . ", Jawa Tengah, Indonesia. Lokasi koordinatnya di Longitude " . htmlspecialchars($data['Longitude']) . ", Latitude " . htmlspecialchars($data['Latitude']) . ".</td></tr>
            <tr><td>B.</td><td></td><td>Coverage seluler di Desa " . htmlspecialchars($data['desa']) . " blank sinyal  operator manapun termasuk Telkomsel.</td></tr>
            <tr><td>C.</td><td></td><td>Kebutuhan Telkomsel  di daerah tersebut cukup tinggi, karena meliputi pemukiman padat penduduk dengan jumlah 5625 penduduk.</td></tr>
            <tr><td>D.</td><td></td><td>Merupakan area blue ocean dimana tidak ada satupun operator celluler yang menjangkau " . htmlspecialchars($data['desa']) . ".</td></tr>
            <tr><td>E.</td><td></td><td>Untuk mengamankan target Payload, Traffic, dan Revenue Telkomsel Tahun 2024, dan menambah coverage share dan pelanggan baru di Kab Pemalang</td></tr>
            </table>
        ");
        
        // Business and technical images specific to this site
        foreach ($_FILES['business_technical_images']['tmp_name'] as $index => $tmpName) {
            if (is_uploaded_file($tmpName)) {
                // Get the description and add it above the image
                $description = htmlspecialchars($_POST['gambar_deskripsi'][$index]);

                // Read the image file and encode it
                $imageData = file_get_contents($tmpName);
                $base64Image = base64_encode($imageData);

                // Add the image to the PDF
                $mpdf->WriteHTML('<img src="data:image/jpeg;base64,' . $base64Image . '" width="300px" height="auto" />');

                // Add the description to the PDF
                $mpdf->WriteHTML("<h3>$description</h3>");

            }
        }
        
        

        $mpdf->WriteHTML("<h1> Proyeksi Revenue </h1>");
        $mpdf->WriteHTML("<p>Proyeksi Revenue by SIFA = ". htmlspecialchars($data['prediksi_revenue_sifa']) ." dengan perhitungan jumlah populasi 8500 jiwa, sedangakan proyeksi revenue by Branch Surakarta dengan ARPU Rp. 60.000, Rev. BB=Rp. 51.000.000, Rev. Digital=Rp. 10.000.000 dan Rev. Voice=Rp. 5.000.000, total proyeksi revenue Combat = Rp. 52.500.000 </p>");

        // Continue with the rest of the content

        $mpdf->WriteHTML("<h1>Ruang Lingkup Pekerjaan</h1>");
        $mpdf->writeHTML("
            <table>
                <tr>
                <td>A.</td><td></td><td>Donor " . htmlspecialchars($data['SiteName']) . " " . htmlspecialchars($data['SiteID']) . " dari Combat " . htmlspecialchars($data['SiteID']) . " , " . htmlspecialchars($data['SiteName']) . ".</td>
                </tr>
                <tr>
                <td>B.</td><td></td><td>Lokasi " . htmlspecialchars($data['SiteName']) . " " . htmlspecialchars($data['SiteID']) . " di desa " . htmlspecialchars($data['desa']) . " Kecamatan " . htmlspecialchars($data['kecamatan']) . " Kabupaten " . htmlspecialchars($data['kabupaten']) . " Latitude " . htmlspecialchars($data['Latitude']) . " , Longitude ". htmlspecialchars($data['Longitude']) . ".</td>
                </tr>
                <tr>
                <td>C.</td><td></td><td>Dismantle infra dan perangkat " . htmlspecialchars($data['SiteName']) . ".</td>
                </tr>
                <tr>
                <td>D.</td><td></td><td>Mobilisasi infra Combat ke Lokasi " . htmlspecialchars($data['SiteName']) . ".</td>
                </tr>
                <tr>
                <td>E.</td><td></td><td>Memproses perijinan penempatan Combat dan melakukan site acquisition.</td>
                </tr>
                <tr>
                <td>F.</td><td></td><td>Masa sewa Lahan selama 6 bulan.</td>
                </tr>
                <tr>
                <td>G.</td><td></td><td>Kebutuhan transport, menggunakan Radio IP.</td>
                </tr>
                <tr>
                <td>H.</td><td></td><td>Melakukan instalasi/deinstalasi perangkat Radio IP.</td>
                </tr>
                <tr>
                <td>I.</td><td></td><td>Pengajuan Pemasangan Sambungan Baru PLN 5500 VA.</td>
                </tr>
            </table>
        ");

        $mpdf->WriteHTML("<h1> Summary </h1>");
        $mpdf->writeHTML("
            <table>
            <tr>
            <td>A.</td><td></td><td>Potensi penambahan payload dan revenue baru dari new Combat Arrow sebesar 400 GB dan Rp 1.500.000,- per hari.</td>
            </tr>
            <tr>
            <td>B.</td><td></td><td>Dismantle, Mobilisasi dan Instalasi Combat Arrow ini menggunakan budget Opex #Q2 2024 RNOP Jateng DIY.</td>
            </tr>
            </table>
        ");

        $mpdf->WriteHTML("<h1> Lampiran </h1>");
    }
}

$mpdf->Output();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TA</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        .card-transparent {
            background-color: rgba(128, 128, 128, 0.7); /* Warna abu-abu dengan transparansi */
            border: none;
        }
    </style>
    <script>

        $(document).ready(function () {
            $('#addSiteID').click(function () {
                var newSiteSection = $('.site-section:first').clone(); // Clone the first .site-section
                newSiteSection.find('select').val(''); // Clear the select value
                newSiteSection.find('input[type="file"][name="business_technical_images[]"][accept="image/jpeg, image/png, image/gif"]').val(''); // Clear the file input
                newSiteSection.find('input[type="text"][name="gambar_deskripsi[]"]').val(''); // Clear the description input
                $('#site-wrapper').append(newSiteSection); // Append the cloned section to the wrapper
            });

            $(document).on('click', '.removeSiteID', function () {
                if ($('.site-section').length > 1) { // Only allow removing if there's more than one section
                    $(this).closest('.site-section').remove();
                }
            });
        });

    </script>
</head>

<body>
    <div class="card card-transparent col-10 mx-auto mt-4 mb-4 text-start">
        <center><h1>Technical Assessment</h1></center>
        <form action="" method="post" enctype="multipart/form-data">
            <div id="site-wrapper">
                <div class="site-section mb-3">
                    <label for="siteID" class="form-label">Masukkan Site ID</label>
                    <select class="form-control" name="site_id[]">
                        <?php foreach ($siteIDs as $id): ?>
                            <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($id) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label for="business_technical_images" class="form-label mt-2">Upload Images</label>
                    <input type="file" class="form-control-file" name="business_technical_images[]" accept="image/jpeg, image/png, image/gif" multiple>

                    <label for="gambar_deskripsi" class="form-label mt-2">Deskripsi Gambar</label>
                    <input type="text" class="form-control" name="gambar_deskripsi[]" placeholder="Deskripsi Gambar">

                    <button type="button" class="btn btn-danger removeSiteID mt-2">Remove</button>
                </div>
            </div>

            <div class="mb-3 mt-3">
                <label for="activity" class="form-label">Pilih Aktivitas</label>
                <select class="form-control" name="activity" id="activity">
                    <option value="Relokasi Combat">Relokasi Combat</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <button type="submit" name="export_excel" class="btn btn-success mt-4">Export to Excel</button>
            <button type="button" id="addSiteID" class="btn btn-primary mt-4">Tambah Site</button>
            <button type="submit" name="generate_pdf" class="btn btn-primary mt-4">Generate PDF</button>
        </form>
    </div>
</body>

</html>