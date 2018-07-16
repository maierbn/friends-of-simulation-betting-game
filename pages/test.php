

<ul id="sortable02">
  
    <li class="ui-state-default nation" id="nation_1">
      <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
      <span class="nation abbr">ARG</span>
      <img class="nation" src="images/arg.png" />
      <span class="nation">Argentina</span>
    </li>
  
    <li class="ui-state-default nation" id="nation_2">
      <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
      <span class="nation abbr">AUS</span>
      <img class="nation" src="images/aus.png" />
      <span class="nation">Australia</span>
    </li>
  
    <li class="ui-state-default nation" id="nation_3">
      <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
      <span class="nation abbr">BEL</span>
      <img class="nation" src="images/bel.png" />
      <span class="nation">Belgium</span>
    </li>
  
</ul>

<form>
    <label>
        <input type="checkbox" name="checkbox-0 ">Check me
    </label>
</form>

<form>
    <label for="flip-checkbox-2">Flip toggle switch checkbox:</label>
    <input type="checkbox" data-role="flipswitch" name="flip-checkbox-2" id="flip-checkbox-2" data-on-text="Light" data-off-text="Dark" data-wrapper-class="custom-label-flipswitch">
</form>

<script>
$('[type="radio"]').checkboxradio();
    $('[type="radio"]').checkboxradio();
    $('[type="checkbox"]').checkboxradio();
    // Select another radio element
    $("input[type='radio']").eq(0).attr("checked",false).checkboxradio("refresh");
    $("input[type='radio']").eq(1).attr("checked",true).checkboxradio("refresh");
</script>

<form>
    <fieldset data-role="controlgroup">
        <input type="checkbox" name="checkbox-h-2a" id="checkbox-h-2a">
        <label for="checkbox-h-2a">One</label>
        <input type="checkbox" name="checkbox-h-2b" id="checkbox-h-2b">
        <label for="checkbox-h-2b">Two</label>
        <input type="checkbox" name="checkbox-h-2c" id="checkbox-h-2c">
        <label for="checkbox-h-2c">Three</label>
    </fieldset>
</form>
