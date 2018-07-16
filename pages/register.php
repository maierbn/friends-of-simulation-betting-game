
<h1>Register</h1>
<p>If you already have an account, go to the <a href="#login">Login</a> page!
</p>
<form id="registerform" method="post">
  <input type="hidden" name="action" value="register"/>
  <table>
    <tr>
      <td>Login name:</td>
      <td><input type="text" name="user" value=""/></td>
    </tr>
    <tr>
      <td>Displayed name:</td>
      <td><input type="text" name="displayed_name" value=""/></td>
    </tr>
    <tr>
      <td>Email address:</td>
      <td><input type="text" name="mail" value=""/></td>
    </tr>
    <tr>
      <td>Password:</td>
      <td><input type="password" name="pass" value=""/></td>
    </tr>
    <tr>
      <td>I have read and accept <br>the <a href="#terms" id="term-link">Term & Conditions</a>:</td>
      <td>
        <label>
          <input type="checkbox" name="terms" value="1"/>
        </label>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <button type="submit">Register</button>
      </td>
    </tr>
  </table>
</form>
