

<h1>Registered Users</h1>


<?
include("connect.php");

$query = 'SELECT * FROM user WHERE id != 4 ORDER BY displayed_name';
$result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);

// if there was no match in the database
if ($result->num_rows == 0)
{
  echo "None.";
}
else 
{
?>
<p>
The following <?=$result->num_rows?> users are currently registered, listed in alphabetical order. (Don't take the right column too seriously.)
</p>
  <table class="users-table">
    <tr class="users-table">
      <th class="users-table">Name</th>
      <th class="users-table">Duration reading Terms & Conditions</th>
    </tr>
<?  
  while($line = $result->fetch_assoc())
  {
?>
    <tr class="users-table">
      <td class="users-table"><?=$line["displayed_name"]." <span class=\"login_name\">(".$line["login_name"].")</span>"?></td>
      <td class="users-table">
<?
      $duration = $line["duration_terms"];
      if ($duration == 0)
      {
        if ($line["id"] == 24)
          echo "(trusts me and didn't read it) <br>Das stimmt nicht, hatte die Seite in einem separaten Tab offen.";
        else echo "(trusts me and didn't read it)";
      }
      else
      {

        $sec_hh = 60 * 60; // millisecs per hour
        $sec_mm = 60; // millisecs per minute
        echo sprintf("%02s:%02s:%02s",
          (int)($duration / $sec_hh),
          (int)(($duration % $sec_hh) / $sec_mm),
          (int)(($duration % $sec_hh) % $sec_mm) );
      }
?>      
      </td>
    </tr>
<?
  } ?>
  </table>
<?  
}
?>
