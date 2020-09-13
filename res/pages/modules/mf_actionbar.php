<?php
  if($main->navPointer == "inbox"){
    ## Check if QUEUE SHOULD BE HIGHLIGHTED OR NOT
    $inbox_id = (int)$_GET['inbox'];
    $result = mysqli_query($main->con, "SELECT * FROM tasks WHERE taskid = $inbox_id");
    if($result->num_rows > 0){
      while($row = mysqli_fetch_assoc($result)){
        if($row['user'] == $main->gl_user) {
            $queue_state = "queue on";
        } else {
            $queue_state = "queue off";
        }
      }
    } else {
      # Keep unused
      $queue_state = "queue off";
    }
    $gl_status = ($gl_status == 0 ? $gl_status = ["Arkivera", "archive"] : ["Aktivera", "activate"]);
    $np_multinav = array(
      "Åtgärdat" => ["middle", "big"],
      "Notis" => ["left", "note"],
      "Queue" => ["right", $queue_state]
    );
    $np_pos = array("left", "middle", "right");

  } else if($main->navPointer == "reports"){
    $np_multinav = array(
      "Alla" => ["left", "all"],
      "Nya" => ["left", "new"],
      "Åtgärdade" => ["right", "done"],
      "Oklara" => ["right", "undone"]
    );
    $activate_href = true;
    $np_pos = array("left", "middle", "right");
  }
  # Classifier to determine if theres a middle _BUTTON_ or not
  # 1. First, gather all pos inputs and loop through them
  # 2. Put all results in temp array
  # 3. Check for needle in temp array, if middle exists, determine layout
  $hstk_temp = array();
  foreach($np_multinav as $ndl => $hstk){
    array_push($hstk_temp, $hstk[0]);
  }
  (in_array("middle", $hstk_temp) == true
    ? $mid_classifier = "mid_ext"
    : $mid_classifier = "no_mid"
  );
  $classAmount_call = sizeOf($np_multinav);
?>
<div class="multif__actionbar t2 <?php echo "amount_".$classAmount_call." ".$mid_classifier ?>">
  <?php
    foreach($np_pos as $k => $v){ ?>
      <div class="<?php echo $v ?>">
        <?php foreach($np_multinav as $kp => $vp){
          if($v === $vp[0]){
            if(isset($activate_href) || $activate_href == true){
              if($main->navPointer == "reports"){
                if($main->i_sort === $vp[1]){
                  $activate_classifier = "active";
                } else if(!$main->i_sort && $vp[1] == "all") {
                  $activate_classifier = "active";
                } else {
                  $activate_classifier = "";
                }
              }
              ?>
                <a href="?q=reports&i_sort=<?php echo $vp[1] ?>">
                  <div class="mp_bar <?php echo strtolower($vp[1])." ".$activate_classifier ?>"><?php echo ($vp[0] != "middle" ? $kp : "") ?></div>
                </a>
              <?php
            } else {
              ?>
              <div class="mp_bar <?php echo strtolower($vp[1]) ?>"><?php echo ($vp[0] != "middle" ? $kp : "") ?></div>
              <?php
            }
            ?>
    <?php }
        } ?>
      </div>
<?php } ?>


  <?php

    #foreach($np_multinav as $key => $value){
    #  $classIndex = ($value == "middle") ? "class='big'" : "class='".$value."'";
    #  ?>
        <!--<div
          <?php #echo $classIndex ?>
          >
       <?php #echo ($value != "middle") ? $key : ""; ?>
     </div> -->
      <?php
    #}
   #?>
</div>
