<?
  session_start();
?>
  <!DOCTYPE html>
<html> 
<head> 
  <title>World Cup Betting Game For Friends of Simulation</title>
	
	<meta name="viewport" content="width=device-width, initial-scale=1" /> 
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="shortcut icon" href="images/favicon.ico" />
	<link rel="icon" href="images/favicon.ico" />
	
  <link rel="stylesheet" href="css/themes/default/jquery.mobile-1.4.5.min.css" />
	<link rel="stylesheet" href="_assets/css/jqm-demos.css" />
  <!--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" />-->
  <!--<link rel="stylesheet" href="css/google_fonts.css" />-->
  <link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet"> 
  <link rel="stylesheet" href="css/style.css" />
	<script src="js/jquery.js"></script>
	<script src="_assets/js/index.js"></script>
	<script src="js/jquery.mobile-1.4.5.min.js"></script>
	<!--<script src="js/jquery-ui.js"></script>-->    <!-- with this the "Create your Ranking" works, without the "Choose nations" works -->
	<script src="js/script.js"></script>
</head> 
<body>
  
<?
  // validate email link
  if (isset($_GET["validate_mail"]))
  {
    $token = $_GET["validate_mail"];
    $user = $_GET["user"];
        
    include("connect.php");
     
    $query = 'SELECT * FROM user WHERE login_name="'.$mysqli->real_escape_string($user).'"';
    $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
    if ($result->num_rows > 0)
    {
      $line = $result->fetch_assoc();
      if ($line["validation_token"] == $token)
      {
        $login = True;
        $_SESSION['user'] = $mysqli->real_escape_string($user);
        $_SESSION['user_id'] = $mysqli->real_escape_string($line["id"]);
            
        $query = 'UPDATE user SET validated=1 WHERE login_name="'.$mysqli->real_escape_string($user).'"';
        $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
        
?>
        <script>
          display_info("Email address validated!", "success");
        </script>
<?        
      } 
    }
  }
  
  //check if logged in
  $logged_in = False;
  if (isset($_SESSION['user']))
  {
    $logged_in = True;
    include_once("update_match_standings.php");
  }
  
  // pages for not logged in
  $pages = ["login", "register", "terms", "matches", "imprint", "rules"];
  $navigation = [
    ["login", "Login"],
    //["register", "Registration"],
    ["matches", "Matches"],
    ["rules", "Rules"],
    ["imprint", "About"]
  ];
  
  // pages for logged in
  if ($logged_in)
  {
    $pages = ["users", "terms", "matches", "rankings", "score", "overview", "logout", "test", "enter_excuse", "imprint", "rules", "substitute_nations"];
    $navigation = [
      ["overview", "Overview"],
      //["create_user_ranking", "Define your ranking"],
      ["substitute_nations", "Substitute nations"],
      ["rankings", "Nation rankings"],
      ["score", "Highscore"],
      //["users", "Users"],
      ["matches", "Matches"],
      ["rules", "Rules"],
      ["logout", "Logout"],
      //["test", "Test"],
      ["imprint", "About"]
    ];
    
    include_once("connect.php");
    
    // check if user already has chosen nations 
    $user_has_chosen_nations = False;
    $query = "SELECT * FROM user_holds_nation WHERE user_id = ".$_SESSION["user_id"];
    $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
    if ($result->num_rows > 0)
    {
      $user_has_chosen_nations = True;
    }
    
    if (!$user_has_chosen_nations)
    {
      $pages = ["users", "terms", "matches", "rankings", "score", "overview", "logout", "test", "choose_nations", "enter_excuse", "imprint", "rules"];
      $navigation = [
        ["overview", "Overview"],
        //["create_user_ranking", "Define your ranking"],
        ["score", "Highscore"],
        //["choose_nations", "Choose nations"],
        ["rankings", "Nation rankings"],
        //["users", "Users"],
        ["matches", "Matches"],
        ["rules", "Rules"],
        ["logout", "Logout"],
        //["test", "Test"],
        ["imprint", "About"]
      ];
    }
    
    // for me
    if (isset($_SESSION['user_id']) && false)
    {
      if ($_SESSION['user_id'] == 1)
      {
        $pages[] = "substitute_nations";
        $navigation[] = ["substitute_nations", "Substitute nations"];
        $navigation[] = ["choose_nations", "Choose nations"];
        $navigation[] = ["rankings", "Nation rankings"];
        $navigation[] = ["score", "High score"];
      }
    }
  }
    
  
  foreach ($pages as $page)
  {
?>
  
  <div data-role="page" id="<?=$page?>" class="jqm-demos jqm-home">

    <!-- header -->
    <div data-role="header" class="jqm-header header-top">
      <div class="header left">World Cup<br>Betting Game<br>2018</div>
      <img src="images/ball.jpg" alt="So'n ball" class="header">
      <div class="header right">for<br>Friends of <br>Simulation</div>
      <a href="#" class="jqm-navmenu-link ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bars ui-nodisc-icon ui-alt-icon ui-btn-left">Menu</a>
    </div><!-- /header -->

    <!-- content -->
    <div role="main" class="ui-content jqm-content">
      <div name="infobox" class="infobox">TESTINFOBOX</div>
<?
      include_once("pages/".$page.".php");
?>      

    </div><!-- /content -->
    
    <!-- navigation panel -->
    <div data-role="panel" class="jqm-navmenu-panel" data-position="left" data-display="overlay" data-theme="a">
      <ul class="jqm-list ui-alt-icon ui-nodisc-icon">
<?
      foreach ($navigation as $page)
      {  
?>                
        <!--<li data-icon="home"><a href=".">Home</a></li>-->
        <li><a href="#<?=$page[0]?>"><?=$page[1]?></a></li>
        <!--<li data-role="collapsible" data-enhanced="true" data-collapsed-icon="carat-d" data-expanded-icon="carat-u" data-iconpos="right" data-inset="false" class="ui-collapsible ui-collapsible-themed-content ui-collapsible-collapsed">
          <h3 class="ui-collapsible-heading ui-collapsible-heading-collapsed">
            <a href="#" class="ui-collapsible-heading-toggle ui-btn ui-btn-icon-right ui-btn-inherit ui-icon-carat-d">
                Checkboxradio widget<span class="ui-collapsible-heading-status"> click to expand contents</span>
            </a>
          </h3>
          <div class="ui-collapsible-content ui-body-inherit ui-collapsible-content-collapsed" aria-hidden="true">
            <ul>
              <li><a href="../checkboxradio-checkbox/" data-ajax="false">Checkboxes</a></li>
              <li><a href="../checkboxradio-radio/" data-ajax="false">Radio buttons</a></li>
            </ul>
          </div>
        </li>-->
<?    }?>        

      </ul>
		</div><!-- /panel -->

  </div><!-- /page -->
<?
}
?>
</body>
</html>
