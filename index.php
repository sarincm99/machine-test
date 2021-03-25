<!doctype html>
<html>
    <head>
    <script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
        <title>Import CSV file data to the MySQL using PHP</title>
        <link href="style.css" type="text/css" rel="stylesheet">

        <?php
        include "config.php";
        session_start();
        unset ($_SESSION["errors"]);

        //print_r($_POST);

        if(isset($_POST['but_import'])){
            //print_r($_FILES);
           // if(empty($_FILES)){
             //   $_SESSION['errors'] = 'Please Upload a file';   
            //}
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["importfile"]["name"]);

            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

            $uploadOk = 1;

            if($imageFileType != "csv" ) {
                $uploadOk = 0;
                $_SESSION['errors'] = 'Please Upload Csv file';
            }



            if ($uploadOk != 0) {
                if (move_uploaded_file($_FILES["importfile"]["tmp_name"], $target_dir.'importfile.csv')) {

                    // Checking file exists or not
                    $target_file = $target_dir . 'importfile.csv';
                    $fileexists = 0;
                    if (file_exists($target_file)) {
                        $fileexists = 1;
                    }
                    if ($fileexists == 1 ) {

                        // Reading file
                        $file = fopen($target_file,"r");
                        $i = 0;

                        $importData_arr = array();
                       

                        while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
                            $num = count($data);

                            for ($c=0; $c < $num; $c++) {
                                $importData_arr[$i][] = mysqli_real_escape_string($con,$data[$c]);
                            }
                            $i++;
                        }
                        fclose($file);

                        $skip = 0;
                        $emp_code_no=$_POST['emp_code_no'];
                        $emp_code_name=$_POST['emp_code_name'];
                        $emp_code_dep=$_POST['emp_code_dep'];
                        $emp_code_age=$_POST['emp_code_age'];
                        $emp_code_exp=$_POST['emp_code_exp'];
                        
                        // insert import data
                        foreach($importData_arr as $data){
                            if($skip != 0){
                                $emp_code = $data[$emp_code_no];
                                $emp_name = $data[$emp_code_name];
                                $emp_dep = $data[$emp_code_dep];
                                $age = $data[$emp_code_age];
                                $emp_exp = $data[$emp_code_exp];

                                // Checking duplicate entry
                               /* $sql = "select count(*) as allcount from user where username='" . $username . "' and fname='" . $fname . "' and  lname='" . $lname . "' and email='" . $email . "' ";

                                $retrieve_data = mysqli_query($con,$sql);
                                $row = mysqli_fetch_array($retrieve_data);
                                $count = $row['allcount'];

                                if($count == 0){ */
                                    // Insert record                          
                                    $insert_query = "insert into employees(emp_code,emp_name,emp_dept,emp_age,emp_exp) values('".$emp_code."','".$emp_name."','".$emp_dep."','".$age."','".$emp_exp."')";
                                    //echo $insert_query;
                                    if(!mysqli_query($con,$insert_query)){
                                        echo("Error description: " . mysqli_error($con));  
                                    }

                                    $_SESSION['errors'] = 'Data uploaded sucessfully !!!';
                               // }
                            }
                            $skip ++;
                        }
                        $newtargetfile = $target_file;
                        if (file_exists($newtargetfile)) {
                            unlink($newtargetfile);
                        }
                    }


                }
            }
        }
        ?>
    </head>
    <body>
    <!-- Import form (start) -->
    <div class="popup_import">
    <h1 class="format">Employee Data</h1>
        <form method="post" action="" enctype="multipart/form-data" id="import_form">
            <table width="100%">


         
        
                <tr>
                    <td colspan="2">
                        <input type='file' name="importfile" id="importfile">
                    </td>
                    <td colspan="2" ><a href="import_example.csv" target="_blank">Download Sample</a></td>

                </tr>
                <tr>
                    <td colspan="2"><b>Instruction : Please select CSV coloum number for each field </b><br/>
                        <table>
                            <tr>
                            <td>Employee Code: </td>
                            <td>
                            <select name="emp_code_no" id="emp1" class="required">
                            <option value="null">--select--</option>
                            <option value="0">1</option>
                            <option value="1">2</option>
                            <option value="2">3</option>
                            <option value="3">4</option>
                            <option value="4">5</option>
                            </select>
                            </td>
                            </tr>
                            <tr>
                            <td>Employee Name: </td>
                            <td>                           
                            <select name="emp_code_name" id="emp2">
                            <option value="null">--select--</option>
                            <option value="0">1</option>
                            <option value="1">2</option>
                            <option value="2">3</option>
                            <option value="3">4</option>
                            <option value="4">5</option>
                            </select></td>
                            </tr>
                            <tr><td>Department: </td>
                            <td>                            
                            <select name="emp_code_dep" id="emp3">
                            <option value="null">--select--</option>
                            <option value="0">1</option>
                            <option value="1">2</option>
                            <option value="2">3</option>
                            <option value="3">4</option>
                            <option value="4">5</option>
                            </select></td>
                            </td>
                            </tr>
                            <tr><td>Age: </td><td>                           
                             <select name="emp_code_age" id="emp4">
                             <option value="null">--select--</option>
                            <option value="0">1</option>
                            <option value="1">2</option>
                            <option value="2">3</option>
                            <option value="3">4</option>
                            <option value="4">5</option>                            </select></td>
                            </td></tr>
                            <tr><td>Experience in the organization: </td><td>
                            <select name="emp_code_exp" id="emp5">
                            <option value="null">--select--</option>
                            <option value="0">1</option>
                            <option value="1">2</option>
                            <option value="2">3</option>
                            <option value="3">4</option>
                            <option value="4">5</option>
                            </select></td>
