

 <div class="topbar inbox">
   <div class="goback__btn" onclick="history.back()"></div>
   <div class="goback__home" onclick="return_home()"></div>
   <h1>Inbox <?php $main->compile_reports() ?></h1>
 </div>
 <div class="inbox-container">
   <?php
     if(!$main->i_sort || $main->i_sort == "all"){
       $sql = "SELECT * FROM reports";
     } else if($main->i_sort == "new"){
       $sql = "SELECT * FROM reports ORDER BY time DESC";
     } else if($main->i_sort == "done"){
       $sql = "SELECT * FROM reports WHERE status = 1 OR status = 2 ORDER BY time DESC";
     } else if($main->i_sort == "undone"){
       $sql = "SELECT * FROM reports WHERE status = 0 ORDER BY time DESC";
     }
     $result = mysqli_query($main->con, $sql);
     while($row = mysqli_fetch_assoc($result)){
       $date_form = $row['time'];
       $date_form = strtotime($date_form) + 60*120;
       $date_form = date("Y-m-d H:i:s", $date_form);
       $status = ($row['status'] == 0 ? "<strong class='blip'></strong>" : "");
       $a_status = ($row['status'] == 0 ? "one" : "nill");
       $ty_input = "st_".$row['type'];
       ?>
       <a href="?inbox=<?php echo $row['refid'] ?>">
         <div class="inbox-object <?php echo $a_status ?>">
           <h1 class="<?php echo $ty_input ?>"><?php echo $status.$row['name'] ?></h1>
           <span class="name"><?php echo $row['comment'] ?></span>
           <span class="street"><?php echo ucwords($row['fastighet'])." ".$row['lghnr'] ?></span>
           <span class="type fel"><?php echo $main->reportType[$row['type']] ?></span>
           <span class="timeago"><?php echo $main->timeAgo($date_form) ?></span>
         </div>
       </a>
       <?php
     }

    ?>

 </div>

 <?php include_once "modules/mf_actionbar.php" ?>
