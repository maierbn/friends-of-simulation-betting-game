<?

include("connect.php");

// parse ranking common
$query = 'SELECT * FROM ranking_common '.
  ' JOIN nation ON nation.id = ranking_common.nation_id '.
  ' ORDER BY rank';
$result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);

$ranking_common = array();
$ranking_common_pos = array();

for($i = 1; $line = $result->fetch_assoc(); $i++)
{
  $line["rank"] = $i;
  $ranking_common[] = $line;
  $ranking_common_pos[$line["id"]] = $i;
}

// parse ranking own
$query = 'SELECT * FROM ranking_user '.
  ' JOIN nation ON nation.id = ranking_user.nation_id '.
  ' WHERE user_id='.$_SESSION["user_id"].' ORDER BY rank';
$result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);

$ranking_own = array();
$ranking_own_pos = array();

for($i = 1; $line = $result->fetch_assoc(); $i++)
{
  $line["rank"] = $i;
  $ranking_own[] = $line;
  $ranking_own_pos[$line["id"]] = $i;
}

$has_own_ranking = true;
$no_user = true;

// parse ranking user
if (isset($_GET["user_id"]))
{
  $no_user = false;
  $query = 'SELECT own_ranking FROM user WHERE id="'.$mysqli->real_escape_string($_GET["user_id"]).'" ';
  $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
  $line = $result->fetch_assoc();
  $has_own_ranking = true;
  
  if ($line["own_ranking"] == 0)
    $has_own_ranking = false;
  
  
  $query = 'SELECT * FROM ranking_user '.
    ' JOIN nation ON nation.id = ranking_user.nation_id '.
    ' WHERE user_id="'.$mysqli->real_escape_string($_GET["user_id"]).'" ORDER BY rank';
  $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
}

$ranking_user = array();
$ranking_user_pos = array();

if (isset($_GET["user_id"]))
{
  for($i = 1; $line = $result->fetch_assoc(); $i++)
  {
    $line["rank"] = $i;
    $ranking_user[] = $line;
    $ranking_user_pos[$line["id"]] = $i;
  }
}

// parse ranking current 
include_once("compute_current_ranking.php");
compute_current_ranking($ranking_current, $ranking_current_pos);

function isBuyable($id)
{
  global $ranking_current_pos, $ranking_common_pos, $ranking_own_pos;
  
  return $ranking_current_pos[$id] < $ranking_common_pos[$id] && $ranking_own_pos[$id] < $ranking_common_pos[$id] ;
}

?>


<h1>Rankings</h1>

Here the different rankings can be compared and you can inspect how other participants ordered the nations.
<!-- The "current ranking" only makes sense after the tournament has started.-->
<fieldset data-role="controlgroup" data-type="horizontal">
    <legend>Show the following rankings:</legend>
    <input name="checkbox-common-ranking" id="checkbox-common-ranking" onchange="display_common_ranking()" checked="checked" type="checkbox">
    <label for="checkbox-common-ranking">Common ranking</label>
    <input name="checkbox-own-ranking" id="checkbox-own-ranking" onchange="display_own_ranking()" checked="checked" type="checkbox">
    <label for="checkbox-own-ranking">Own ranking</label>
    <input name="checkbox-user-ranking" id="checkbox-user-ranking" onchange="display_user_ranking()" <?=(isset($_GET["user_id"])? "checked=\"checked\"": "")?> type="checkbox">
    <label for="checkbox-user-ranking">Other user ranking</label>
    <input name="checkbox-current-ranking" id="checkbox-current-ranking" onchange="display_current_ranking()" checked="checked" type="checkbox">
    <label for="checkbox-current-ranking">Current ranking</label>
</fieldset>

  <table><tr><td><label for="other-user-id" class="ranking other-user">Other user</label></td><td>
  <!--<div class="ui-field-contain">-->
      <select id="other-user-id" data-native-menu="false" class="filterable-select">
        <option value="-1">Select...</option>
<?
        include_once("connect.php");

        $query = 'SELECT * FROM user WHERE id != '.$_SESSION["user_id"].' ORDER BY displayed_name';
        $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);

        $name = "";
        while($line = $result->fetch_assoc())
        {
          $selected = "";
          if (isset($_GET["user_id"]))
          {
            if ($_GET["user_id"] == $line["id"])
            {
              $selected = "selected='selected'";
              $name = $line["displayed_name"];
            }
          }
        ?>  
          <option value="<?=$line["id"]?>" <?=$selected?>>
            <?=$line["displayed_name"]?>
          </option>
        <?    
        }
        ?>
      </select>
    <!--</div>-->
    </td></tr></table>
    
