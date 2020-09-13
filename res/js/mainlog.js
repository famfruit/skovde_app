
lgid = $('.lgid')
lgpw = $('.lgpw')
lgbtn = $('.lgbtn')
lsr = localStorage.getItem("remID")
if(lsr){
  lsr = lsr.split(",")
  lgid.val(lsr[0])
  lgpw.val(lsr[1])
}
function hightlight_inputs(targets, classname, operation){
  // Target = element or element array
  // Classname = New classname
  // Operation = Add / Remove Class
  if(targets.length > 1){
    if(operation == "add"){
      targets.forEach(function (target){
        target.addClass(classname)
        target.next("img").addClass(classname)
      })
    } else if(operation == "remove"){
      target.forEach(function (target){
        target.removeClass(classname).attr("placeholder", "")
        target.next("img").removeClass(classname)
      })
    }
  } else {
    if(operation == "add"){
      targets.addClass(classname)
      targets.next("img").addClass(classname)
    } else if (operation == "remove"){
      targets.removeClass(classname).attr("placeholder", "")
      targets.next("img").removeClass(classname)
    }
  }
}  // handles single or multiple elements remove or add classes with img next to it

lgbtn.click(function(){
    lgid.removeClass('invalid').attr("placeholder", "").next("img").removeClass('invalid')
    lgpw.removeClass('invalid').attr("placeholder", "").next("img").removeClass('invalid')
    $.ajax({
      type: "POST",
      data: {lgid:lgid.val(), lgpw:lgpw.val(), lgset:true},
      success: function(returndata){
        if(returndata === "E501"){ // Not valid credentials
          hightlight_inputs([lgid, lgpw], "invalid", "add")
        } else if (returndata === "E506") { // Loggin verified and cookies set
          // Save uncrypted id & pw in local db
          lsrem = [lgid.val(), lgpw.val()]
          localStorage.setItem("remID", lsrem)
          location.reload()
        }
      }
    });
})
$('input').click(function(){
  if($(this).hasClass('invalid')){
    hightlight_inputs($(this), "invalid", "remove")
  }
})
function return_home(){
  console.log(location.href);
  location.href = "?";
}
