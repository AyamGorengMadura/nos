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

// Pagination settings
$limit = 5; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Pagination settings for rabtb
$limitRab = 5; // Number of records per page for RAB
$pageRab = isset($_GET['page_rab']) ? (int)$_GET['page_rab'] : 1;
$startRab = ($pageRab - 1) * $limitRab;

// Handle search for sitetb
$searchSite = isset($_GET['search_site']) ? $_GET['search_site'] : '';

// Handle search for rabtb
$searchRab = isset($_GET['search_rab']) ? $_GET['search_rab'] : '';

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

// Query for sitetb with search and pagination
$sqlSite = "SELECT * FROM sitetb 
            WHERE site_name_plan_combat LIKE '%$searchSite%' 
               OR departement_nop LIKE '%$searchSite%' 
               OR address LIKE '%$searchSite%' 
               OR desa LIKE '%$searchSite%' 
               OR kecamatan LIKE '%$searchSite%' 
               OR kabupaten LIKE '%$searchSite%'
            LIMIT $start, $limit";
$resultSite = $dbconn->query($sqlSite);

// Query for sitetb total count with search for pagination
$totalSqlSite = "SELECT COUNT(*) FROM sitetb 
                  WHERE site_name_plan_combat LIKE '%$searchSite%' 
                     OR departement_nop LIKE '%$searchSite%' 
                     OR address LIKE '%$searchSite%' 
                     OR desa LIKE '%$searchSite%' 
                     OR kecamatan LIKE '%$searchSite%' 
                     OR kabupaten LIKE '%$searchSite%'";
$totalResultSite = $dbconn->query($totalSqlSite);
$totalRowsSite = $totalResultSite->fetch_row()[0];
$totalPagesSite = ceil($totalRowsSite / $limit);

// Query for rabtb with search and pagination
$sqlRab = "SELECT * FROM rabtb 
           WHERE jasa_pengurusan_izin_penempatan LIKE '%$searchRab%' 
              OR jasa_deinst_combat_arrow_dan_perangkat LIKE '%$searchRab%' 
              OR jasa_deinst_cmon_dan_perangkat LIKE '%$searchRab%'
           LIMIT $startRab, $limitRab";
$resultRab = $dbconn->query($sqlRab);

// Query for rabtb total count with search for pagination
$totalSqlRab = "SELECT COUNT(*) FROM rabtb 
                 WHERE jasa_pengurusan_izin_penempatan LIKE '%$searchRab%' 
                    OR jasa_deinst_combat_arrow_dan_perangkat LIKE '%$searchRab%' 
                    OR jasa_deinst_cmon_dan_perangkat LIKE '%$searchRab%'";
$totalResultRab = $dbconn->query($totalSqlRab);
$totalRowsRab = $totalResultRab->fetch_row()[0];
$totalPagesRab = ceil($totalRowsRab / $limitRab);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
    body {
        background-color: lightgrey;
    }
    .search-input {
        max-width: 300px; /* Atur lebar maksimum sesuai kebutuhan */
        margin-top: 20px;
        margin-left: 15px;
    }
</style>
</head>
<body>
<center><h1>Dashboard Admin</h1></center>
<div class="container mt-5">
<a href="../index.php" class="btn btn-danger">Logout</a>
    <h2 class="mb-4">Site Information</h2>
    <div class="card w-100 mt-3">
        <div class="card-header">
            <h5 class="card-title">Site Details</h5>
        </div>
        <form method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control search-input" name="search_site" value="<?php echo htmlspecialchars($searchSite); ?>" placeholder="Search site...">
            </div>
        </form>
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
        
        <!-- Pagination controls for sitetb -->
        <div class="card-footer">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>&search_site=<?php echo urlencode($searchSite); ?>">Previous</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPagesSite; $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>"><a class="page-link" href="?page=<?php echo $i; ?>&search_site=<?php echo urlencode($searchSite); ?>"><?php echo $i; ?></a></li>
                    <?php endfor; ?>
                    <?php if ($page < $totalPagesSite): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>&search_site=<?php echo urlencode($searchSite); ?>">Next</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>

    <h2 class="mt-5 mb-4">RAB Data</h2>

    <div class="card w-100 mt-3">
        <div class="card-header">
            <h5 class="card-title">RAB Details</h5>
        </div>
        <form method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" class="form-control search-input" name="search_rab" value="<?php echo htmlspecialchars($searchRab); ?>" placeholder="Search RAB...">
    </div>
    </form>
        <div class="card-body" style="overflow-x: auto;">
            <table id="rabTable" class="table table-striped table-bordered" style="width:100%">
                <thead class="thead-dark">
                    <tr>
                        <th>Site ID</th>
                        <th>Jasa Pengurusan Izin Penempatan</th>
                        <th>Jasa Deinst Combat Arrow dan Perangkat</th>
                        <th>Jasa Deinst Cmon dan Perangkat</th>
                        <!-- Add all other fields from rabtb -->
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
        
        <!-- Pagination controls for rabtb -->
        <div class="card-footer">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($pageRab > 1): ?>
                        <li class="page-item"><a class="page-link" href="?page_rab=<?php echo $pageRab - 1; ?>&search_rab=<?php echo urlencode($searchRab); ?>">Previous</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPagesRab; $i++): ?>
                        <li class="page-item <?php echo $i == $pageRab ? 'active' : ''; ?>"><a class="page-link" href="?page_rab=<?php echo $i; ?>&search_rab=<?php echo urlencode($searchRab); ?>"><?php echo $i; ?></a></li>
                    <?php endfor; ?>
                    <?php if ($pageRab < $totalPagesRab): ?>
                        <li class="page-item"><a class="page-link" href="?page_rab=<?php echo $pageRab + 1; ?>&search_rab=<?php echo urlencode($searchRab); ?>">Next</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
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

    <!-- Optional JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9zhvFYfXEXozpV2xLCP1igUFI20tVJwC4Kk3mZMkRrjF8J7p/wy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-pQFf4P5p6k/er8FxdV5V2leM0Z48CkF03g4fCgITNfZ7Wln/uHEo2q00e4lgFGNY" crossorigin="anonymous"></script>
    <script>
        // Optional: Add JavaScript for handling modal and form population
    </script>
</body>
</html>
