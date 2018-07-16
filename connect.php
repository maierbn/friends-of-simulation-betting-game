<?

global $mysqli;

// connect to db
$mysqli = new mysqli("localhost", "user",  str_rot13("rot13password"), "db_name");
if ($mysqli->connect_errno)
{
  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
?>
