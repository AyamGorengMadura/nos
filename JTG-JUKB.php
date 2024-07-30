<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JTG-JUKB</title>
    <style>
        body {
            background-image: url(https://th.bing.com/th/id/OIP.ntr3O0LR4AzkKQEcbrWkAAHaEo?rs=1&pid=ImgDetMain);
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

    <div class="">
        <div class="card text-white bg-secondary col-8 mt-3 mx-auto">
            <div class="card-body"> 
                <h4 class="card-title text-center">JUSTIFIKASI KEBUTUHAN BARANG / JASA / PROYEK</h4>
                <a name="" id="" class="btn btn-primary" href="index.php" role="button">Kembali</a>
            </div>
            <div>
                <!-- Add a form around the inputs -->
                <form action="cetakpdf.php" method="post" class="mb-3 col-6 ps-3 mx-auto">
                    <label for="nojukb" class="form-label">NO JUKB</label>
                    <input type="text" name="nojukb" id="nojukb" class="form-control" placeholder="" aria-describedby="helpId" required />
                    
                    <label for="progname" class="form-label pt-3">NAMA PROGRAM/ACTIVITY</label>
                    <input type="text" name="progname" id="progname" class="form-control" placeholder="" aria-describedby="helpId" required />
                    
                    <label for="budget" class="form-label pt-3">Budget Needed</label>
                    <input type="text" name="budget" id="budget" class="form-control" placeholder="" aria-describedby="helpId" required />
                    
                    <label for="bholder" class="form-label pt-3">Budget Holder</label>
                    <input type="text" name="bholder" id="bholder" class="form-control" placeholder="" aria-describedby="helpId" required />
                    
                    <label for="coa" class="form-label pt-3">COA</label>
                    <textarea type="text" name="coa" id="coa" class="form-control" placeholder="" aria-describedby="helpId" required ></textarea>

                    <label for="bperiode" class="form-label pt-3">Periode Budget</label>
                    <input type="text" name="bperiode" id="bperiode" class="form-control" placeholder="" aria-describedby="helpId" required />

                    <label for="wpelaksanaan" class="form-label pt-3">Waktu Pelaksanaan</label>
                    <input type="text" name="wpelaksanaan" id="wpelaksanaan" class="form-control" placeholder="" aria-describedby="helpId" required />

                    <label for="jtransaksi" class="form-label pt-3">Jenis Transaksi</label>
                    <input type="text" name="jtransaksi" id="jtransaksi" class="form-control" placeholder="" aria-describedby="helpId" required />

                    <div class="">
                        <p class="mt-5 fs-4">Alasan Kebutuhan</p>
                        <button type="button" class="btn btn-primary" id="addNeedPointButton">Tambah Poin Alasan</button>
                    </div>

                    <div id="needsContainer">
                        <div class="need-entry">
                            <label for="poin" class="form-label pt-3">Poin A</label>
                            <input type="text" name="poin[]" id="poin" class="form-control" placeholder="" aria-describedby="helpId" required />
                                
                            <label for="rincian" class="form-label pt-3">Rincian A</label>
                            <input type="text" name="rincian[]" id="rincian" class="form-control" placeholder="" aria-describedby="helpId" required />

                        </div>
                    </div>

                    <div class="">
                        <p class="mt-5 fs-4">Lingkup Pekerjaan dan Spesifikasi Teknis</p>
                        <button type="button" class="btn btn-primary" id="addWorkPointButton">Tambah Poin Pekerjaan</button>
                    </div>

                    <div id="worksContainer">
                        <div class="work-entry">
                            <label for="spesifikasi" class="form-label pt-3">Spesifikasi A</label>
                            <input type="text" name="spesifikasi[]" class="form-control" placeholder="" aria-describedby="helpId" required />
                        </div>
                    </div>

                    <!-- Use a submit button instead of a link -->
                    <button type="submit" target="_blank" class="btn btn-primary mt-4">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // Add new point for Alasan Kebutuhan
        document.getElementById('addNeedPointButton').addEventListener('click', function() {
            const needsContainer = document.getElementById('needsContainer');
            const needEntryCount = needsContainer.children.length;

            const pointLabel = document.createElement('label');
            pointLabel.setAttribute('for', 'poin');
            pointLabel.classList.add('form-label', 'pt-3');
            pointLabel.innerText = `Poin ${String.fromCharCode(65 + needEntryCount)}`;

            const pointInput = document.createElement('input');
            pointInput.setAttribute('type', 'text');
            pointInput.setAttribute('name', 'poin[]');
            pointInput.classList.add('form-control');
            pointInput.required = true;

            const rincianLabel = document.createElement('label');
            rincianLabel.setAttribute('for', 'rincian');
            rincianLabel.classList.add('form-label', 'pt-3');
            rincianLabel.innerText = `Rincian ${String.fromCharCode(65 + needEntryCount)}`;

            const rincianInput = document.createElement('input');
            rincianInput.setAttribute('type', 'text');
            rincianInput.setAttribute('name', 'rincian[]');
            rincianInput.classList.add('form-control');
            rincianInput.required = true;

            const needEntry = document.createElement('div');
            needEntry.classList.add('need-entry');
            needEntry.appendChild(pointLabel);
            needEntry.appendChild(pointInput);
            needEntry.appendChild(rincianLabel);
            needEntry.appendChild(rincianInput);

            needsContainer.appendChild(needEntry);
        });

        // Add new point for Lingkup Pekerjaan
        document.getElementById('addWorkPointButton').addEventListener('click', function() {
            const worksContainer = document.getElementById('worksContainer');
            const workEntryCount = worksContainer.children.length;

            const specLabel = document.createElement('label');
            specLabel.setAttribute('for', 'spesifikasi');
            specLabel.classList.add('form-label', 'pt-3');
            specLabel.innerText = `Spesifikasi ${String.fromCharCode(65 + workEntryCount)}`;

            const specInput = document.createElement('input');
            specInput.setAttribute('type', 'text');
            specInput.setAttribute('name', 'spesifikasi[]');
            specInput.classList.add('form-control');
            specInput.required = true;

            const workEntry = document.createElement('div');
            workEntry.classList.add('work-entry');
            workEntry.appendChild(specLabel);
            workEntry.appendChild(specInput);

            worksContainer.appendChild(workEntry);
        });
    </script>
</body>
</html>
