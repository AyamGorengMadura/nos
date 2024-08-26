<?PHP

$servername = "localhost";
$username = "sudo";
$password = "admin";
$dbname = "tsel_nosdb";

$dbconn = new mysqli($servername, $username, $password, $dbname);
$pdo = new mysqli($servername, $username, $password, $dbname);

if ($dbconn->connect_error) {
    die("Connection error: " . $dbconn->connect_error);
}

?>