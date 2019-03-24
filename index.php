<?php

$host = "localhost";
$usename = "root";
$password = "";
$dbname = "test";

$conn = mysqli_connect($host,$usename,$password,$dbname);

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

mysqli_set_charset($conn,"utf8");

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <title>Document</title>
</head>
<body>

<br>
<br>


<div class="container">
    <table border="2px" style="text-align:center">
        <thead >
        <th  style="text-align:center">Name </th>
        <th  style="text-align:center">Type </th>
        <th  style="text-align:center">Created At</th>
        </thead>
</div>

<div class="container" align="">
    <div class="row">
        <div class="col-md-12">


            <form class="form-horizontal" action="" method="POST">


                <div class="form-group">
                    <label for="input" class="col-sm-2" >Name:</label>
                    <div class="col-sm-10">
                        <input type="text" class="" name="name" required="required">
                    </div>
                </div>
                <br>
                <br>

                <div class="form-group">
                    <label for="input" class="col-sm-2 control-label">Type:</label>
                    <div class="col-sm-10">




                        <select name="type" id="type"  required="required" style="width:19%">

                            <!-- get data from the other table in dropdown -->

                            <?php

                            $sql = "SELECT type FROM `name`  ORDER BY id DESC LIMIT 1";     // get last saved item value in dropdown
                            $res = mysqli_query($conn,$sql);
                            $item=0;
                            while ($print = mysqli_fetch_array($res)) {
                                $item = $print['type'];
                            }


                            $sql="SELECT *  FROM `type`";      // loading select(dropdown list)

                            $res = mysqli_query($conn,$sql);
                            $niz = array();


                                while ($print = mysqli_fetch_array($res)) {
                                    echo '<option  value="' . $print['type'] . '" ' . (($print['type'] == $item) ? 'selected="'.$item.'"' : "") . ' >' . $print['type'] . '</option>';

                                }

                            ?>

                          </select>

                    </div>
                </div>
                <br>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" name="submit">Add Name</button>
                    </div>
                </div>

            </form>



        </div>
    </div>
</div>
<br>

<!--second form for saving "type" both in database and in frontend select list -->

<form action="" method="post">
    <div class=" form-group" align="">
        <label for="input" class="aa col-sm-2 control-label">Add new type in dropdown list:</label>
        <div class="col-sm-10">
            <input type="text" class="" name="new" required="required" >
            <button type="submit" class="btn btn-success" name="submit1">Add Type</button>
        </div>
    </div>

</form>
<br>
<br>
<br>

 <!-- printing out all elements from table "name"-->

<?php


$sql = "SELECT * FROM `name`";
$sql .= " ORDER BY created_at DESC";   //sort by the time created from last one to first one
$res = mysqli_query($conn,$sql);




//check if the database is emtpy

if($res->num_rows === 0)
{
    $text="No names";
    echo "<tr>";
    echo "<td>" .$text. "</td>";
    echo "<td>" . $text . "</td>";
    echo "<td>" . $text . "</td>";
    echo "<tr>";
}

// printing the data

    while ($print = mysqli_fetch_array($res)) {

            echo "<tr>";
            echo "<td>" . $print["name"] . "</td>";
            echo "<td>" . $print["type"] . "</td>";
            echo "<td>" . $print["created_at"] . "</td>";
            echo "<tr>";

    }

echo "</table>";
?>

<br>
<br>

<!-- second table  -->

<div class="container">
    <table  border="1px" style="text-align:center">
        <thead>
        <th  style="text-align:center">Type</th>
        <th  style="text-align:center"> Counter</th>
        </thead>
</div>

</body>
</html>

<?php

// loading new data from forms to database

$time = date( 'd-m-Y H:i:s');

if(isset($_POST["submit"])) {

        $sql = "INSERT INTO name (name, type,created_at)
VALUES ('" . $_POST["name"] . "','" . $_POST["type"] . "','".$time."')";

    if ($conn->query($sql) === TRUE) {
        echo "<meta http-equiv='refresh' content='0'>";

        echo '<script language="javascript">';
        echo 'alert("Successfully Added!")';
        echo '</script>';
    } else {

        echo '<script language="javascript">';
        echo 'alert("Error!")';
        echo '</script>';
    }
}

// second form for adding "type" in  "type" database and in frontend select list

if(isset($_POST["submit1"])) {
    if (isset($_POST["new"])) {

        $new = $_POST["new"];
        $sql = "SELECT * FROM `type` where type = '$new'";
        $res = mysqli_query($conn, $sql);

        if (mysqli_num_rows($res) == 0)    //checking if the same name exists in database
        {
            $sql = "INSERT INTO type(type)
            VALUES ('" . $_POST["new"] . "')";

            if ($conn->query($sql) === TRUE) {
                echo "<meta http-equiv='refresh' content='0'>";

                echo '<script language="javascript">';
                echo 'alert("Type successfully added!")';
                echo '</script>';
            } else {

                echo '<script language="javascript">';
                echo 'alert("Error!")';
                echo '</script>';
            }
        }
        else  {
            echo '<script language="javascript">';
            echo 'alert("Error! Name already exists! ")';
            echo '</script>';
        }

    }
}

?>

<?php

//save the table "name" into nizA(array) without duplicating same items

$sql = "SELECT DISTINCT type FROM `name`";
$res = mysqli_query($conn,$sql);

$nizA=array();
while ($print = mysqli_fetch_array($res))
{
    $nizA[] = $print['type'];
}
sort($nizA);    // sort by the name
$lengthA = count($nizA);


//save the  type(from table "Name") into nizB[array] with all items including duplicates

$sql = "SELECT * FROM `name`";
$res = mysqli_query($conn,$sql);

$nizB=array();
while ($print = mysqli_fetch_array($res))
{
    $nizB[] = $print['type'];
}

$lengthB = count($nizB);

 //nizC(array) will count how many times some type is selected

$nizC=array();

for($x=0; $x<$lengthA ; $x++)
{
    $nizC[]=0;
}

//comparing same types from array nizA and array nizB ,and saving every time each "type" repeat

for($x=0; $x<$lengthA ; $x++)
{
    for($y=0; $y<$lengthB; $y++)
    {
        if($nizA[$x] == $nizB[$y])
        {

          $nizC[$x]+=1;
        }
    }
}

//printing out second table



for($x=0; $x<$lengthA; $x++)
{

    echo "<tr>";
    echo "<td>" . $nizA[$x] . "</td>";
    echo "<td>" . $nizC[$x] . "</td>";
    echo "<tr>";

}

echo "<tr>";
echo "<th style='text-align:center''>" . "Sum: " . "</th>";
echo "<td >" . $lengthB . "</td>";
echo "</tr>";
echo "</table>";

mysqli_close($conn);

?>


				
