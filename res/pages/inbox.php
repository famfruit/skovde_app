<?php
  $refid = $_GET['inbox'];
  $sql = "SELECT * FROM reports WHERE refid = '$refid'";
  $result = mysqli_query($main->con, $sql);
  $gl_status = 0;
  if($result->num_rows > 0){
    while($row = mysqli_fetch_assoc($result)){
      $enter = ($row['huvudnyckel'] == 1 ? "Ja" : "Nej");
      $gl_status = $row['status'];
      $status_top = ($row['status'] == 3 ? "Arkiverad" : "");
        ?>
          <div class="topbar inbox">
            <div class="goback__btn" onclick="history.back()"></div>
            <div class="goback__home" onclick="return_home()"></div>
            <h1 class="refid"><strong><?php echo $_GET['inbox'] ?></strong><br><?php echo $status_top ?></h1>
            <div class="tpbar__inbox">
              <div class="inbox__divider">
                <span class="lable">NAMN</span>
                <h2><?php echo $row['name'] ?></h2>
              </div>
              <div class="inbox__divider">
                <span class="lable">FASTIGHET</span>
                <h2><?php echo $row['fastighet'] ?> | <?php echo $row['lghnr'] ?></h2>
              </div>
            </div>
            <div class="tpbar__inbox icons">
              <a href="tel:0<?php echo $row['telefon'] ?>">

                <i class="fas fa-phone-alt"><span>Ring</span></i>
              </a>
              <a href="sms:0<?php echo $row['telefon'] ?>">
                <i class="far fa-comment-dots"><span>SMS</span></i>
              </a>
              <a href="sms:0<?php echo $row['telefon'] ?>?body=Hej <?php echo explode(" ", $row['name'])[0] ?>! <?php echo ucfirst($main->gl_user) ?> från Skövdehem här. Vi är påväg hem till dig angående ditt ärende och beräknar vara framme inom 10-30 minuter.">
                <i class="fas fa-route"><span>Påväg</span></i>
              </a>
            </div>
          </div>

          <div class="inbox-profile app_hidden">
            <h1>HYRESGÄST</h1>
            <div class="inprofile-container">
              <span class="lable">NAMN</span>
              <h2><?php echo $row['name'] ?></h2>

              <span class="lable">FASTIGHET</span>
              <h2><?php echo $row['fastighet'] ?> | <?php echo $row['lghnr'] ?></h2>

              <span class="lable">TILLTRÄDESRÄTT</span>
              <h2><?php echo $enter ?></h2>

              <span class="lable">TELEFONNUMMER</span>
              <a href="tel:0<?php echo $row['telefon'] ?>"><h2><?php echo "0".$row['telefon'] ?></h2></a>
            </div>
          </div>
          <div class="inbox-profile">
            <div class="inprofile-container">


              <span class="lable">KOMMENTAR</span>
              <h2><?php echo $row['comment'] ?></h2>
            </div>
          </div>

          <div class="inbox-profile last">
            <?php
              $row_note = json_decode($row['note'], true);
              if(sizeOf($row_note) > 0){
                ?>
                <h1>NOTISER</h1>
                <div class="inprofile-container">
                  <?php
                    foreach($row_note as $key => $value){
                      ?>
                      <h2 style="margin: 0;margin-top: 10px"><?php echo ucfirst($value["user"]) ?></h2>
                      <span class="lable"><?php echo $value["msg"] ?></span>
                      <?php
                    }
                   ?>
                </div>
                  <?php
                }
               ?>
          </div>


          <div class="inbox-profile last app_hidden">
            <h1>TIDIGARE DATA</h1>
            <div class="inprofile-container data">
              <div class="ipc">
                <span class="lable fel done">Felanmälan</span>
                <h2>Min ugn är trasig</h2>
                <span class="date">12:34</span>
              </div>
              <div class="ipc">
                <span class="lable request done">Förfrågan</span>
                <h2>Önskar blommor till gårdsplanen</h2>
                <span class="date">Igår, 08:40</span>
              </div>
              <div class="ipc">
                <span class="lable intresse done">Intresse</span>
                <h2>Söker 3rok i Norrmalm</h2>
                <span class="date">Söndags, 14:02</span>
              </div>
            </div>
          </div>
          <div class="note_container app_hidden">
            <div class="msg">
              <h1>Lägg till notis</h1>
              <textarea class="txt__note" name="note_cont" placeholder="Skriv ditt meddelande här.."></textarea>
              <div class="mp_buttons">
                <div class="mp__btn btn_n mp_note_cancel">
                  Avbryt
                </div>
                <div class="mp__btn btn_y mp_note_send">
                  Skicka
                </div>
              </div>
            </div>
          </div>
          <div class="mp_popup app_hidden">
            <div class="msg">
              <h1>Vill du markera denna som åtgärdad?<strong>rID (125504)</strong></h1>

              <div class="mp_buttons">
                <div class="mp__btn btn_n">
                  Nej
                </div>
                <div class="mp__btn btn_y">
                  Ja
                </div>
              </div>
            </div>
          </div>
        <?php include_once "modules/mf_actionbar.php" ?>
        <?php
    }
  } else {
    echo "404";
  }
 ?>
