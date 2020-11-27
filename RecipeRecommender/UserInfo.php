<?php
session_start();
include_once 'dbconnect.php'; //include php file
	

if (isset($_SESSION['uname']) && isset($_SESSION['upassword'])) {
    $name = mysqli_real_escape_string($con, $_SESSION["uname"]); //escape all the characters that could damange database
	$result0 = mysqli_query($con, "SELECT * FROM Uprofile WHERE uname = '" . $name. "'");
	$row0 = mysqli_fetch_array($result0);
// 	print_r($row0);
    if ($row0) {
    		
    } else {
    	$errormsg = "You have not edit a profile yet! Please introduce yourself!";
    }
} else {
  header("Location: login.php");
  }
?>
<!DOCTYPE html>
<html>
<head>
    <title>UserInfo | RecipeRecommender</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />  
     <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

</head>
<body>

<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">RecipeRecommender</a>
        </div>
        <div class="pull-right" id="navbar1">
            <ul class="nav navbar-nav navbar-right">
                <?php if (isset($_SESSION['uname'])) { ?>
                <li><p class="navbar-text">Hello <?php echo $_SESSION['uname']."!"; ?></p></li>
                <li><p class="navbar-text">Not  <?php echo $_SESSION['uname']."? "; ?><a href="login.php">Login</a></p></li>
                <li><a href="logout.php">Log Out</a></li>
                <?php }  ?>
            </ul>
        </div>
    </div>
</nav>
<!-- 	<?php if (isset($errormsg)) {echo $errormsg;} else {echo Hello;}?>  -->
    <h3> Welcome to <?php echo $name."'s kitchen!"?> </h3> 
    <hr /> 
    <p> <?php if (isset($errormsg)) {echo $errormsg;}?></p>
     
    <h4><?php echo $name."'s"?> profile</h4>
    <?php if (isset($errormsg) or $row0['profile']=='null') {
    		echo "<p> it's a secret.ho ho ho...</p>";
    	 } else {
    	 echo $row0['profile'];}?>
     <p><a href="EditProfile.php"> [edit your profile]</a> </p>
     
    <hr />   
    <h4 align="center"> <?php echo $name."'s"?> favorite recipes</a></h4> 
<?php
 	$query1="SELECT * FROM Recipe NATURAL JOIN favRecipe WHERE fname ='".$name."'" ;
//  	echo $query1;
	$result = mysqli_query($con,$query1);
	if ($result){
	$resultsr1 = mysqli_num_rows($result); 
	$s='<p align="center">';
	if ($resultsr1>0) {
		$colNum = 3; $i = 0;
   		while($row = $result->fetch_assoc()) {
   			$i = $i+1;
   			$rid = $row["rid"];
			$rtitle = $row["rtitle"];
			$rServNum= $row["rServNum"];
			$rCookTime= $row["rCookTime"];
			$resultim = mysqli_query($con, "SELECT RecipeImgDir FROM RecipeImg WHERE rid = '" . $rid. "' limit 3");
			if (mysqli_num_rows($resultim)==1){
				$rowIm = mysqli_fetch_array($resultim);
				$MyPhoto=  $rowIm['RecipeImgDir'];
				$iId = "<img src= 'http://localhost/proj2/Images/".$MyPhoto."'width = 400/>";
			} else {
				$iId = '';
			}
			$s.= '<a href="recipe.php?rid=' . $rid . '">' ."<b>* </b>" . $rtitle. '</a>'." serving No.: </b>" . $rServNum. "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>" .$iId . "<br><b>cook time: </b>" . $rCookTime. " minutes <br>";
		}
	}
    echo $s.'</div>';}?>
    
    
    
<!--     <h4> Recipes <?php echo $name;?>  might like </a> </h4> -->
    
    
  
    <h4 align="center"> <?php echo $name."'s"?> recipes </a> </h4>
<?php
// 	$query="SELECT * FROM Recipe join RecipeTag natural join Tag WHERE tname ='".$tname."'" ;
	$query2=" SELECT * FROM recipe natural join UserDetails WHERE uname ='".$name."' order by rTime DESC" ;
	$result = mysqli_query($con,$query2);
	$results = mysqli_num_rows($result); 
	$s="<p align='center'>";
   	if ($results>0) {
   		while($row = $result->fetch_assoc()) {
   			$rid = $row["rid"];
			$rtitle = $row["rtitle"];
			$rServNum= $row["rServNum"];
			$rCookTime= $row["rCookTime"];
			$resultim = mysqli_query($con, "SELECT RecipeImgDir FROM RecipeImg WHERE rid = '" . $rid. "' limit 1");
			if (mysqli_num_rows($resultim)==1){
				$rowIm = mysqli_fetch_array($resultim);
				$MyPhoto=  $rowIm['RecipeImgDir'];
				$iId = "<img src= 'http://localhost/proj2/Images/".$MyPhoto."'width = 400/>";
			} else {
				$iId = '';
			}
			$s.= '<a href="recipe.php?rid=' . $rid . '">' ."<b>* </b>" . $rtitle. '</a>'." serving No.: </b>" . $rServNum. "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>" .$iId . "<br><b>cook time: </b>" . $rCookTime. " minutes <br>";
		}
	}
    echo $s;   	
?>
      <h4 align="center"> Recipes <?php echo $name;?> visited recently </a> </h4>
<?php
// 	$query="SELECT * FROM Recipe join RecipeTag natural join Tag WHERE tname ='".$tname."'" ;
	$query2=" select distinct rid, rtitle, rServNum, rCookTime from (SELECT * FROM recipe natural join readRecipe WHERE rname ='".$name."' order by logtime DESC ) as a limit 5" ;
// 	echo $query2;
	$result = mysqli_query($con,$query2);
	$results = mysqli_num_rows($result); 
	$s="<p align='center'>";
   	if ($results>0) {
   		while($row = $result->fetch_assoc()) {
   			$rid = $row["rid"];
			$rtitle = $row["rtitle"];
			$rServNum= $row["rServNum"];
			$rCookTime= $row["rCookTime"];
			$resultim = mysqli_query($con, "SELECT RecipeImgDir FROM RecipeImg WHERE rid = '" . $rid. "' limit 1");
			if (mysqli_num_rows($resultim)==1){
				$rowIm = mysqli_fetch_array($resultim);
				$MyPhoto=  $rowIm['RecipeImgDir'];
				$iId = "<img src= 'http://localhost/proj2/Images/".$MyPhoto."'width = 400/>";
			} else {
				$iId = '';
			}
			$s.= '<a href="recipe.php?rid=' . $rid . '">' ."<b>* </b>" . $rtitle. '</a>'." serving No.: </b>" . $rServNum. "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>" .$iId . "<br><b>cook time: </b>" . $rCookTime. " minutes <br>";
		}
	}
    echo $s;   	
?>    
    
    <hr /> 
	<h4>Want to add a recipe?</h4>
    <form action = "addRecipe.php">
    <input type="submit" name = "clickhere" value="Click Here!"/>
    </form> </div> 
    
    <hr/>
   
	
</body>
</html>