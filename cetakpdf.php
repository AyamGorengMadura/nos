<?php
require_once __DIR__ . '/vendor/autoload.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nojukb = $_POST['nojukb'] ?? '';
    $progname = $_POST['progname'] ?? '';
    $budget = $_POST['budget'] ?? '';
    $bholder = $_POST['bholder'] ?? '';
    $coa = $_POST['coa'] ?? '';
    $bperiode = $_POST['bperiode'] ?? '';
    $wpelaksanaan = $_POST['wpelaksanaan'] ?? '';
    $jtransaksi = $_POST['jtransaksi'] ?? '';
    $poin = isset($_POST['poin']) ? (array)$_POST['poin'] : [];
    $rincian = isset($_POST['rincian']) ? (array)$_POST['rincian'] : [];
    $spesifikasi = isset($_POST['spesifikasi']) ? (array)$_POST['spesifikasi'] : [];

    // Script untuk buat garis baru
    $formattedCOA = nl2br(htmlspecialchars($coa));

    // 
    if ($nojukb && $progname && $budget && $bholder && $coa) {
        
        $html = "
            <p style='font-size:20px;' align='center'>JUSTIFIKASI KEBUTUHAN BARANG / JASA / PROYEK</p>
            <hr>

            <table>
            <tr>
            <td><p>NO JUKB</p></td>
            <td colspan='3'>:</td>
            <td><p>".htmlspecialchars($nojukb)."</p></td>
            </tr>
            <tr>
            <td><p>Nama Program/Activity</p></td>
            <td colspan='3'>:</td>
            <td><p>".htmlspecialchars($progname)."</p></td>
            </tr>
            <tr>
            <td><p>Budget Needed</p></td>
            <td colspan='3'>:</td>
            <td><p>".htmlspecialchars($budget)."</p></td>
            </tr>
            <tr>
            <td><p>Budget Holder</p></td>
            <td colspan='3'>:</td>
            <td><p>".htmlspecialchars($bholder)."</p></td>
            </tr>
            <tr>
            <td><p>COA</p></td>
            <td colspan='3'>:</td>
            <td><p>$formattedCOA</p></td>
            </tr>
            <tr>
            <td><p>Periode Budget</p></td>
            <td colspan='3'>:</td>
            <td><p>".htmlspecialchars($bperiode)."</p></td>
            </tr>
            <tr>
            <td><p>Waktu Pelaksanaan</p></td>
            <td colspan='3'>:</td>
            <td><p>".htmlspecialchars($wpelaksanaan)."</p></td>
            </tr>
            <tr>
            <td><p>Jenis Transaksi</p></td>
            <td colspan='3'>:</td>
            <td><p>".htmlspecialchars($jtransaksi)."</p></td>
            </tr>
            </table>

            <hr>

            <p><b>I.    Alasan Kebutuhan:</b><br>Latar belakang dilaksanakannya pekerjaan ini adalah sebagai berikut.</p>
            <ul>
        ";

        // Add points and details for "Alasan Kebutuhan"
        foreach ($poin as $index => $point) {
            $rincianText = htmlspecialchars($rincian[$index] ?? '');
            $html .= "<li><b>" . chr(65 + $index) . ".</b> " . htmlspecialchars($point) . "<br>" . $rincianText . "</li>";
        }

        $html .= "</ul>";

        // Add "Lingkup Pekerjaan dan Spesifikasi Teknis" section
        $html .= "<p><b>II.   Lingkup Pekerjaan dan Spesifikasi Teknis:</b></p><ul>";

        foreach ($spesifikasi as $index => $spec) {
            $html .= "<li><b>" . chr(65 + $index) . ".</b> " . htmlspecialchars($spec) . "</li>";
        }

        $html .= "</ul>";

        // Generate the PDF
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    } else {
        echo "Tolong isi input yang kosong.";
    }
}
?>
