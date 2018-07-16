<?

  $uri = 'http://api.football-data.org/v1/competitions/467/fixtures/';
  $reqPrefs['http']['method'] = 'GET';
  $reqPrefs['http']['header'] = 'X-Auth-Token: c4727505065a4690a1e835455385e436';
  $stream_context = stream_context_create($reqPrefs);
  $response = file_get_contents($uri, false, $stream_context);
  $football_data = json_decode($response);
  
  
  $output = false;
  
  if ($output)
  {
    echo "<pre>";
    print_r($football_data);
    echo "</pre>";
  }
  
  include_once("connect.php");
  
  $query = 'SELECT `match`.id match_id, nation0.name name0, nation1.name name1, finished FROM `match` JOIN nation nation0 ON (nation0_id=nation0.id) JOIN nation nation1 ON (nation1_id=nation1.id) WHERE finished = 0';
  $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
  
  $previous_fixture_index = 0;
  while ($line = $result->fetch_assoc())
  {
    $name0 = str_replace("&nbsp;"," ",$line["name0"]);
    $name1 = str_replace("&nbsp;"," ",$line["name1"]);
    if($name0 == "Korea")
      $name0 = "Korea Republic";
    if($name1 == "Korea")
      $name1 = "Korea Republic";
    
    $found = false;
    
    $first = true;
    $fixture_index = $previous_fixture_index-1;
    // find out entry in football_data
    for ($i = 0; $i < $football_data->count; $i++)
    {
      $fixture_index++;
      if ($fixture_index == $football_data->count)
        $fixture_index = 0;
      $first = false;
      
      
      if ($output) echo "<br>fixture_index: ".$fixture_index."<br>";
      $dataset = $football_data->fixtures[$fixture_index];
      
      if ($output) echo " --[".$dataset->homeTeamName."]?=[".$name0."](".(int)($dataset->homeTeamName == $name0)."), [".$dataset->awayTeamName."]?=[".$name1."](".(int)($dataset->awayTeamName == $name1).")<br>";
      
      if ($dataset->homeTeamName == $name0 && $dataset->awayTeamName == $name1)
      {        
        if ($output) echo "found<br>";
        if ($output) echo $name0." - ".$name1.": ".$dataset->result->goalsHomeTeam." : ".$dataset->result->goalsAwayTeam."<br>\n";
        $found = true;
        $previous_fixture_index = $fixture_index+1;
        
        if ($output) echo "status: ".$dataset->status.", ".$dataset->result->goalsHomeTeam."(".(int)($dataset->result->goalsHomeTeam!="")."), ".$dataset->result->goalsAwayTeam."(".(int)($dataset->result->goalsAwayTeam!="").")";
        if (property_exists($dataset->result,"goalsHomeTeam") && property_exists($dataset->result,"goalsAwayTeam"))
        {
          $goalsAwayTeam = (int)($dataset->result->goalsAwayTeam);
          $goalsHomeTeam = (int)($dataset->result->goalsHomeTeam);
          if (property_exists($dataset->result,"penaltyShootout") )
          {
            if($output) {
              echo "penaltyShootout: <pre>";
              print_r($dataset->result);
              echo "</pre>, old: $goalsHomeTeam - $goalsAwayTeam<br>";
            }
            
            $goalsAwayTeam += (int)($dataset->result->penaltyShootout->goalsAwayTeam);
            $goalsHomeTeam += (int)($dataset->result->penaltyShootout->goalsHomeTeam);
            if ($output) echo "new: $goalsHomeTeam - $goalsAwayTeam <br>";
          }  
          else if ($output)
          {
            echo "no penaltyShootout: <pre>";
            print_r($dataset->result);
            echo "</pre><br>";
          }
          
          if ($dataset->status == "IN_PLAY")
          {
            $query = 'UPDATE `match` SET goals0='.$mysqli->real_escape_string($goalsHomeTeam).', goals1='.$mysqli->real_escape_string($goalsAwayTeam).' WHERE id='.$line["match_id"];
            if ($output) echo "$query: [".$query."]";
            $result2 = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);  
          }
          else if ($dataset->status == "FINISHED")
          {
            $query = 'UPDATE `match` SET goals0='.$mysqli->real_escape_string($goalsHomeTeam).', goals1='.$mysqli->real_escape_string($goalsAwayTeam).',finished=1 WHERE id='.$line["match_id"];
            if ($output) echo "$query: [".$query."]";
            $result2 = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);  
          }
        }
        break;
      }
    }
    
    if (!$found)
    {
      if ($output) echo "not found: ".$name0." - ".$name1."<br>";
    }
  }
  
  

?>
