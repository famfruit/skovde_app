<?php
session_start();
spl_autoload_register('autoLoadClasses');
function autoLoadClasses($className){
  $path = "res/classes/";
  $ext = ".class.php";
  $fullP = $path . $className . $ext;
  include_once $fullP;
}
$main = new base;
mysqli_set_charset($main->con,"utf8");
if($main->userAjaxSet){
  $main->auth_user($main->userId, $main->userPw);
  return;
}
if(isset($_POST['logoutbtn'])){
  setcookie('userCookie', null, -1, "/");
  header('Location: ?');
  exit;
}
########################################
### AJP FUNCTIONS
### * TO_DO FOR ALL
### * - add history tab data
########################################

if(isset($_POST['ajp_archive'])){
  $ajp_id = (int)$_POST['ajp_rID'];
  $ajp_status = (int)$_POST['ajp_rStatus'];
  $ajp_user = $_POST['ajp_byUser'];
  // * TO_DO: add history to sql with json or something
  $sql = "UPDATE reports SET status = $ajp_status WHERE refid = $ajp_id";
  mysqli_query($main->con, $sql);
  # Make sure it changed like we told it to
  $sql = "SELECT * FROM reports WHERE refid = $ajp_id";
  $result = mysqli_query($main->con, $sql);
  if($result->num_rows > 0){
    while($row = mysqli_fetch_assoc($result)){
        if($row['status'] == $ajp_status){
          echo "100";
        } else {
          echo "504";
        }
    }
  } else {
    echo "504";
  }
  exit;
}
if(isset($_POST['ajp_activate'])){
  $ajp_id = (int)$_POST['ajp_rID'];
  $ajp_status = (int)$_POST['ajp_rStatus'];
  $ajp_user = $_POST['ajp_byUser'];
  // * TO_DO: add history to sql with json or something
  $sql = "UPDATE reports SET status = $ajp_status WHERE refid = $ajp_id";
  mysqli_query($main->con, $sql);
  # Make sure it changed like we told it to
  $sql = "SELECT * FROM reports WHERE refid = $ajp_id";
  $result = mysqli_query($main->con, $sql);
  if($result->num_rows > 0){
    while($row = mysqli_fetch_assoc($result)){
        if($row['status'] == $ajp_status){
          echo "100";
        } else {
          echo "504";
        }
    }
  } else {
    echo "504";
  }
  exit;
}
if(isset($_POST['ajp_queue'])){
  $ajp_id = (int)$_POST['ajp_rID'];
  $ajp_user = strtolower($_POST['ajp_byUser']) ;
  $ajp_date = date("Y-m-d h:i");
  $sql = "SELECT * FROM tasks WHERE taskid = '$ajp_id'";
  $result = mysqli_query($main->con, $sql);
  if($result->num_rows > 0){
    while($row = mysqli_fetch_assoc($result)){
      if($row['user'] != $ajp_user){
        $sql = "INSERT INTO tasks (taskid, user, date, status) VALUES ('$ajp_id', '$ajp_user', '$ajp_date', 0)";
        mysqli_query($main->con, $sql);
        echo "100";
        exit;
      } else {
        $sql = "UPDATE tasks SET date = '$ajp_date' WHERE taskid = $ajp_id";
        mysqli_query($main->con, $sql);
        echo "303";
        exit;
      }
    }
  } else {
    $sql = "INSERT INTO tasks (taskid, user, date, status) VALUES ('$ajp_id', '$ajp_user', '$ajp_date', 0)";
    mysqli_query($main->con, $sql);
    echo "100";
    exit;
  }
}

if(isset($_POST['ajp_note'])){
  $ajp_id = (int)$_POST['ajp_rID'];
  $ajp_user = $_POST['ajp_byUser'];
  $ajp_message = $_POST['ajp_message'];
  $sql = "SELECT * FROM reports WHERE refid = $ajp_id";
  $result = mysqli_query($main->con, $sql);
  while($row = mysqli_fetch_assoc($result)){
    if(strlen($row['note']) == 0){
      $json_data = array();
      $indiv_arr = array("user" => $ajp_user,"msg" => $ajp_message);
      array_push($json_data, $indiv_arr);
      $p_data = json_encode($json_data);
      $sql = "UPDATE reports SET note = '$p_data' WHERE refid = $ajp_id";
      mysqli_query($main->con, $sql);
      echo "100";
    } else {
      $p_data = json_decode($row['note'], true);
      $js_dt = array("user" => $ajp_user, "msg" => $ajp_message);
      array_push($p_data, $js_dt);
      $p_data = json_encode($p_data);
      $sql = "UPDATE reports SET note = '$p_data' WHERE refid = $ajp_id";
      mysqli_query($main->con, $sql);
      echo "100";
    }
  }
  exit;
}
if(isset($_POST['ajp_complete'])){
  $ajp_id = (int)$_POST['ajp_rID'];
  $ajp_status = (int)$_POST['ajp_rStatus'];
  $sql = "UPDATE reports SET status = $ajp_status WHERE refid = $ajp_id";
  if(mysqli_query($main->con, $sql)){
    $sql = "UPDATE tasks SET status = $ajp_status WHERE taskid = $ajp_id";
    mysqli_query($main->con, $sql);
    echo "100";
    exit;
  } else {
    echo "504";
    exit;
  }

}
########################################
############### END ####################
########################################
if(isset($_POST['send_test'])){
  $thetime = date("Y-m-d h:i");
  $status = 0;
  $name = $_POST['name'];
  $fastighet = $_POST['fastighet'];
  $lghnr = $_POST['lghnr'];
  $pets = $_POST['pets'];
  $keys = $_POST['key'];
  $phone = $_POST['number'];
  $title = $_POST['title'];
  $type = 1;
  $comment = $_POST['comment'];
  #var_dump($thetime, $status, $name, $fastighet, $lghnr, $pets, $keys, $phone, $title, $type, $comment);
  $sql = "INSERT INTO reports (time, status, name, fastighet, lghnr, husdjur, huvudnyckel, telefon, title, type, comment) VALUES ('$thetime', $status, '$name', '$fastighet', $lghnr, $pets, $keys, $phone, '$title', $type, '$comment')";
  mysqli_query($main->con, $sql);
  header('Location: ?q=add-page&status=done');
  exit;
}
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <body>
  <?php
    if(!isset($_COOKIE['userCookie'])){
      include_once "res/pages/login.html";
    } else {
      if(isset($main->navQuery)){
        if(in_array($main->navQuery, $main->queryOptions)){
          # Query options exists, continue
          include_once "res/pages/".strtolower($main->navQuery).".php";
        } else {
          echo "Access Denied";
        }
      } else if(isset($_GET['inbox'])) {
        include_once "res/pages/inbox.php";
      } else {
        include_once "res/pages/profile.php";
      }
    }
  ?>
  </body>
  <script src="res/js/mainlog.js"></script>
  <script src="res/js/mp_actions.js"></script>
</html>
