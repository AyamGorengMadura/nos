<li?PHP

include 'koneksi.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .sidebar {
            height: 100vh;
        }

        .dropdown-menu {
            position: static;
            display: block;
            visibility: hidden;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, visibility 0.3s ease;
        }

        .dropdown-menu.show {
            visibility: visible;
            max-height: 200px; /* Sesuaikan dengan tinggi konten dropdown */
        }

    </style>
</head>
<body>

<div class="outercontainer">

    <!-- sidebar start -->
    <div class="col-2 row-12 card bg-dark text-light sidebar">
    <div class="mx-auto mt-4 col-10">
        <p class="">Lorem, ipsum.</p>
        <ul class="nav nav-tabs mb-3 text-decoration-none col-10">
            <li class="nav-item">
                <a class="nav-link text-light mt-2 fs-4" href="#"><i class="bi bi-inbox"></i> Dashboard </a>
            </li>
             <li class="nav-item">
                <a class="nav-link text-light mt-3 fs-4" href="TA.php"><i class="bi bi-send-fill"></i> TA </a>
            </li>
             <li class="nav-item">
                <a class="nav-link text-light mt-3 fs-4" href="RAB.php"><i class="bi bi-send-fill"></i> RAB </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-light mt-3 fs-4" href="JTG-JUKB.php"><i class="bi bi-send-fill"></i> JUKB </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-light mt-3 mb-2 fs-5" data-bs-toggle="modal" data-bs-target="#modaljudul"><i class="bi fs-4 bi-file-plus-fill"></i> Judul Activity </a>
            </li>
        </ul>
    </div>
    </div>    
    <!-- sidebar end -->

    <div class="card text-start col-4 mt-3 mx-auto">
        <div class="card-body">

            <h2 class="card-title text-center mt-3">Submit Dokumen Activity</h2>

                <div class="d-flex justify-content-center mt-4 mb-3">

                        <!-- Button trigger modal -->
                            <!-- Button trigger modal -->
                             
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modaljudul">
                            Judul Activity
                        </button>


                        <button class="btn mx-3 btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" >
                            Dokumen
                        </button>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="TA.php">TA</a></li>
                            <li><a class="dropdown-item" href="RAB.php">RAB</a></li>
                            <li><a class="dropdown-item" href="JTG-JUKB.php">JUKB</a></li>
                        </ul>

                </div>

        </div>
    </div>

</div>

<!-- Modal -->
    <div class="modal fade" id="modaljudul" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Modal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter your name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter your email">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" rows="3" placeholder="Enter your message"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="dropdown">
    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Dropdown button
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="#">Action</a>
        <a class="dropdown-item" href="#">Another action</a>
        <a class="dropdown-item" href="#">Something else here</a>
    </div>
</div>


    
    <script>
        document.querySelectorAll('.dropdown-toggle').forEach(function (dropdownToggle) {
    dropdownToggle.addEventListener('click', function () {
        var dropdownMenu = this.nextElementSibling;
        dropdownMenu.classList.toggle('show');
    });
});

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>