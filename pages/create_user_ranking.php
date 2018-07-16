
<h1>Define your Ranking</h1>
<p>
Drag the nations below into an order such that the strongest is on top and the weakest is at the end. 
The ranking will be saved automatically and you can continue at a later time.
</p>
<p>
  It is beneficial to do it right (and better than the average), as you only then can profit from the "clever clogs' rule" after the group phase.
</p>
<p>
If you don't like insertion sort, but bubble sort (or you're on a mobile device) you can use the following buttons:<br>
<a href="#" class="ui-btn ui-btn-inline ui-icon-arrow-u ui-shadow ui-mini ui-btn-icon-notext  ui-corner-all"></a>/
<a href="#" class="ui-btn ui-btn-inline ui-icon-arrow-d ui-shadow ui-mini ui-btn-icon-notext  ui-corner-all"></a>= Move to top/bottom,
<a href="#" class="ui-btn ui-btn-inline ui-icon-carat-u ui-shadow ui-mini ui-btn-icon-notext  ui-corner-all"></a>/
<a href="#" class="ui-btn ui-btn-inline ui-icon-carat-d ui-shadow ui-mini ui-btn-icon-notext  ui-corner-all"></a>= Move one up/down,
</p>

<!--<a href="#" id="save_user_ranking" data-role="button">Save</a>-->
 
<ul id="sortable" class="sortable">
<?
  include("connect.php");

  $query = 'SELECT * FROM ranking_user '.
    ' JOIN nation ON nation.id = ranking_user.nation_id '.
    ' WHERE user_id='.$_SESSION["user_id"].' ORDER BY rank';
  $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
  
  //echo $query;
  
  while($line = $result->fetch_assoc())
  {
?>  
    <li class="ui-state-default nation" id="nation_<?=$line["nation_id"]?>">
      <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
      <div class="nation abbr"><?=$line["abbreviation"]?></div>
      <img class="nation" src="images/<?=$line["image"]?>" />
      <div class="nation name"><?=$line["name"]?></div>
      
      <a href="#" class="nation ui-btn ui-btn-inline ui-icon-arrow-u ui-shadow ui-mini ui-btn-icon-notext ui-corner-all" onclick="move_top('nation_<?=$line["nation_id"]?>');"></a>
      <a href="#" class="nation ui-btn ui-btn-inline ui-icon-carat-u ui-shadow ui-mini ui-btn-icon-notext ui-corner-all" onclick="move_up('nation_<?=$line["nation_id"]?>');"></a>
      <a href="#" class="nation ui-btn ui-btn-inline ui-icon-carat-d ui-shadow ui-mini ui-btn-icon-notext ui-corner-all" onclick="move_down('nation_<?=$line["nation_id"]?>');"></a>
      <a href="#" class="nation ui-btn ui-btn-inline ui-icon-arrow-d ui-shadow ui-mini ui-btn-icon-notext ui-corner-all" onclick="move_bottom('nation_<?=$line["nation_id"]?>');"></a>
      
    </li>
<?    
  }
?>
</ul>

<!--<a href="#" id="save_user_ranking" data-role="button">Save</a>-->
