<?php
class base {
  public function __construct(){
    #$this->userLoggedIn = isset($_COOKIE['uLi']) ? true : false;
    $this->applicationVersion = "0.0.1";
    $this->userLoggedIn = false;
    $this->userAjaxSet = isset($_POST['lgset']);
    $this->userId = isset($_POST['lgid']) ? $_POST['lgid'] : "";
    $this->userPw = isset($_POST['lgpw']) ? $_POST['lgpw'] : "";
    $this->con = mysqli_connect('localhost', 'root', '', 'skovde');
    $this->userCookie = $_COOKIE['userCookie'];
    $this->navQuery = isset($_GET['q']) ? $_GET['q'] : null;
    $this->gl_user = $_COOKIE['gl_user'];
    $this->navPointer = (isset($_GET['q']) ? $_GET['q'] : (isset($_GET['inbox']) ? "inbox" : null));
    $this->i_sort = ($_GET['i_sort']) ? $_GET['i_sort'] : null;
    $this->reportType = array(
      "1" => "Fel",
      "2" => "Intresse",
      "3" => "Förfrågan"
    );
    $this->userPrivs = array(
      "0" => "Systemutvecklare",
      "1" => "Administratör",
      "2" => "Fastighetsskötare"
    );
    $this->profileMenu = array(
      "Rapporter" => ["reports.svg", "reports", "compile_reports()"],
      "Fastighetsöversikt" => ["apartments.svg", "overview"],
      "Arbetsuppgifter" => ["mechanic.svg", "tasks"]
    );
    $this->settingMenu = array(
      "Personalisering" => ["options.svg", "options"],
      "Inställningar" => ["application.svg", "settings"],
      "Logga ut" => ["logout.svg", "-submit"],
      "(TEST) Felanmälan" => ["question.svg", "add-page"]
    );
    $this->queryOptions = [
      "reports",
      "overview",
      "options",
      "settings",
      "tasks",
      "add-page" // THIS IS FOR TEST PURPOSES ONLY
    ];
  }
  public function auth_user($userid, $password){
    ## Authenticate the user, if it checks out
    ## give the user a cookie and fill it.
    setcookie('gl_user', strtolower($userid), time() + (86400 * 30 * 30), "/");
    $sql = "SELECT * from users WHERE user = '$userid' AND pw = '$password'";
    $result = mysqli_query($this->con, $sql);
    if($result->num_rows > 0){
      while($row = mysqli_fetch_assoc($result)){
        setcookie('userCookie', null, -1, "/");
        setcookie('userCookie', json_encode($row), time() + (86400 * 30 * 30), "/");
        echo "E506";
      }
    } else {
      echo "E501";
    }
  }
  public function compile_reports(){
    $result = mysqli_query($this->con, "SELECT * FROM reports WHERE status = 0");
    if($result->num_rows > 0){
      $number = "<strong class='numb'>".$result->num_rows."</strong>";
    } else {
      $number = "";
    }

    echo $number;
  }
  public function timeAgo($time_ago)
  {
      $time_ago = strtotime($time_ago);
      $cur_time   = time();
      $time_elapsed   = $cur_time - $time_ago;
      $seconds    = $time_elapsed ;
      $minutes    = round($time_elapsed / 60 );
      $hours      = round($time_elapsed / 3600);
      $days       = round($time_elapsed / 86400 );
      $weeks      = round($time_elapsed / 604800);
      $months     = round($time_elapsed / 2600640 );
      $years      = round($time_elapsed / 31207680 );
      // Seconds
      if($seconds <= 60){
          return "precis nu";
      }
      //Minutes
      else if($minutes <=60){
          if($minutes==1){
              return "en minut sedan";
          }
          else{
              return "$minutes minuter sedan";
          }
      }
      //Hours
      else if($hours <=24){
          if($hours==1){
              return "en timma sedan";
          }else{
              return "$hours timmar sedan";
          }
      }
      //Days
      else if($days <= 7){
          if($days==1){
              return "igår";
          }else{
              return "$days dagar sedan";
          }
      }
      //Weeks
      else if($weeks <= 4.3){
          if($weeks==1){
              return "en vecka sedan";
          }else{
              return "$weeks veckor sedan";
          }
      }
      //Months
      else if($months <=12){
          if($months==1){
              return "en månad sedan";
          }else{
              return "$months månader sedan";
          }
      }
      //Years
      else{
          if($years==1){
              return "ett år sedan";
          }else{
              return "$years år sedan";
          }
      }
  }
}
?>
