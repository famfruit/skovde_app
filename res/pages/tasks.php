<div class="topbar inbox tasks">
  <div class="goback__btn" onclick="history.back()"></div>
  <div class="goback__home" onclick="return_home()"></div>
  <?php
    $sql = "SELECT * FROM tasks WHERE user = '$main->gl_user'";
    $result = mysqli_query($main->con,$sql);
    $cur_month_day = date("m-d");
    $my_tasks = 0;
    $my_done = 0;
    while($row = mysqli_fetch_assoc($result)){
      $db_month_day = date("m-d", strtotime($row['date']));

      if($cur_month_day == $db_month_day){
        ($row['user'] == $main->gl_user ? $my_tasks++ : null);
        ($row['user'] == $main->gl_user && $row['status'] == 1 ? $my_done++ : null);
      }
    }
    $my_perc = round($my_done / $my_tasks * 100);
   ?>
  <div class="tasks-top">
    <h1>Arbetsuppgifter</h1>
    <span><?php echo $my_tasks - $my_done ?> <strong class="thin">Oklara</strong><br><?php echo $my_done ?> <strong class="thin">Färdiga</strong> </span>
    <div class="tasks-progress">
      <?php
        if(is_nan($my_perc) != true){
          ?>
            <h1><?php echo $my_perc ?><strong>%</strong></h1>
          <?php
        } else {
          ?>
            <h1><strong></strong></h1>
          <?php
        }
       ?>
    </div>
  </div>
  <div class="tasks-progbar">
    <div class="tasks_current" style="width: <?php echo $my_perc ?>%"></div>
  </div>
</div>
<div class="inbox-profile undone">
  <h1>KÖADE</h1>
  <?php
  if($my_perc == 100){
    ?>
      <h3 class="no__tasks">Dagens uppgfiter avklarade.</h3>
    <?php
  } else if ($my_done == 0 && $my_tasks == 0){
    ?>
    <h3 class="no__tasks">Inga köade arbetsuppgifter för idag.</h3>
    <?php
  }
    $sql = "SELECT * FROM tasks WHERE status = 0";
    $result = mysqli_query($main->con,$sql);
    while($row = mysqli_fetch_assoc($result)){
      $task_id = $row['taskid'];
      $seq = "SELECT * FROM reports WHERE refid = $task_id";
      $res = mysqli_query($main->con, $seq);
      $q_users = array();
      while($rad = mysqli_fetch_assoc($res)){
          $r_seq = "SELECT * FROM tasks WHERE taskid = $task_id";
          $r_res = mysqli_query($main->con, $r_seq);
          $db_month_day = "";
          while($rr = mysqli_fetch_assoc($r_res)){
            array_push($q_users, $rr['user']);
            $db_month_day = date("m-d", strtotime($rr['date']));
          }

          if($row['user'] == $main->gl_user){
            if($cur_month_day == $db_month_day){
            $date_form = $rad['time'];
            $date_form = strtotime($date_form) + 60*120;
            $date_form = date("Y-m-d H:i:s", $date_form);
                ?>
                <a href="?inbox=<?php echo $row['taskid'] ?>">
                  <div class="inprofile-container">
                    <div class="tasks__on_duty">
                      <?php
                        foreach($q_users as $key => $value){
                          $key = $key + 1;
                          ?>
                          <span class="t_<?php echo $key ?>"><?php echo strtoupper($value[0]) ?></span>
                          <?php
                        }
                       ?>
                    </div>
                    <h3 class="tasks__title"><?php echo $rad['title'] ?></h3>
                    <span class="tasks__type fel">Felanmälan</span>
                    <span class="tasks__date"><?php echo $main->timeAgo($date_form) ?></span>
                  </div>
                </a>
                <?php
              }
        }
      }
    }
   ?>
</div>

<div class="inbox-profile done last">
  <h1>Klara</h1>
  <?php
    if ($my_perc == 0){
    ?>
    <h3 class="no__tasks">Inga avklarade uppgifter än.</h3>
    <?php
  }
    $sql = "SELECT * FROM tasks WHERE status = 1";
    $result = mysqli_query($main->con,$sql);
    while($row = mysqli_fetch_assoc($result)){
      $task_id = $row['taskid'];
      $seq = "SELECT * FROM reports WHERE refid = $task_id";
      $res = mysqli_query($main->con, $seq);
      $q_users = array();
      while($rad = mysqli_fetch_assoc($res)){
          $r_seq = "SELECT * FROM tasks WHERE taskid = $task_id";
          $r_res = mysqli_query($main->con, $r_seq);
          $db_month_day = "";
          while($rr = mysqli_fetch_assoc($r_res)){
            array_push($q_users, $rr['user']);
            $db_month_day = date("m-d", strtotime($rr['date']));
          }
          if($row['user'] == $main->gl_user){
            if($cur_month_day == $db_month_day){
            $date_form = $rad['time'];
            $date_form = strtotime($date_form) + 60*120;
            $date_form = date("Y-m-d H:i:s", $date_form);
                ?>
                <a href="?inbox=<?php echo $row['taskid'] ?>">
                  <div class="inprofile-container">
                    <div class="tasks__on_duty">
                      <?php
                        foreach($q_users as $key => $value){
                          $key = $key + 1;
                          ?>
                          <span class="t_<?php echo $key ?>"><?php echo strtoupper($value[0]) ?></span>
                          <?php
                        }
                       ?>
                    </div>
                    <h3 class="tasks__title"><?php echo $rad['title'] ?></h3>
                    <span class="tasks__type fel">Felanmälan</span>
                    <div class="task__completed"></div>
                  </div>
                </a>
                <?php
              }
        }
      }
    }
   ?>
</div>
<?php include_once "modules/mf_actionbar.php" ?>
