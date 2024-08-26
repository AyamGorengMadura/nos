<?php
include("koneksi.php");

// Database connection parameters
$host = 'localhost';
$dbname = 'your_database_name';
$user = 'your_username';
$pass = 'your_password';

// Check connection
if ($dbconn->connect_error) {
    die("Connection failed: " . $dbconn->connect_error);
}

// SQL to create table sitetb
$sql_sitetb = "CREATE TABLE IF NOT EXISTS sitetb (
    site_id_plan_combat VARCHAR(255),
    site_name_plan_combat VARCHAR(255),
    departement_nop VARCHAR(255),
    address TEXT,
    longitude DECIMAL(10, 7),
    latitude DECIMAL(10, 7),
    prediksi_revenue_sifa DECIMAL(15, 2),
    plan_infra_combat VARCHAR(255),
    site_id_combat_donor VARCHAR(255),
    site_name_combat_donor VARCHAR(255),
    infra_combat_donor VARCHAR(255),
    combat_donor_status VARCHAR(255),
    justifikasi_combat_donor TEXT,
    infra_combat_donor_location TEXT,
    jarak_infra_donor_ke_lokasi_baru DECIMAL(10, 2),
    plan_transmisi VARCHAR(255),
    donor_radio_ip VARCHAR(255),
    plan_far_end VARCHAR(255),
    daya_pln DECIMAL(10, 2),
    donor_license VARCHAR(255),
    sitac VARCHAR(255),
    issue TEXT,
    remark_sqa TEXT,
    remark_rtpe TEXT,
    pic_nop VARCHAR(255)
)";

// SQL to create table rabtb
$sql_rabtb = "CREATE TABLE IF NOT EXISTS rabtb (
    jasa_pengurusan_izin_penempatan VARCHAR(255),
    jasa_deinst_combat_arrow_dan_perangkat VARCHAR(255),
    jasa_deinst_cmon_dan_perangkat VARCHAR(255),
    jasa_mobilisasi_per_50_km_combat_arrow DECIMAL(10, 2),
    jasa_mobilisasi_per_50_km_cmon DECIMAL(10, 2),
    pengadaan_custom_pole_cmon_1_sector VARCHAR(255),
    pengadaan_custom_pole_cmon_3_sector VARCHAR(255),
    jasa_inst_combat_arrow_dan_perangkat VARCHAR(255),
    jasa_instalasi_cmon_dan_perangkat VARCHAR(255),
    jasa_deinst_transport_radio_ipmw_less_1_2m VARCHAR(255),
    jasa_inst_transport_radio_ipmw_less_1_2m VARCHAR(255),
    jasa_pengurusan_penyambungan_listrik VARCHAR(255),
    listrik_multiguna DECIMAL(10, 2),
    sewa_lahan VARCHAR(255)
)";

// Close connection
$dbconn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Display</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="card bg-primary col-8 mx-auto rounded mt-3 mb-3 pb-4">


        <div class="container mt-5">
            <h2 class="mb-4">Site Data</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-light">
                    <thead>
                        <tr>
                            <th>Site ID Plan Combat</th>
                            <th>Site Name Plan Combat</th>
                            <th>Departement NOP</th>
                            <th>Address</th>
                            <th>Longitude</th>
                            <th>Latitude</th>
                            <th>Prediksi Revenue (SIFA)</th>
                            <th>Plan Infra Combat</th>
                            <th>Site ID Combat Donor</th>
                            <th>Site Name Combat Donor</th>
                            <th>Infra Combat Donor</th>
                            <th>Combat Donor Status</th>
                            <th>Justifikasi Combat Donor</th>
                            <th>Infra Combat Donor Location</th>
                            <th>Jarak Infra Donor ke Lokasi Baru</th>
                            <th>Plan Transmisi</th>
                            <th>Donor Radio IP</th>
                            <th>Plan Far End</th>
                            <th>Daya PLN</th>
                            <th>Donor License</th>
                            <th>SITAC</th>
                            <th>Issue</th>
                            <th>Remark SQA</th>
                            <th>Remark RTPE</th>
                            <th>PIC NOP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'koneksi.php';
                        $result = $dbconn->query("SELECT * FROM sitetb");
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            foreach ($row as $value) {
                                echo "<td>" . htmlspecialchars($value) . "</td>";
                            }
                            echo "</tr>";
                        }
                        $dbconn->close();
                        ?>
                    </tbody>
                </table>
            </div>

            <h2 class="mb-4 mt-3">RAB Data</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-light">
                    <thead>
                        <tr>
                            <th>Jasa Pengurusan Izin Penempatan</th>
                            <th>Jasa DeInst. Combat Arrow dan Perangkat</th>
                            <th>Jasa DeInst. CMON dan Perangkat</th>
                            <th>Jasa Mobilisasi per 50 km Combat Arrow</th>
                            <th>Jasa Mobilisasi per 50 km CMON</th>
                            <th>Pengadaan Custom Pole CMON 1 sector</th>
                            <th>Pengadaan Custom Pole CMON (3 Sector)</th>
                            <th>Jasa Inst. Combat Arrow dan Perangkat</th>
                            <th>Jasa Instalasi CMON dan Perangkat</th>
                            <th>Jasa DeInst. Transport Radio IPMW<1.2m</th>
                            <th>Jasa Inst. Transport Radio IPMW<1.2m</th>
                            <th>Jasa Pengurusan Penyambungan Listrik</th>
                            <th>Listrik Multiguna (10.6 kVA atau lebih)</th>
                            <th>Sewa Lahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'koneksi.php';
                        $result = $dbconn->query("SELECT * FROM rabtb");
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            foreach ($row as $value) {
                                echo "<td>" . htmlspecialchars($value) . "</td>";
                            }
                            echo "</tr>";
                        }
                        $dbconn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
