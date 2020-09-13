<div class="topbar inbox">
  <div class="goback__btn" onclick="history.back()"></div>
  <div class="goback__home" onclick="return_home()"></div>
  <h1>Inställningar</h1>
</div>
<?php
  $settings_cat = array(
    "Global uppdateringstid+Interval för updates, påverkar dataanvändning+10 Minuter" => "set",
    "Notiser+Visas vid ändringar, uppdateringar & nyheter+on" => "toggle",
    "Inkognitio läge+Dölj min aktivitet för andra+off" => "toggle",
    "Cookies+Håller dig inloggad vid inaktivitet+on" => "toggle"
  );
 ?>
<div class="app-cat">
  <?php
    foreach($settings_cat as $key => $value){
      $header = explode("+", $key);
      ?>
      <div class="cat-listobject settings">
        <span><?php echo $header[0] ?></span>
        <?php
          if($value != "set"){
            ?>
            <span class="setting-desc"><?php echo $header[1] ?></span>
            <div class="clo-toggle">
              <div class="clo-ball <?php echo $header[2] ?>">

              </div>
            </div>
            <?php
          } else if($value == "set"){
            ?>
            <span class="settings-link"><?php echo $header[2] ?></span>
            <?php
          }
         ?>
      </div>
      <?php
    }
   ?>

</div>
