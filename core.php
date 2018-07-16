<?php
session_start();

//print_r($_POST);

// parse action
if (!isset($_POST["action"]))
  $action = "none";
else 
  $action = $_POST["action"];

include("connect.php");

$msg = "";
$status = "";

if ($action != "login")
{
  $status = "fail";
  $msg = "The game is over now, no more actions are allowed!";
  $action = "";
}

// perform action
if ($action == "login")
{
  $query = 'SELECT * FROM user WHERE login_name="'.$mysqli->real_escape_string($_POST["user"]).'"';
  $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
  
  // if there was no match in the database
  if ($result->num_rows == 0)
  {
    // try with displayed name    
    $query = 'SELECT * FROM user WHERE displayed_name="'.$mysqli->real_escape_string($_POST["user"]).'"';
    $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
    
    if ($result->num_rows == 0)
    {
      $msg = 'User "'.$mysqli->real_escape_string($_POST["user"]).'" does not exist';
      $status = "retry";
    }
    else 
    {
      $status = "login";
    }
  }
  else 
  {
    $status = "login";
  }
  
  if ($status == "login")
  {
    $line = $result->fetch_assoc();
    
    if (password_verify ($_POST["pass"], $line["password"]))
    {
      $status = "login";
      $_SESSION['user'] = $mysqli->real_escape_string($_POST["user"]);
      $_SESSION['user_id'] = $mysqli->real_escape_string($line["id"]);
    }
    else
    {
      $msg = 'Password is wrong.';
      $status = "retry";
    }
  }
  
  $result->close();
}
else if ($action == "register")
{
  
  $msg = "Registration is already closed";
  $status = "retry";
  
  if (false)
  {
  
    $user = $_POST["user"];
    if (strlen($user) == 0)
    {
      $msg = "Please enter a user name";
      $status = "retry";
    }
    else
    {
      $query = 'SELECT * FROM user WHERE login_name="'.$mysqli->real_escape_string($user).'"';
      $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
      
      // if there was no match in the database
      if ($result->num_rows == 0)
      {
        $msg = "";
        $password = $_POST["pass"];
        if (strlen($password) < 3)
        {
          $msg .= "Password is too short. ";
          $status = "retry";
        }
        
        $displayed_name = $_POST["displayed_name"];
        if (strlen($displayed_name) == 0)
        {
          $msg .= "Please enter a displayed name. ";
          $status = "retry";
        }
        
        $mail = $_POST["mail"];
        if (strlen($mail) == 0)
        {
          $msg .= "Please enter a valid email address. ";
          $status = "retry";
        }
        /*else if(!filter_var($mail, FILTER_VALIDATE_EMAIL))
        {
          $msg .= "Email \"".$mail."\" is invalid, please enter a valid email address. ";
          $status = "retry";
        }*/
        
        if (!isset($_POST["terms"]))
        {
          $msg .= "Please read and accept the Terms & Conditions. ";
          $status = "retry";
        }
        
        if ($status != "retry")
        {
          $milliseconds = $_POST["read_terms"]/1000.0;
          $validation_token = bin2hex(openssl_random_pseudo_bytes(10));
          
          $query = 'INSERT INTO user(login_name, displayed_name, password, duration_terms, email_address, validation_token) '.
          'VALUES("'.$mysqli->real_escape_string($user).'", "'
          .$mysqli->real_escape_string($displayed_name).'", "'
          .password_hash($password, PASSWORD_DEFAULT).'", '
          .($milliseconds).', "'
          .$mysqli->real_escape_string($mail).'", "'
          .$validation_token.'")';
          $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
          $user_id = $mysqli->insert_id;
          
          // get nations
          $query = 'SELECT * FROM nation ORDER BY name';
          $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
          
          // insert standard ranking_user
          // alphabetical ranking
          /*$query = 'INSERT INTO ranking_user (user_id, rank, nation_id) VALUES ';
          
          for($i=0; $line = $result->fetch_assoc(); $i++)
          {
            if ($i != 0)
            { 
              $query .= ', ';
            }
            $first = False;
            $query .= '('.$user_id.','.$i.','.$line["id"].')';
          }
          $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
          */
          
          // common ranking
          $ranking = [13, 4, 12, 11, 10, 1, 26, 7, 3, 25, 8, 33, 21, 15, 5, 27, 30, 31, 2, 17, 22, 24, 9, 28, 6, 29, 23, 32, 18, 14, 20, 16, 19];
          $query = 'INSERT INTO ranking_user (user_id, rank, nation_id) VALUES ';
          
          for($i=0; $i < 33; $i++)
          {
            if ($i != 0)
            { 
              $query .= ', ';
            }
            $first = False;
            $query .= '('.$user_id.','.$i.','.$ranking[$i].')';
          }
          $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
          
          
          // send validation mail
          $to = $mail;
          $subject = 'Betting game email validation';
          $message = "<html><body>Hello ".$mysqli->real_escape_string($displayed_name).",<br>".
            "<br>You have registered for the World Cup Betting Game for Friends of Simulation. ".
            "Please validate your e-mail address by clicking on ".
            "<a href='https://friends-of-simulation.org/index.php?user=".$mysqli->real_escape_string($user)."&validate_mail=".$validation_token."#overview'>Validate Email Address</a>.".
            "<br><br>Thank you!</body></html>";
          
          $headers = 'From: info@friends-of-simulation.org' . "\r\n" .
              'Reply-To: benjamin.maier@ipvs.uni-stuttgart.de' . "\r\n" .
              'X-Mailer: PHP/' . phpversion(). "\r\n" .
              'MIME-Version: 1.0'. "\r\n" .
              'Content-type: text/html; charset=iso-8859-1';

          if (mail($to, $subject, $message, $headers))
          {
            $msg = "Please click the link in the validation email which was sent to ".$mail.".";
            $msg = "Registration successful, you are now logged in.";
            $status = "login";
                
            $_SESSION['user'] = $mysqli->real_escape_string($user);
            $_SESSION['user_id'] = $user_id;
          }
          else 
          {
            $msg = "Sending validation email to $mail failed. (You can login anyway.)";
            $status = "failed";
          }
          //$msg .= " Headers: ".$headers;
        }
      }
      else 
      {
        $msg = 'User name "'.$mysqli->real_escape_string($user).'" is already in use.';
        $status = "retry";
      }
    }
  }
}
else if ($action == "logout")
{
  session_destroy();
  $status = "success";
  $msg = "logged out";
}
else if ($action == "save_user_ranking")
{
  //print_r($_POST);
  
  $nation = $_POST["nation"];
  $query = "";
  foreach ($nation as $rank => $nation_id)
  {
    $query .= 'UPDATE ranking_user SET rank="'.$mysqli->real_escape_string($rank).
      '" WHERE nation_id="'.$mysqli->real_escape_string($nation_id).'" && user_id='.$_SESSION["user_id"].'; ';
  }
  $mysqli->multi_query($query) or die("error in multi query [$query]: ".$mysqli->error);
        
  $msg = "query: [$query]";
  $msg = "Ranking saved";
  $status = "success";
}
else if ($action == "save_chosen_nations")
{
  $msg = "Bets are closed.";
  $status = "failed";
    
  if (false)
  {
    // check if user does not already have nations 
    $query = "SELECT * FROM user_holds_nation WHERE user_id = ".$_SESSION["user_id"];
    $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
    if ($result->num_rows > 0)
    {
      $msg = "You already have selected your nations.";
      $status = "failed";
    }
    else
    {
      
      $nation1 = (int)($_POST["nation-1"]);
      $nation2 = (int)($_POST["nation-2"]);
      $nation3 = (int)($_POST["nation-3"]);
      $nation4 = (int)($_POST["nation-4"]);
      $sum = (int)($_POST["sum"]);
      $msg = "";
      if ($nation1 == -1)
      {
        $msg .= "You have to select 4 nations!";
        $status = "failed";
      }
      else if ($nation2 == -1)
      {
        $msg .= "You have to select 4 nations!";
        $status = "failed";
      }
      else if ($nation3 == -1)
      {
        $msg .= "You have to select 4 nations!";
        $status = "failed";
      }
      else if ($nation4 == -1)
      {
        $msg .= "You have to select 4 nations!";
        $status = "failed";
      }
      else if ($nation1 == $nation2 || $nation1 == $nation3 || $nation1 == $nation4 || $nation2 == $nation3 || $nation2 == $nation4 || $nation3 == $nation4)
      {
        $msg .= "You have to select 4 different nations!";
        $status = "failed";
      }
      else 
      {
        
        $nation1_value = 0;
        $nation2_value = 0;
        $nation3_value = 0;
        $nation4_value = 0;
        $query = 'SELECT * FROM ranking_common WHERE nation_id = '.$mysqli->real_escape_string($nation1);
        $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
        if ($line = $result->fetch_assoc())
        {
          $nation1_value = $line["value"];
        }
        else 
        {
          $msg .= "Value could not be found for Nation 1!";
          $status = "failed";
        }
        
        $query = 'SELECT * FROM ranking_common WHERE nation_id = '.$mysqli->real_escape_string($nation2);
        $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
        if ($line = $result->fetch_assoc())
        {
          $nation2_value = $line["value"];
        }
        else 
        {
          $msg .= "Value could not be found for Nation 2!";
          $status = "failed";
        }
        
        $query = 'SELECT * FROM ranking_common WHERE nation_id = '.$mysqli->real_escape_string($nation3);
        $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
        if ($line = $result->fetch_assoc())
        {
          $nation3_value = $line["value"];
        }
        else 
        {
          $msg .= "Value could not be found for Nation 3!";
          $status = "failed";
        }
        
        $query = 'SELECT * FROM ranking_common WHERE nation_id = '.$mysqli->real_escape_string($nation4);
        $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
        if ($line = $result->fetch_assoc())
        {
          $nation4_value = $line["value"];
        }
        else 
        {
          $msg .= "Value could not be found for Nation 1!";
          $status = "failed";
        }
        
        $real_sum = $nation1_value + $nation2_value + $nation3_value + $nation4_value;
        if ($real_sum <= 60)
        {
          if ($sum == 0)
          {
            $msg = "You didn't calculate the sum of your nations, it is $real_sum, which is <= 60. Your selection is accepted anyway.";
            $status = "success";
          }
          else if ($sum == $real_sum)
          {
            $msg = "You correctly calculated the sum of your nations, $real_sum, and it is <= 60. Your selection is accepted.";
            $status = "success";
          }
          else
          {
            $msg = "Well, you miscalculated the sum of your nations, is would have been $real_sum, not $sum. It is <= 60 anyways, so your selection is accepted.";
            $status = "success";
          }
        }
        else if ($real_sum >= 61)
        {
          if ($sum == 0)
          {
            $msg = "You didn't calculate the sum of your nations. Do you know what you're doing here?";
            $status = "failed";
          }
          else if ($sum == 60 && $real_sum == 61)
          {
            $status = "excuse";
          }
          else if ($sum < $real_sum-1)
          {
            $msg = "You underestimated the sum of your nations very badly, it is far away from being right.";
            $status = "failed";
          }
          else if ($sum == $real_sum-1 || $sum == $real_sum+2)
          {
            $msg = "You miscalculated the sum of your nations moderately.";
            $status = "failed";
          }
          else if ($sum == $real_sum)
          {
            $msg = "Your calculated sum of your nations is $sum, but the rules state that is has to be <= 60.";
            $status = "failed";
          }
          else if ($sum > $real_sum)
          {
            $msg = "I'm sorry to tell you that you have miscalculated ($sum != $real_sum) the sum of your nations. Better luck next time.";
            $status = "failed";
          }
        }
      }
    }
    
    if ($status == "success" || $status == "excuse")
    {
      $query = "INSERT INTO user_holds_nation (user_id, nation_id, time_from, time_to, payed) ".
        " VALUES ".
        "('".$_SESSION["user_id"]."', '".$mysqli->real_escape_string($nation1).
          "', CURRENT_TIMESTAMP, '2020-01-01 00:00:00', 0), ".
        "('".$_SESSION["user_id"]."', '".$mysqli->real_escape_string($nation2).
          "', CURRENT_TIMESTAMP, '2020-01-01 00:00:00', 0), ".
        "('".$_SESSION["user_id"]."', '".$mysqli->real_escape_string($nation3).
          "', CURRENT_TIMESTAMP, '2020-01-01 00:00:00', 0), ".
        "('".$_SESSION["user_id"]."', '".$mysqli->real_escape_string($nation4).
          "', CURRENT_TIMESTAMP, '2020-01-01 00:00:00', 0)";
      $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
    }
  }
}
else if($action == "save-excuse")
{
  $query = "UPDATE user SET excuse='".$mysqli->real_escape_string($_POST["excuse"])."' WHERE id='".$_SESSION["user_id"]."'";
  $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
  $status = "success";
  $msg = "Excuse noted.";
  
  if (strpos($mysqli->real_escape_string($_POST["excuse"]),"spline") !== false || strpos($mysqli->real_escape_string($_POST["excuse"]),"Spline") !== false)
    $msg = "Excuse accepted, because it contains Splines.";
  
}
else if($action == "substitute_nation")
{
  $nation_drop = (int)($mysqli->real_escape_string($_POST["nation-drop"]));
  $nation_get = (int)($mysqli->real_escape_string($_POST["nation-get"]));
  
  if ($nation_drop == -1)
  {
    $status = "fail";
    $msg = "Please select a nation to drop.";
  }
  else if ($nation_get == -1)
  {
    $status = "fail";
    $msg = "Please select a nation to get.";
  }
  else 
  {
    
    // check if user has the nation to drop
    $user_has_nation = false;
    $query = 'SELECT user_holds_nation.id uhn_id, nation.id id FROM user_holds_nation JOIN nation ON (nation.id = nation_id) WHERE user_id='.$_SESSION["user_id"].' AND time_from < CURRENT_TIMESTAMP AND time_to >= CURRENT_TIMESTAMP';
    $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
    
    
    $user_holds_nation_id = -1;
    while($line = $result->fetch_assoc())
    {
      if ($line["id"] == $nation_drop)
      {
        $user_holds_nation_id = $line["uhn_id"];
        $user_has_nation = true;
        break;
      }
    }
    
    if (!$user_has_nation)
    {
      $status = "fail";
      $msg = "You do not currently have the nation you tried to drop.";
    }
    else 
    {
      $query = "SELECT nation0.name nation0_name, nation1.name nation1_name, time_start FROM `match` JOIN nation nation0 ON (nation0.id = nation0_id) JOIN nation nation1 ON (nation1.id = nation1_id) WHERE time_start < CURRENT_TIMESTAMP AND finished = 0";
      $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
      
      if ($result->num_rows > 0)
      {
        $status = "fail";
        $msg = "It is currently not possible to substitute nations because matches are not finished yet, e.g. ".$line["nation0_name"]." vs. ".$line["nation1_name"].", started ".date("d/m H:i",strtotime($line["time_start"]));
      }
      else 
      {
        // check if user can buy the nation
        
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

        // parse ranking current 
        include_once("compute_current_ranking.php");
        $ranking_current_pos = array();
        $ranking_current = array();
        compute_current_ranking($ranking_current, $ranking_current_pos);

        $user_can_buy_nation = $ranking_current_pos[$nation_get] < $ranking_common_pos[$nation_get] && $ranking_own_pos[$nation_get] < $ranking_common_pos[$nation_get];
        
        if (!$user_can_buy_nation)
        {
          $status = "fail";
          $msg = "You can't get the nation you tried to get. Current ranking: #".$ranking_current_pos[$nation_get].", common ranking: #".$ranking_common_pos[$nation_get].", own ranking: #".$ranking_own_pos[$nation_get];
        }
        else 
        {
          $cost = $ranking_current[$ranking_current_pos[$nation_get]-1]["score"];
          
          $query = "UPDATE user_holds_nation SET time_to = CURRENT_TIMESTAMP WHERE id='$user_holds_nation_id'";
          $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
          
          $query = 'INSERT INTO user_holds_nation (user_id, nation_id, time_from, time_to, payed) '.
            'VALUES ("'.$_SESSION["user_id"].'", "'.$mysqli->real_escape_string($nation_get).'", CURRENT_TIMESTAMP, "2020-01-01 00:00:00", "'.$cost.'")';
          $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
          
          $status = "success";
          $msg = "Exchange successful.";
        }
      }
    }
  }
}

$return_string = array("msg" => $msg, "status" => $status);
die(json_encode($return_string));

?>
