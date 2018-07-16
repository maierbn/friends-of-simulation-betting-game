<?
  $query = "SELECT * FROM user WHERE id = ".$_SESSION["user_id"];
  $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
  $line = $result->fetch_assoc();
?>
<h1>Welcome, <?=$line["displayed_name"]?>!</h1>
<!--<p>
You have selected your nations, everything done so far! The possibility to change them will be in the knockout phase.
</p>-->
<!--
<p>
  You can now specify your ranking of the teams, how you think their chances are at the World Cup. Click on "Define your ranking" in the menu.
  This has to be done before the Sunday before the event starts, i.e. 10th of June.
</p>
<p>
  Subsequently, from 10th - 13th of June, you will select 4 teams that will collect points for you. See the rules for details.
</p>
-->
<p>
  Your score is <?=$own_score;?>, rank #<?=$own_rank?> of <?=$n_users?>. <!--User id <?=$_SESSION["user_id"]?>.-->
</p>

<?
  if ($line["own_ranking"] == 0)
  {
?>
   <!--<p>Note, that you didn't define your own ranking (maybe because you registered late or you forgot it). This is no problem, you just won't be able to change nations later.</p>-->
   <p>You cannot substitute nations. </p>
<?}?>

<?  
  $own_nations = array();
  $query = 'SELECT * FROM user_holds_nation JOIN nation ON (nation.id = nation_id) WHERE user_id='.$_SESSION["user_id"].' AND time_from < CURRENT_TIMESTAMP AND time_to >= CURRENT_TIMESTAMP';
  $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
  
  if ($result->num_rows == 0)
  {
  ?>
<p>
  The common ranking has been determined and now it's time for you to choose four teams that will collect points for you. 
  You can do that until the opening match starts on this Thursday (June 14th), at 17:00.
</p>
<p>
  Remember that according to rule §2a the four nations' values have to sum to a maximum value of 60. (Maybe check the rules again).
  The values of the nations are given by the common ranking, see the "Nation rankings" page.
</p>
<?
  }
  else 
  {
?>
<p>
Your selected nations:
</p>
<ul class="sortable">
<?    
    $sum = 0;
    while($line = $result->fetch_assoc())
    {
      $own_nations[] = $line["id"];
          
      if (isset($ranking_common_pos[$line["id"]]))
        $sum += $ranking_common[$ranking_common_pos[$line["id"]]-1]["value"];
?>  
    <li class="ui-state-default nation" id="nation_<?=$line["nation_id"]?>">
      <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
      <div class="nation abbr"><?=$line["abbreviation"]?></div>
      <img class="nation" src="images/<?=$line["image"]?>" />
      <!--<div class="nation name"><?=$line["name"]?><?=$ranking_common_pos[$id]?></div>  -->
      <div class=".jqm-content nation name">
        <?=$line["name"]?><?=(isset($ranking_common_pos[$line["id"]]) ? "&nbsp;<span class=\"value\">(".$ranking_common[$ranking_common_pos[$line["id"]]-1]["value"].")</span>" : "")?>
      </div>       
      <br>
    </li>
<?  } 
?>    
</ul>
<? if ($sum > 0) { ?>
<p>The sum is <?=$sum?>.</p>
<? }}?>

<!--
<p>
Trading is possible after the group phase and only for nations for which (current rank < common rank) and (own rank < common rank).
This means you could currently buy the following nations. The given ranks are of the common/own/current ranking.
</p>
-->

<?
  if ($line["own_ranking"] != 1)
  {
?>
<!--
<p>
Now it's the knockout phase, so you can "substitute" your nations on the "Substitute nations" page. 
You could buy the following national teams. 
The listed ranks are of the common/own/current ranking.
</p>
<?  
  }?>
<ul class="sortable">
<?
foreach ($buyable_nations as $id)
{
  // you can't exchange for nations you already have
  if (in_array($id, $own_nations))
  {
    continue;
  }
  
  $query = 'SELECT * FROM nation WHERE id='.$id;
  $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
  $line = $result->fetch_assoc();
?>  
    <li class="ui-state-default nation" id="nation_<?=$line["nation_id"]?>">
      <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
      <div class="nation abbr"><?=$line["abbreviation"]?></div>
      <img class="nation" src="images/<?=$line["image"]?>" />
      <div class="nation name"><?=$line["name"]?></div>
      <span class="nation rankings">
      Cost: <?=$ranking_current[$ranking_current_pos[$id]-1]["score"]?>,
      Ranks #<?=$ranking_common_pos[$id]?> > #<?=$ranking_own_pos[$id]?>, #<?=$ranking_current_pos[$id]?>
      </span>
      <br>
    </li>
<?    
}
?>
</ul>
-->
<p>
The game is over, thank you for participating.
</p>
