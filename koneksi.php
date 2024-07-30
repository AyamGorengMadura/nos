<?PHP

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tsel_nosdb";

$dbconn = new mysqli($servername, $username, $password, $dbname);

if ($dbconn->connect_error) {
    die("Connection error: " . $dbconn->connect_error);
}

?>