</td></tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" ><input type="submit" id="but_import" name="but_import" value="Import"></td>
                </tr>
            </table>
        <?php// if(isset($_SESSION['errors'])){ ?>
          <span class="error format" id="msg"><?php if(isset($_SESSION['errors'])){echo $_SESSION['errors'];} ?></span>
      <?php //} ?>
        </form>
    </div>
    <!-- Import form (end) -->

    <!-- Displaying imported users -->
    <table border="1" id="userTable">
        <tr>
            <td>Id</td>
            <td>Employee Code</td>
            <td>Employee Name</td>
            <td>Department</td>
            <td>Age</td>
            <td>Experience in the organization</td>
        </tr>
    <?php
    $sql = "select * from employees order by id desc limit 10";
    $sno = 1;
    $retrieve_data = mysqli_query($con,$sql);
    while($row = mysqli_fetch_array($retrieve_data)){
        $id = $row['id'];
        $emp_code = $row['emp_code'];
        $emp_name = $row['emp_name'];
        $emp_dept = $row['emp_dept'];
        $emp_age = $row['emp_age'];
        $emp_exp = $row['emp_exp'];

        echo "<tr>
            <td>".$sno."</td>
            <td>".$emp_code."</td>
            <td>".$emp_name."</td>
            <td>".$emp_dept."</td>
            <td>".$emp_age."</td>
            <td>".$emp_exp."</td>

        </tr>";
        $sno++;
    }
    ?>
        </table>
<script>
$(document).ready(function(){
jQuery("#but_import").click(function(e){
   var eemp1=$( "#emp1 option:selected" ).val();
   var eemp2=$( "#emp2 option:selected" ).val();
   var eemp3=$( "#emp3 option:selected" ).val();
   var eemp4=$( "#emp4 option:selected" ).val();
   var eemp5=$( "#emp5 option:selected" ).val();
   if(eemp1=="null" || eemp2=="null" || eemp3=="null" || eemp4=="null" || eemp5=="null"){
    $("#msg").html('Please match csv fields') 
    return false;
   }
   var vidFileLength = $("#importfile")[0].files.length;
if(vidFileLength === 0){
    $("#msg").html('Please upload a csv file') 
    return false;
}

});
});

</script>


    </body>
</html>