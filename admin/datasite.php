<?php
include ('../koneksi.php');
// Mulai sesi
session_start();

// Hapus semua variabel sesi
$_SESSION = array();

// Jika menggunakan cookie sesi, hapus cookie sesi
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hancurkan sesi
session_destroy();



// Handle Update for sitetb
if (isset($_POST['update_site'])) {
    $siteId = $_POST['siteId'];
    $siteName = $_POST['site_name_plan_combat'];
    $departementNop = $_POST['departement_nop'];
    $address = $_POST['address'];
    $desa = $_POST['desa'];
    $kecamatan = $_POST['kecamatan'];
    $kabupaten = $_POST['kabupaten'];

    $updateSql = "UPDATE sitetb SET 
                    site_name_plan_combat='$siteName',
                    departement_nop='$departementNop',
                    address='$address',
                    desa='$desa',
                    kecamatan='$kecamatan',
                    kabupaten='$kabupaten'
                  WHERE siteId='$siteId'";
    
    $dbconn->query($updateSql);
}

// Handle Update for rabtb
if (isset($_POST['update_rab'])) {
    $siteId = $_POST['siteId'];
    // Add all the fields from rabtb here
    $jasaPengurusanIzin = $_POST['jasa_pengurusan_izin_penempatan'];
    // ... (add all other fields)

    $updateSql = "UPDATE rabtb SET 
                    jasa_pengurusan_izin_penempatan='$jasaPengurusanIzin'
                    -- Add all other fields here
                  WHERE siteId='$siteId'";
    
    $dbconn->query($updateSql);
}

// Handle Delete for sitetb
if (isset($_GET['delete_site'])) {
    $siteId = $_GET['delete_site'];
    $deleteSql = "DELETE FROM sitetb WHERE siteId='$siteId'";
    $dbconn->query($deleteSql);
}

// Handle Delete for rabtb
if (isset($_GET['delete_rab'])) {
    $siteId = $_GET['delete_rab'];
    $deleteSql = "DELETE FROM rabtb WHERE siteId='$siteId'";
    $dbconn->query($deleteSql);
}

// Query for sitetb
$sqlSite = "SELECT * FROM sitetb";  
$resultSite = $dbconn->query($sqlSite);

// Query for rabtb
$sqlRab = "SELECT * FROM rabtb";  
$resultRab = $dbconn->query($sqlRab);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Site Information</h2>
    
<a href="../index.php" class="btn btn-danger">Logout</a>
    <div class="card w-100 mt-3">
        <div class="card-header">
            <h5 class="card-title">Site Details</h5>
        </div>
        <div class="card-body" style="overflow-x: auto;">
            <table id="siteTable" class="table table-striped table-bordered" style="width:100%">
                <thead class="thead-dark">
                    <tr>
                        <th>Site ID</th>
                        <th>Site Name Plan Combat</th>
                        <th>Department NOP</th>
                        <th>Address</th>
                        <th>Desa</th>
                        <th>Kecamatan</th>
                        <th>Kabupaten</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultSite->num_rows > 0): ?>
                        <?php while($row = $resultSite->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['siteId']; ?></td>
                                <td><?php echo $row['site_name_plan_combat']; ?></td>
                                <td><?php echo $row['departement_nop']; ?></td>
                                <td><?php echo $row['address']; ?></td>
                                <td><?php echo $row['desa']; ?></td>
                                <td><?php echo $row['kecamatan']; ?></td>
                                <td><?php echo $row['kabupaten']; ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm editBtnSite" data-id="<?php echo $row['siteId']; ?>" data-name="<?php echo $row['site_name_plan_combat']; ?>" data-nop="<?php echo $row['departement_nop']; ?>" data-address="<?php echo $row['address']; ?>" data-desa="<?php echo $row['desa']; ?>" data-kecamatan="<?php echo $row['kecamatan']; ?>" data-kabupaten="<?php echo $row['kabupaten']; ?>">Edit</button>
                                    <a href="?delete_site=<?php echo $row['siteId']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
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
    
    <div class="card w-100 mt-3">
        <div class="card-header">
            <h5 class="card-title">RAB Data</h5>
        </div>
        <div class="card-body" style="overflow-x: auto;">
            <table id="rabTable" class="table table-striped table-bordered" style="width:100%">
                <thead class="thead-dark">
                    <tr>
                        <th>Site ID</th>
                        <th>Jasa Penggunaan Izin Penempatan</th>
                        <th>Jasa Deinst Combat Arrow</th>
                        <th>Jasa Deinst Cmon</th>
                        <!-- Add all other columns from rabtb -->
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultRab->num_rows > 0): ?>
                        <?php while($row = $resultRab->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['SiteID']; ?></td>
                                <td><?php echo $row['jasa_pengurusan_izin_penempatan']; ?></td>
                                <td><?php echo $row['jasa_deinst_combat_arrow_dan_perangkat']; ?></td>
                                <td><?php echo $row['jasa_deinst_cmon_dan_perangkat']; ?></td>
                                <!-- Add all other fields from rabtb -->
                                <td>
                                    <button class="btn btn-warning btn-sm editBtnRab" data-id="<?php echo $row['SiteID']; ?>" data-izin="<?php echo $row['jasa_pengurusan_izin_penempatan']; ?>" data-combat="<?php echo $row['jasa_deinst_combat_arrow_dan_perangkat']; ?>" data-cmon="<?php echo $row['jasa_deinst_cmon_dan_perangkat']; ?>">Edit</button>
                                    <a href="?delete_rab=<?php echo $row['SiteID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No data found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Form Modal for sitetb -->
    <div class="modal fade" id="editModalSite" tabindex="-1" aria-labelledby="editModalSiteLabel" aria-hidden="true">
        <!-- (modal content for sitetb remains the same) -->
    </div>

    <!-- Edit Form Modal for rabtb -->
    <div class="modal fade" id="editModalRab" tabindex="-1" aria-labelledby="editModalRabLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalRabLabel">Edit RAB</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="siteId" id="editRabSiteId">
                        <div class="mb-3">
                            <label for="editJasaPengurusanIzin" class="form-label">Jasa Pengurusan Izin Penempatan</label>
                            <input type="text" class="form-control" name="jasa_pengurusan_izin_penempatan" id="editJasaPengurusanIzin" required>
                        </div>
                        <!-- Add more fields for rabtb -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="update_rab" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<!-- (Scripts remain the same) -->

<script>
    $(document).ready(function() {
        $('#siteTable').DataTable();
        $('#rabTable').DataTable();

        // Open edit modal for sitetb with pre-filled data
        $('.editBtnSite').on('click', function() {
            $('#editSiteId').val($(this).data('id'));
            $('#editSiteName').val($(this).data('name'));
            $('#editDepartementNop').val($(this).data('nop'));
            $('#editAddress').val($(this).data('address'));
            $('#editDesa').val($(this).data('desa'));
            $('#editKecamatan').val($(this).data('kecamatan'));
            $('#editKabupaten').val($(this).data('kabupaten'));
            $('#editModalSite').modal('show');
        });

        // Open edit modal for rabtb with pre-filled data
        $('.editBtnRab').on('click', function() {
            $('#editRabSiteId').val($(this).data('id'));
            $('#editJasaPengurusanIzin').val($(this).data('izin'));
            // Add more fields for rabtb
            $('#editModalRab').modal('show');
        });
    });
</script>

</body>
</html>