
// login form
$(function() {
  $("#loginform").submit(function(e)
  {
    $.ajax({
           type: "POST",
           url: "core.php",
           data: $("#loginform").serialize(), // serializes the form's elements.
           success: function(raw_data)
           {
             //alert("parseJSON("+raw_data+")\n");
             data = $.parseJSON(raw_data);              
//             alert(raw_data+"\n"+data);
             if (data["status"] == "login")
             {
               location.assign("#overview");
               location.reload();
             }
             display_info(data["msg"],data["status"]);
           }
         });

    e.preventDefault(); // avoid to execute the actual submit of the form.
  });
  
  start_read_terms = 0;
  end_read_terms = 0;
  $("#term-link").click(function(e)
  {
    start_read_terms = Date.now();
  });
  
  $("#registerform").submit(function(e)
  {
    milliseconds = 0;
    if (end_read_terms == 0)
    {
      end_read_terms = Date.now();
    }
    if (start_read_terms != 0 && end_read_terms != 0)
    {
      milliseconds = Date.now() - start_read_terms;
    }
    data = $("#registerform").serialize()+"&read_terms="+milliseconds;
//    alert("POST data: "+data);
    $.ajax({
            type: "POST",
            url: "core.php",
            data: data, // serializes the form's elements.
            success: function(data)
            {
              //alert("data: "+data);
              data = $.parseJSON(data); 
              display_info(data["msg"],data["status"]);
              if (data["status"] == "login")
              {                
                location.assign("#overview");
                location.reload();
              }
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
              display_info("Registration failed. "+textStatus+" "+errorThrown); // show response from the php script.
            }
         });

    e.preventDefault(); // avoid to execute the actual submit of the form.
  });
  
  $("#logout").click(function(e)
  {
    $.ajax({
      type: "POST",
      url: "core.php",
      data: {"action": "logout"},
      success: function(raw_data)
      {
        //alert("parseJSON("+raw_data+")\n");
        data = $.parseJSON(raw_data); 
        if (data["status"] == "success")
        {
          location.assign("#login");
          location.reload();
        }
      },
      error: function(jqXHR, textStatus, errorThrown)
      {
        display_info("Logout failed. "+textStatus+" "+errorThrown); // show response from the php script.
      }
      });
  });
  
  if (typeof $("#sortable").sortable === 'function')
  {
    $("#sortable").sortable({
      update: save_user_ranking
    });

    $("#sortable").disableSelection();
  }
  
  $("#save_chosen_nations").submit(function(e)
  {
    $.ajax({
            type: "POST",
            url: "core.php",
            data: {
              "action": "save_chosen_nations",
              "nation-1": $("#nation-1").val(), 
              "nation-2": $("#nation-2").val(), 
              "nation-3": $("#nation-3").val(),
              "nation-4": $("#nation-4").val(),
              "sum": $("#sum").val()
            },
            success: function(raw_data)
            {
              //alert("parseJSON("+raw_data+")\n");
              data = $.parseJSON(raw_data);
              if (data["status"] == "excuse")
              {
                location.assign("#enter_excuse");
              }
              display_info(data["msg"],data["status"]);
              if (data["status"] == "success")
              {
                // load overview page after 3000 sec.
                setTimeout(function() 
                { 
                  location.assign("#overview");
                  location.reload();
                }, 3000);
              }
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
              display_info("Choosing nations failed. "+textStatus+" "+errorThrown); // show response from the php script.
            }
         });

    e.preventDefault(); // avoid to execute the actual submit of the form.
  });
  $("#excuse-form").submit(function(e)
  {
    $.ajax({
            type: "POST",
            url: "core.php",
            data: $("#excuse-form").serialize(),
            success: function(raw_data)
            {
//              alert("parseJSON("+raw_data+")\n");
              data = $.parseJSON(raw_data); 
              display_info(data["msg"],data["status"]);
              //alert("data: "+data);
              // load overview page after 2000 sec.
              setTimeout(function() 
              {
                location.assign("#overview");
                location.reload();
              }, 2000);
              
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
              display_info("Saving the excuse failed. "+textStatus+" "+errorThrown); // show response from the php script.
            }
         });

    e.preventDefault(); // avoid to execute the actual submit of the form.
  });
  
  $("#substitute_nations").submit(function(e)
  {
    data = "nation-get="+$('#nation-get').val()+"&nation-drop="+$('#nation-drop').val()+"&action=substitute_nation";
    //alert(data);
    $.ajax({
            type: "POST",
            url: "core.php",
            data: data,
            success: function(raw_data)
            {
              //alert("parseJSON("+raw_data+")\n");
              data = $.parseJSON(raw_data); 
              display_info(data["msg"],data["status"]);
              //alert("data: "+data);
              // load overview page after 1000 sec.
              setTimeout(function() 
              {
                if (data["status"] == "success")
                {
                  location.assign("#overview");
                  location.reload();
                }
              }, 1000);
              
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
              display_info("Substituting nations failed. "+textStatus+" "+errorThrown); // show response from the php script.
            }
         });

    e.preventDefault(); // avoid to execute the actual submit of the form.
  });
  
  
  $("#other-user-id").change(function(e)
  {
    user_id = $("#other-user-id").val();
    setTimeout(function() 
    {
      if (user_id == -1)
      {
        location.assign("#rankings");
      }
      else
      {
        location.assign("?user_id="+user_id+"#rankings");
      }
      //location.reload();
    }, 10);
  });
  
  display_user_ranking();
  
});

