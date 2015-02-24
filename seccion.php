<? 
$seccion = $_POST['seccion'];

if($seccion=="Homepage") {echo '<p>Homepage</p>';}
if($seccion=="About") { echo '<p>Mariona Dalmau //';
						echo 'Sergi Caelles //';
						echo 'Alberto Gomez //';
						echo 'Ricard Boada //';
						echo 'Miquel Marti //';
						echo 'Alejandro Fernandez</p>';}
if($seccion=="Trends") { 

						$hostname = 'us-cdbr-iron-east-01.cleardb.net';
						$username = 'b8be3578d06bd2';
						$password = '8d45e044';
						$db = "ad_06d3edcdc42d9c2";
						$con = mysqli_connect($hostname,$username,$password,$db) or die("Caca doble");
						// Check connection
						if (mysqli_connect_errno())
							{
								echo "Failed to connect to MySQL: " . mysqli_connect_error();
							}
						
						$res=mysqli_query($con,"SELECT tags FROM posts ORDER BY date DESC LIMIT 4") or die("BETWEEN . mysqli_error()");
						while($rresu=mysqli_fetch_array($res)){
							$tag=$rresu['tags'];
							echo $tag."<br>";
						}
						
						
						
						mysqli_close($con);
}
if($seccion=="Contact") { echo 'Contact'; }


?>