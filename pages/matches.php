
<h1>Matches</h1>

<p>
  Here the matches and their results are listed, a red score means that the match is in play. The results are updated automatically when you reload the page.
  <!--If you want to know if e.g. your computed current score reflects the latest match results, you can check here if the results are already entered into the system.-->
</p>

<table>
  <thead>
    <tr>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
<?
  include("connect.php");
  $query = "SELECT * FROM match_group";
  $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
  
  while($line = $result->fetch_assoc())
  {
    $match_group = $line["name"];
?>
    <tr>
      <td colspan=6>
        <h2><?=$match_group?></h2>
      </td>
    </tr>
<?  
    $query2 = "SELECT time_start, goals0, goals1, nation0.name name0, nation0.image image0, ranking_common0.value value0, ".
      "nation1.name name1, nation1.image image1 ,ranking_common1.value value1, finished FROM `match` ".
      " LEFT JOIN nation AS nation0 ON (match.nation0_id = nation0.id) ".
      " LEFT JOIN ranking_common as ranking_common0 ON (ranking_common0.nation_id = nation0.id) ".
      " LEFT JOIN nation AS nation1 ON (match.nation1_id = nation1.id) ".
      " LEFT JOIN ranking_common as ranking_common1 ON (ranking_common1.nation_id = nation1.id) ".
      " WHERE match_group_id=\"".$line["id"]."\" ORDER BY time_start";
    $result2 = $mysqli->query($query2) or die("error in query [$query2]: ".$mysqli->error);
?>
   <!--<tr><td> <?=$query2?></td></tr>-->
<?    
  
    while($line2 = $result2->fetch_assoc())
    {
      $dt = DateTime::createFromFormat('Y-m-d H:i:s', $line2["time_start"], new DateTimeZone('Europe/Berlin'));
      $time_start = $dt->getTimestamp();
      
?>  
      <tr class=".jqm-content">
        <td>
        <div class="date">
          <div class=".jqm-content .jqm-demos">
            <?=str_replace(" ","&nbsp;",date("D j M,", $time_start)).date(" G:i", $time_start);?>
          </div>
        </div>
        <div class="nation0">
          <div class=".jqm-content nation name">
            <?=$line2["name0"]?><?=(isset($line2["value0"]) ? "&nbsp;<span class=\"value\">(".$line2["value0"].")</span>" : "")?>
          </div>       
        </div>
<?
        if (isset($line2["image0"]))
        { ?>
          <img class="nation" src="/images/<?=$line2["image0"]?>" />
<?
        }?>   
	<div class="goals">
<!--		<?=(isset($line2["goals0"]) ? $line2["goals0"] : "")?>:
		<?=(isset($line2["goals1"]) ? $line2["goals1"] : "")?>-->
        	<span class="goals goals0<?=($line2["finished"]==1? "": " inplay")?>"><?=(isset($line2["goals0"]) ? $line2["goals0"] : "")?></span>
	        <span class="goals<?=($line2["finished"]==1 || $line2["goals0"]==""? "": " inplay")?>">:</span>
	        <span class="goals goals1<?=($line2["finished"]==1? "": " inplay")?>"><?=(isset($line2["goals1"]) ? $line2["goals1"] : "")?></span>
	</div>
<?
        if (isset($line2["image1"]))
        { ?>
          <img class="nation" src="/images/<?=$line2["image1"]?>" />
<?
        }?>      
        <div class="nation1">   
          <div class=".jqm-content nation name">
            <?=$line2["name1"]?><?=(isset($line2["value1"]) ? "&nbsp;<span class=\"value\">(".$line2["value1"].")</span>" : "")?>
          </div>
      </div>
      </td>
    </tr>
<?    
    }
  }
?>
  </tbody>
</table>