function move_top(id)
{
  var nation = $("#"+id).remove();
  $("#sortable").prepend(nation);
    save_user_ranking(null, null);
}

function move_bottom(id)
{
  var nation = $("#"+id).remove();
  $("#sortable").append(nation);
    save_user_ranking(null, null);
}

function move_up(id)
{
  var nation = $("#"+id);
  var previous = nation[0].previousElementSibling;
  if (previous != null)
  {
    nation.remove();
    $(nation).insertBefore(previous);
    save_user_ranking(null, null);
  }
}

function move_down(id)
{
  var nation = $("#"+id);
  var next = nation[0].nextElementSibling;
  if (next != null)
  {
    nation.remove();
    $(nation).insertAfter(next);
    save_user_ranking(null, null);
  }
}

function save_user_ranking(event, ui)
{
  // on rearranging the ranking
  $.ajax({
    type: "POST",
    url: "core.php",
    data: $("#sortable").sortable('serialize')+"&action=save_user_ranking",
    success: function(raw_data)
    {
      //alert("parseJSON("+raw_data+")\n");
      data = $.parseJSON(raw_data); 
      //display_info(data["msg"],data["status"]);
    },
    error: function(jqXHR, textStatus, errorThrown)
    {
      display_info("Could not save ranking. "+textStatus+" "+errorThrown); // show response from the php script.
    }
    });
}

function display_common_ranking()
{
  if ($('#checkbox-common-ranking').is(':checked'))
  {
    $('#ranking-common').show();
  }
  else
  {
    $('#ranking-common').hide();
  }
}

function display_own_ranking()
{
  if ($('#checkbox-own-ranking').is(':checked'))
  {
    $('#ranking-own').show();
  }
  else
  {
    $('#ranking-own').hide();
  }

}

function display_current_ranking()
{
  if ($('#checkbox-current-ranking').is(':checked'))
  {
    $('#ranking-current').show();
  }
  else
  {
    $('#ranking-current').hide();
  }
}

function display_user_ranking()
{
  if ($('#checkbox-user-ranking').is(':checked'))
  {
    $('#ranking-user').show();
  }
  else
  {
    $('#ranking-user').hide();
  }
}

function display_buyable()
{
  if ($('#checkbox-buyable').is(':checked'))
  {
    $('.buyable').css("background-color","#bdf0ca");
  }
  else
  {
    $('.buyable').css("background-color","white");
  }
}

function display_info(msg,status)
{
  //alert(msg);
  if (msg == "")
    return;
  if (status == "fail" || status == "failed" || status == "retry")
  {
    $('[name=infobox]').addClass('failed')
  }
  else
  {
    $('[name=infobox]').removeClass('failed')
  }
  $('[name=infobox]').html(msg);
  $('[name=infobox]').show().delay(7000).fadeOut(1000);
}

function update_new_score()
{
  var new_score = $('#new_score-'+$('#nation-get').val()).html();
  //alert($('#nation-get').val()
  $('#new_score').html(new_score);
  $('#new_score_text').show();
}








