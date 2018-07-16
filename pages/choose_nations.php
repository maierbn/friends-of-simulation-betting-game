
<h1>Choose Nations</h1>
<p>
Make your choice. As soon as you click on "Save" there will be no way back!
</p>

<form id="save_chosen_nations">
  <div class="ui-field-contain">
<?
    for ($i = 1; $i <= 4; $i++)
    {
?>          
    <label for="nation-<?=$i?>">Choose nation <?=$i?>:</label>
    <select id="nation-<?=$i?>" data-native-menu="false" class="filterable-select">
      <option value="-1">Select...</option>
<?
      include("connect.php");

      $query = 'SELECT * FROM ranking_common JOIN nation ON (nation_id = nation.id) ORDER BY rank';
      $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);

      while($line = $result->fetch_assoc())
      {
      ?>  
        <option value="<?=$line["id"]?>"><?=$line["name"]?> (<?=$line["value"]?>)</option>
      <?    
      }
      ?>
    </select>
<?
}?>    
  </div>
  <label for="sum">Enter the sum of your nations' values, which must be ≤ 60.</label>
  <input type="text" name="sum" value="" id="sum" class="sum">
  <input type="submit" id="save_chosen_nations_button" data-role="button" value="Save">
</form>

