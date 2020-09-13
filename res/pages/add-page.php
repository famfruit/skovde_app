
<h1 class="betah1">Endast för utvecklingsyfte (ta bort)<?php
  if($_GET['status'] == "done"){
    echo "<br><strong>FORMULÄR SKICKAT</strong>";
    echo "<br><a href='?'>Gå tillbaka</a>";
  }
 ?></h1>

<form method="post" class="testsubject">
  <span>För & Efternamn</span>
  <input type="text" name="name" value="">

  <span>Telefonnummer</span>
  <input type="text" name="number" value="">

  <span>Ämne</span>
  <input type="text" name="title" value="">

  <span>Fastighet</span>
  <input type="text" name="fastighet" value="">

  <span>Lägenhetsnummer</span>
  <input type="text" name="lghnr" value="">

  <span>Har du husdjur?</span>
  <select name="pets">
    <option value="0" selected>Har du husdjur hemma?</option>
    <option value="1">Ja</option>
    <option value="2">Nej</option>
  </select>

  <span>Gå in med huvudnyckel?</span>
  <select name="key">
    <option value="0" selected>Gå in med huvudnyckel?</option>
    <option value="1">Ja</option>
    <option value="2">Nej</option>
  </select>

  <span>Kommentar</span>
  <textarea type="text" name="comment" value=""></textarea>

  <button type="submit" name="send_test">Skicka</button>
</form>
