<?php
include ('../koneksi.php');

// Handle Update
if (isset($_POST['update'])) {
    $siteId = $_POST['siteId'];
    $siteName = $_POST['site_name_plan_combat'];
    $departementNop = $_POST['departement_nop'];
    $address = $_POST['address'];
    $desa = $_POST['desa'];
    $kecamatan = $_POST['kecamatan'];
    $kabupaten = $_POST['kabupaten'];

    // Update query
    $updateSql = "UPDATE sitetb SET 
                    site_name_plan_combat='$siteName',
                    departement_nop='$departementNop',
                    address='$address',
                    desa='$desa',
                    kecamatan='$kecamatan',
                    kabupaten='$kabupaten'
                  WHERE siteId='$siteId'";
    
    if ($dbconn->query($updateSql) === TRUE) {
    } else {
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $siteId = $_GET['delete'];

    // Delete query
    $deleteSql = "DELETE FROM sitetb WHERE siteId='$siteId'";
    if ($dbconn->query($deleteSql) === TRUE) {
    } else {
    }
}

// Query untuk menggabungkan data dari sitetb dan rabtb menggunakan JOIN
$sql = "SELECT sitetb.*, rabtb.* FROM sitetb INNER JOIN rabtb ON sitetb.siteId = rabtb.siteId";  
$result = $dbconn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Table - Site Information</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Site Information</h2>
    <a
        name=""
        id=""
        class="btn btn-primary"
        href="#"
        role="button"
        >Kembali</a
    >
    
    <div class="card w-100">
        <div class="card-header">
            <h5 class="card-title">Site Details</h5>
        </div>
        <div class="card-body" style="overflow-x: auto;">
            <table id="siteTable" class="table table-striped table-bordered" style="width:100%">
                <thead class="thead-dark mt-3">
                    <tr>
                        <th>Site ID</th>
                        <th>Site Name Plan Combat</th>
                        <th>Department NOP</th>
                        <th>Address</th>
                        <th>Desa</th>
                        <th>Kecamatan</th>
                        <th>Kabupaten</th>
                        <th>Longitude</th>
                        <th>Latitude</th>
                        <th>Prediksi Revenue SIFA</th>
                        <th>Jasa Penggunaan Izin Penempatan</th>
                        <th>Jasa Deinst Combat Arrow</th>
                        <th>Jasa Deinst Cmon</th>
                        <th>Mobilisasi Combat Arrow</th>
                        <th>Mobilisasi Cmon</th>
                        <th>Custom Pole 1 Sector</th>
                        <th>Custom Pole 3 Sector</th>
                        <th>Inst Combat Arrow</th>
                        <th>Instalasi Cmon</th>
                        <th>Deinst Transport Radio</th>
                        <th>Inst Transport Radio</th>
                        <th>Pengurusan Penyambungan Listrik</th>
                        <th>Listrik Multiguna</th>
                        <th>Sewa Lahan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['siteId']; ?></td>
                                <td><?php echo $row['site_name_plan_combat']; ?></td>
                                <td><?php echo $row['departement_nop']; ?></td>
                                <td><?php echo $row['address']; ?></td>
                                <td><?php echo $row['desa']; ?></td>
                                <td><?php echo $row['kecamatan']; ?></td>
                                <td><?php echo $row['kabupaten']; ?></td>
                                <td><?php echo $row['longitude']; ?></td>
                                <td><?php echo $row['latitude']; ?></td>
                                <td><?php echo $row['prediksi_revenue_sifa']; ?></td>
                                <td><?php echo $row['jasa_pengurusan_izin_penempatan']; ?></td>
                                <td><?php echo $row['jasa_deinst_combat_arrow_dan_perangkat']; ?></td>
                                <td><?php echo $row['jasa_deinst_cmon_dan_perangkat']; ?></td>
                                <td><?php echo $row['jasa_mobilisasi_per_50_km_combat_arrow']; ?></td>
                                <td><?php echo $row['jasa_mobilisasi_per_50_km_cmon']; ?></td>
                                <td><?php echo $row['pengadaan_custom_pole_cmon_1_sector']; ?></td>
                                <td><?php echo $row['pengadaan_custom_pole_cmon_3_sector']; ?></td>
                                <td><?php echo $row['jasa_inst_combat_arrow_dan_perangkat']; ?></td>
                                <td><?php echo $row['jasa_instalasi_cmon_dan_perangkat']; ?></td>
                                <td><?php echo $row['jasa_deinst_transport_radio_ipmw_less_1_2m']; ?></td>
                                <td><?php echo $row['jasa_inst_transport_radio_ipmw_less_1_2m']; ?></td>
                                <td><?php echo $row['jasa_pengurusan_penyambungan_listrik']; ?></td>
                                <td><?php echo $row['listrik_multiguna']; ?></td>
                                <td><?php echo $row['sewa_lahan']; ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm editBtn" data-id="<?php echo $row['siteId']; ?>" data-name="<?php echo $row['site_name_plan_combat']; ?>" data-nop="<?php echo $row['departement_nop']; ?>" data-address="<?php echo $row['address']; ?>" data-desa="<?php echo $row['desa']; ?>" data-kecamatan="<?php echo $row['kecamatan']; ?>" data-kabupaten="<?php echo $row['kabupaten']; ?>">Edit</button>
                                    <a href="?delete=<?php echo $row['siteId']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="8">No data found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Form Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Site</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="siteId" id="editSiteId">
                        <div class="mb-3">
                            <label for="editSiteName" class="form-label">Site Name</label>
                            <input type="text" class="form-control" name="site_name_plan_combat" id="editSiteName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDepartementNop" class="form-label">Department NOP</label>
                            <input type="text" class="form-control" name="departement_nop" id="editDepartementNop" required>
                        </div>
                        <div class="mb-3">
                            <label for="editAddress" class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" id="editAddress" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDesa" class="form-label">Desa</label>
                            <input type="text" class="form-control" name="desa" id="editDesa" required>
                        </div>
                        <div class="mb-3">
                            <label for="editKecamatan" class="form-label">Kecamatan</label>
                            <input type="text" class="form-control" name="kecamatan" id="editKecamatan" required>
                        </div>
                        <div class="mb-3">
                            <label for="editKabupaten" class="form-label">Kabupaten</label>
                            <input type="text" class="form-control" name="kabupaten" id="editKabupaten" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="update" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#siteTable').DataTable();

        // Open edit modal with pre-filled data
        $('.editBtn').on('click', function() {
            $('#editSiteId').val($(this).data('id'));
            $('#editSiteName').val($(this).data('name'));
            $('#editDepartementNop').val($(this).data('nop'));
            $('#editAddress').val($(this).data('address'));
            $('#editDesa').val($(this).data('desa'));
            $('#editKecamatan').val($(this).data('kecamatan'));
            $('#editKabupaten').val($(this).data('kabupaten'));
            $('#editModal').modal('show');
        });
    });
</script>

</body>
</html>
