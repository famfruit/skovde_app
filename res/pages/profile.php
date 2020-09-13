<?php
  // Check that user is authenticated and fend of retarded users
  // Then perform regular functions

  $userArray = json_decode($_COOKIE['userCookie'], true);
  ?>
  <div class="app-topcard">
      <h1 class="top-time"><?php echo date("d")?><strong><?php echo date("M Y") ?></strong></h1>
      <span class="top-name"><?php echo ucwords($userArray["user"]) ?><strong><?php echo $main->userPrivs[intval($userArray["priv"])] ?></strong></span>
  </div>
  <div class="app-cat">
    <?php
      foreach($main->profileMenu as $menu => $value){
        // Compiles all MAIN menus needed
        ?>
          <a href="?q=<?php echo $value[1] ?>">
            <div class="cat-listobject">
              <img src="res/img/svg/<?php echo $value[0] ?>">
              <span><?php echo ucfirst($menu); ($value[2]) ? $main->compile_reports() : ""; // If it has a third value, call function, else continue ?></span>
            </div>
          </a>
        <?php
      }

     ?>
  </div>
  <div class="app-cat sub">
    <h1>Inställningar & Alternativ</h1>
    <?php
      foreach($main->settingMenu as $menu => $value){
        if($value[1][0] != "-"){
          # If value has "-", treat is as regular hyper
          # Else it's a submit call.
          ?>
          <a href="?q=<?php echo $value[1] ?> ">
            <div class="cat-listobject">
              <img src="res/img/svg/<?php echo $value[0] ?>">
              <span><?php echo ucfirst($menu) ?></span>
            </div>
          </a>
          <?php
        } else {
          ?>
          <div class="cat-listobject">
            <img src="res/img/svg/<?php echo $value[0] ?>">
            <form method="post">
              <button type="submit" name="logoutbtn"><?php echo $menu ?></button>
            </form>
          </div>
          <?php
        }

      }
     ?>
  </div>
  <?php
 ?>
