function ajx_post(action_type, ajx_data, callback){
  $.ajax({
    type: action_type,
    data: ajx_data,    //{lgid:lgid.val(), lgpw:lgpw.val(), lgset:true}
    success: function(returndata){
      if(!returndata){
        callback("504");
      } else {
        callback(returndata);
      }
    }
  });
}
function post_note(action, page_stage, message){
  $('body').prepend("<div class='post_note_pop' id='pid_"+action+"'>"+ message +"</div>")
  setTimeout(function(){
    $('.post_note_pop').fadeOut(500, function(){
      if(page_stage == "reload"){
        location.reload()
      } else {
        $(this).remove()
      }
    })
  }, 3000)
}

// Predefined vars
$('.mp_bar.archive').click(function(){
  active_user = localStorage.getItem("remID").split(",")[0] // Currently logged in user
  rep_id = $('.refid')[0].innerText;
  postObject = {
    ajp_archive: 1,           // Function name
    ajp_rID: rep_id,          // Report ID
    ajp_rStatus: 3,           // Status Integer to change to
    ajp_byUser: active_user   // Made by this user
  };
  ajx_post("POST", postObject, function(retData){
    if(retData == "100"){
      // Return animation
      // Set animation to X, after 1s, change to Y without transition for pageload
      post_note("100","reload","Felanmälan har arkiverats av <strong>" + active_user + "</strong>");
    } else if (retData == "504"){
      post_note("504", "Något gick fel, prova igen.");
      // Return error, nothing changed or error
    }
  })
})
$('.mp_bar.activate').click(function(){
  active_user = localStorage.getItem("remID").split(",")[0] // Currently logged in user
  rep_id = $('.refid')[0].innerText;
  postObject = {ajp_activate: 1,ajp_rID: rep_id,ajp_rStatus: 0,ajp_byUser: active_user};
  ajx_post("POST", postObject, function(retData){
    if(retData == "100"){
      // Return animation
      // Set animation to X, after 1s, change to Y without transition for pageload
      post_note("100","reload", "Felanmälan har återaktiverats av <strong>" + active_user + "</strong>");
    } else if (retData == "504"){
      post_note("504", "stay", "Något gick fel, prova igen.");
      // Return error, nothing changed or error
    }
  })
})
$('.mp_bar.queue').click(function(){
  active_user = localStorage.getItem("remID").split(",")[0] // Currently logged in user
  rep_id = $('.refid')[0].innerText;
  postObject = {ajp_queue: 1, ajp_rID: rep_id, ajp_byUser: active_user}
  ajx_post("POST", postObject, function(retData){
    if(retData == "100"){
      post_note("100", "reload", rep_id + "har lagts till i <strong>" + active_user+"s</strong> lista.")
    } else if(retData == "303"){
      post_note("303", "reload", "Du har nu köat om denna.")
    } else {
      post_note("504", "stay", "Något gick fel, prova igen.");
    }
  })
})
$('.mp_bar.note').click(function(){
  active_user = localStorage.getItem("remID").split(",")[0] // Currently logged in user
  rep_id = $('.refid')[0].innerText;
  $('.multif__actionbar').addClass('no_z')
  $('.note_container').toggleClass('app_hidden')
  $('.mp_note_cancel').click(function(){
    $('.note_container').addClass('app_hidden')
  })
  $('.mp_note_send').click(function(){
    mp_msg = $('.txt__note')[0].value
    if(mp_msg.length > 0){
      postObject = {ajp_note: 1, ajp_rID: rep_id, ajp_byUser: active_user, ajp_message: mp_msg}
      ajx_post("POST", postObject, function(retData){
        $('.multif__actionbar').removeClass('no_z')
        if(retData == "100"){
          $('.note_container').addClass('app_hidden')
          post_note("100", "reload", "Notis sparad.")
        } else {
          post_note("504", "stay", "Något gick fel, prova igen.")
        }
      })

    }
  })
})
$('.mp_bar.big').click(function(){
  active_user = localStorage.getItem("remID").split(",")[0] // Currently logged in user
  rep_id = $('.refid')[0].innerText;
  $('.mp_popup').removeClass('app_hidden')
  $('.btn_y').click(function(){
    postObject = {ajp_complete: 1, ajp_rID: rep_id, ajp_rStatus: 1}
    ajx_post("POST", postObject, function(retData){
      if(retData == "100"){
        $('.mp_popup').addClass('app_hidden')
        post_note("100", "reload", "Ärende åtgärdat.")
      } else {
        post_note("504", "stay", "Något gick fel, prova igen.")
      }
    })
  })
  $('.btn_n').click(function(){
    $('.mp_popup').addClass('app_hidden')
  })
})