Teams can be bought if their own and current ranks are both higher than their common rank.
<div style="float:left">
  <label for="checkbox-buyable" class="ranking">Show nations that can be bought in green</label>
  <input type="checkbox" id="checkbox-buyable" onchange="display_buyable()" class="ranking" checked="checked"/>
</div>
<br>
    
<table id="rankings-comparison">
  <thead>
  </thead>
  <tbody>
    <tr>
      <td valign="top" id="ranking-common" >
      <!-- common ranking -->
        <h2>Common ranking</h2>
        <table class="ranking">
          <thead>
            <tr>
              <th>#</th>
              <th></th>
              <th>Nation</th>
              <th>Value</th>
            </tr>
          </thead>
          <tbody>
<?

        $buyable_nations = array();
        foreach($ranking_common as $line)
        {
          if (isBuyable($line["id"]))
          {
            $buyable_nations[] = $line["id"];
          }
          $class = (isBuyable($line["id"])? "buyable" : "");
?>       
            <tr>
              <th class="<?=$class?>"><?=$line["rank"]?></th>
              <!--<td><div class="nation abbr"><?=$line["abbreviation"]?></div></td>-->
              <td class="<?=$class?>"><img class="nation_ranking" src="/images/<?=$line["image"]?>" /></td>
              <td class="<?=$class?>"><div class=".jqm-content nation name"><?=$line["name"]?></div></td>
              <td class="<?=$class?>">
                <?=(isset($line["value"])? "&nbsp;<span class=\"value\">(".$line["value"].")</span>" : "")?>
              </td>
            </tr>
<?      }?> 
          </tbody>
        </table>
      </td>
      <td valign="top" id="ranking-own" >
        <!-- own ranking -->
        <h2>Own ranking</h2>
        <table data-mode="columntoggle" class="ranking">
          <thead>
            <tr>
              <th>#</th>
              <th></th>
              <th>Nation</th>
            </tr>
          </thead>
          <tbody>
<?
        foreach($ranking_own as $line)
        {
          $class = (isBuyable($line["id"])? "buyable" : "");
?>       
            <tr>
              <th class="<?=$class?>"><?=$line["rank"]?></th>
              <!--<td><div class="nation abbr"><?=$line["abbreviation"]?></div></td>-->
              <td class="<?=$class?>"><img class="nation_ranking" src="/images/<?=$line["image"]?>" /></td>
              <td class="<?=$class?>"><div class=".jqm-content nation name"><?=$line["name"]?></div></td>
            </tr>
<?      }?> 
          </tbody>
        </table>
      </td>
      <td valign="top" id="ranking-user" >
        <!-- other user ranking -->
        <h2><?=$name?></h2>
<?
        if($no_user)
        {
?>
           <p>(Please select a user)</p>
<?          
        }
        else if(!$has_own_ranking)
        {
?>
           <p>(Did not specify own ranking.)</p>
<?      }
        else 
        {
?>
          <table data-mode="columntoggle" class="ranking">
            <thead>
              <tr>
                <th>#</th>
                <th></th>
                <th>Nation</th>
              </tr>
            </thead>
            <tbody>
<?
          foreach($ranking_user as $line)
          {
?>       
              <tr>
                <th ><?=$line["rank"]?></th>
                <!--<td><div class="nation abbr"><?=$line["abbreviation"]?></div></td>-->
                <td ><img class="nation_ranking" src="/images/<?=$line["image"]?>" /></td>
                <td ><div class=".jqm-content nation name"><?=$line["name"]?></div></td>
              </tr>
<?        }?> 
            </tbody>
          </table>
<?      }?>        
      </td>
      <td valign="top" id="ranking-current" >
        <!-- current ranking -->
        <h2>Current ranking</h2>
        <table data-mode="columntoggle" class="ranking">
          <thead>
            <tr>
              <th>#</th>
              <th></th>
              <th>Nation</th>
              <th>Score/Cost</th>
            </tr>
          </thead>
          <tbody>
<?
        foreach($ranking_current as $line)
        {
          $class = (isBuyable($line["id"])? "buyable" : "");
?>       
            <tr>
              <th class="<?=$class?>"><?=$line["rank"]?></th>
              <!--<td><div class="nation abbr"><?=$line["abbreviation"]?></div></td>-->
              <td class="<?=$class?>"><img class="nation_ranking" src="/images/<?=$line["image"]?>" /></td>
              <td class="<?=$class?>"><div class=".jqm-content nation name"><?=$line["name"]?></div></td>
              <td class="<?=$class?>"><div class="score"><?=$line["score"]?></div></td>
            </tr>
<?      }?> 
          </tbody>
        </table>
      </td>
    </tr>
    </tbody>
</table>
