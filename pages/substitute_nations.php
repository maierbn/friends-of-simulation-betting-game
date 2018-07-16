<h1>Substitute nations</h1>

<?
  // check if it is currently possible to substitute nations
  $query = "SELECT nation0.name nation0_name, nation1.name nation1_name, time_start FROM `match` JOIN nation nation0 ON (nation0.id = nation0_id) JOIN nation nation1 ON (nation1.id = nation1_id) WHERE time_start < CURRENT_TIMESTAMP AND finished = 0";
  $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
  
  if ($result->num_rows > 0)
  {
    $line = $result->fetch_assoc();
?>
    <p>
      It is currently not possible to substitute nations because matches are not finished yet, e.g. <?=$line["nation0_name"]?> vs. <?=$line["nation1_name"]?>, started <?=date("d/m H:i",strtotime($line["time_start"]))?>.
    </p>
<?    
  }
  else if (count($buyable_nations) == 0)
  { ?>
    <p>You currently cannot exchange any nations.</p>
<?}
  else {
?>

<p>
Here you can substitute your nations.
</p>
<!--<p>
<span style="color:red">Should we lower the price of the nations to half the score?</span>
</p>-->
<form action="post" id="substitute_nations">
<table>
  <tr>
    <td>Nation to drop/remove:</td>
    <td>
    <div class="ui-field-contain">
      <select id="nation-drop" data-native-menu="false" class="filterable-select">
        <option value="-1">Select...</option>
<?
        include_once("connect.php");

        $query = 'SELECT name,value,nation.id id FROM user_holds_nation JOIN nation ON (nation.id = nation_id) JOIN ranking_common ON (ranking_common.nation_id = nation.id) WHERE user_id='.$_SESSION["user_id"].' AND time_from < CURRENT_TIMESTAMP AND time_to >= CURRENT_TIMESTAMP';
        $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
        $own_nations = array();

        while($line = $result->fetch_assoc())
        {
          $own_nations[] = $line["id"];
        ?>  
          <option value="<?=$line["id"]?>"><?=$line["name"]?> (<?=$line["value"]?>)</option>
        <?    
        }
        ?>
      </select>
    </div>
    </td>
  </tr>
  <tr>
    <td>Nation to get in exchange:</td>
    <td>
    <div class="ui-field-contain">
      <select id="nation-get" data-native-menu="false" class="filterable-select" onchange="update_new_score()">
        <option value="-1">Select...</option>
<?
        $str = "";
        foreach ($buyable_nations as $nation_id)
        {
          // you can't exchange for nations you already have
          if (in_array($nation_id, $own_nations))
          {
            continue;
          }

          $query = 'SELECT * FROM nation JOIN ranking_common ON (ranking_common.nation_id = nation.id) WHERE nation.id='.$nation_id;
          $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
          $line = $result->fetch_assoc();
          
          $cost = $ranking_current[$ranking_current_pos[$nation_id]-1]["score"];
          $new_score = $own_score - $cost;
          $str .= "<span style='display:none' id='new_score-".$nation_id."'>$new_score</span>";
        
        ?>  
          <option value="<?=$nation_id?>"><?=$line["name"]?> (<?=$line["value"]?>), Cost: <?=$cost?></option>
        <?    
        }
        ?>
      </select>
    </div>
    <?=$str?>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <span style='display:none' id="new_score_text">
        Your current score is <?=$own_score;?>, if you click on Submit below, your new score will be <span id="new_score"></span>! But you may have different chances.
      </span>
    </td>
  </tr>
</table>
<input type="hidden" name="action" value="substitute_nation" />
<input type="submit" value="Submit" />
</form>

<?}?>
