<?

function compute_current_ranking(&$ranking_current, &$ranking_current_pos, $nation_id=-1, $time_from="0000-00-00 00:00:00", $time_to="2025-01-01 00:00:00")
{

global $mysqli;
  
$query = 'SET @counter = 0, @rank = 0, @last_score = 0;
SELECT sub.id,sub.name,sub.abbreviation,sub.image,sub.score,
(@counter := @counter +1) as counter, 
(@rank := IF (@last_score = sub.score, @rank, @counter)) as rank,
(@last_score := sub.score) AS dummy
FROM
(

SELECT nation.id, nation.name, nation.abbreviation, nation.image,
IF(list0.sum0 IS NULL, 0, list0.sum0) + IF(list1.sum1 IS NULL, 0, list1.sum1) AS score
FROM
nation 
LEFT JOIN
(

SELECT nation.name, nation.id,
SUM(IF(match0.goals0 > match0.goals1, 2*ranking_common01.value, IF(match0.goals0 = match0.goals1, ranking_common01.value, 0))) AS sum0
FROM
nation
JOIN ranking_common ON (ranking_common.nation_id = nation.id)
JOIN `match` AS match0 ON (nation.id = match0.nation0_id)
JOIN `nation` AS nation01 ON(nation01.id = match0.`nation1_id`)
JOIN ranking_common AS ranking_common01 ON (ranking_common01.nation_id = nation01.id)
WHERE
    match0.finished = 1 AND "'.$time_from.'" <= match0.time_start AND match0.time_start <= "'.$time_to.'"
GROUP BY nation.id
) AS list0
ON nation.id = list0.id

LEFT JOIN
(
SELECT nation.name, nation.id,
SUM(IF(match1.goals1 > match1.goals0, 2*ranking_common10.value, IF(match1.goals1 = match1.goals0, ranking_common10.value, 0))) AS sum1
FROM
nation
JOIN ranking_common ON (ranking_common.nation_id = nation.id)
JOIN `match` AS match1 ON (nation.id = match1.nation1_id)
JOIN `nation` AS nation10 ON(nation10.id = match1.`nation0_id`)
JOIN ranking_common AS ranking_common10 ON (ranking_common10.nation_id = nation10.id)
WHERE
    match1.finished = 1 AND "'.$time_from.'" <= match1.time_start AND match1.time_start <= "'.$time_to.'"
GROUP BY nation.id
) As list1
ON nation.id = list1.id
ORDER BY score DESC
    
) sub ';

if ($nation_id != -1)
{
  $query .= " WHERE sub.id = ".$nation_id;
  //echo "query=[<br>".$query."<br>]";
}


//echo "query=[".$query."]";

$mysqli->multi_query($query) or die("error in query [$query]: ".$mysqli->error);
  
/* store first result set */
if ($result = $mysqli->store_result()) 
{
  $result->free();
}
if (!$mysqli->next_result())
{
  echo "Error - current ranking could not be computed.";
}
$result = $mysqli->store_result();


$ranking_current = array();
$ranking_current_pos = array();

for($i = 1; $line = $result->fetch_assoc(); $i++)
{
  $ranking_current[] = $line;
  $ranking_current_pos[$line["id"]] = $i;
}

}

