<!--<h1>Overview</h1>-->

<!--
<p>
  <b>Update 14.6.: The tournament has started and registration and bets are closed now.</b>
</p>-->
<!--
<p>
  Update 10.6.: Now log in and make a wise choice of your 4 teams!
</p>
<p>
  Update 8.6.: If anyone had problems registering with their mail address being rejected, now the filter is weakened ("off") so it should work now.
</p>
<p>
This is the betting game for the soccer World Cup 2018 for all "friends of simulation". 
Everyone bets on 4 national teams. Every team has a "value", the expected best (Germany) has the value 32, the next (Brazil) 31 and so on. You can only choose 4 teams such that the sum of their values is &le; 60. For example you can't choose Germany, Brazil, Spain and France at once, instead you'll have to also pick some lower rated nations.
</p>
<p>
If one of your 4 teams wins a match you'll get twice the points of the opponent. If your team draws a match, you'll get the points of the opponent. E.g. if you have choosen Germany and they win against Mexico, which has a value of 20, then you'll get 40 points from that match.
</p>
<p>
The value of each national team was not prescribed but rather was determined as a compromise between the participants' expectations, in the last week. Now is the time to choose the 4 nations, until the opening match starts on Thursday. Registration is still open.
</p>
<p>
The exact rules can be found <a href="#rules">here</a>.
</p>
-->
<p>
The betting game is over now. Congratulations to the winner ___ and to all who enjoyed participating.
</p>
<p>
The source code is now public on <a href="https://github.com/maierbn/friends-of-simulation-betting-game">github</a>, in case you need it sometime.
</p>

<h1>Login</h1>
<!--<p>If you don't have an account yet, please <a href="#register">Register</a>.
</p>-->
<form id="loginform" method="post">
  <input type="hidden" name="action" value="login"/>
  <table>
    <tr>
      <td>Name:</td>
      <td><input type="text" name="user" value=""/></td>
    </tr>
    <tr>
      <td>Password:</td>
      <td><input type="password" name="pass" value=""/></td>
    </tr>
    <tr>
      <td colspan="2">
        <button type="submit">Login</button>
      </td>
    </tr>
  </table>
</form>
