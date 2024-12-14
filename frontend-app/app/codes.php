<?php

try
{
        $dbh = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $username, $password);
} catch(PDOException $e)
{
        http_response_code(401);
        trigger_error($e->getMessage());
        die("Database error!");
}

if (empty($_SESSION["account_id"])) {
        header("location: login.php");
}

try
{
        $sth = $dbh->prepare("SELECT * FROM ".$usertablename." WHERE id = :id");
        $sth->execute([':id' => $_SESSION["account_id"] ]);
        $row = $sth->fetch();

} catch(PDOException $e)
{
        http_response_code(401);
        trigger_error($e->getMessage());
        die("Database error!");
}


?>

<style>
.accordion {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
  transition: 0.4s;
}

.active, .accordion:hover {
  background-color: #ccc;
}

.accordion:after {
  content: '\002B';
  color: #777;
  font-weight: bold;
  float: right;
  margin-left: 5px;
}

.active:after {
  content: "\2212";
}

.panel {
  padding: 0 18px;
  background-color: white;
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.2s ease-out;
}
</style>

<div class="page-content">
	<div class="row">
		<div class="col-lg-12 left_section">
			<div class="left_section-container">
				<h2>Integration information and documentation</h2>
				<p>All the help you need to integrate this channel with your systems: encoder settings, embeded codes, API access, etc.</p>
				<button class="accordion">Configure your encoder and go live</button>
				<div class="panel">
					<?php 
					echo "<br>Server URL: rtmp://publish.maghost.ro/transcoder?key=".$row["apphash"]."<br>";
					echo "Stream Key: " . $_SESSION["appname"]."?key=".$row["idhash"];
					?>
					<br><br>
				</div>

				<button class="accordion">Integrate the live player to your website</button>
				<div class="panel">
				  <xmp><iframe src="https://localhost/player.php?app=<?=$_SESSION["appname"]?>" style="height:100vh;width:100%;" allow="fullscreen"></iframe></xmp>
				</div>

				<button class="accordion">Integrate the media archive into your website</button>
				<div class="panel">
				 <xmp><iframe src="https://arhiva.stream.maghost.ro/archive/<?=$_SESSION["appname"]?>" style="height:100vh;width:100%;" allow="fullscreen"></iframe></xmp>
				</div>
			</div>
		</div>
	</div>

</div>

<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}
</script>