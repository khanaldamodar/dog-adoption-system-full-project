<?php require_once 'check_admin_login.php';?>
 <?php
 
if (isset($_POST['btnAdd'])) {
 //assign error to $err array
 $err =[];
//  $photo=[];
 
  
 if (isset($_POST['name']) && !empty($_POST['name']) && trim($_POST['name']))  {
      $name = $_POST['name'];
 
      } else {
      $err['name'] = 'Please enter name';
      }

      //check dog age
if (isset($_POST['age']) && !empty($_POST['age']) && trim($_POST['age'])) {
    $age = $_POST['age'];
    
    } else {
    $err['age'] = "**Please enter the age";
    }
    //check dog breed
    if (isset($_POST['breed']) && !empty($_POST['breed']) && trim($_POST['breed'])) {
    $breed = $_POST['breed'];
    if (!preg_match('/^[A-Za-z\s]+$/', $_POST['breed'])) {
      $err['breed'] = "Please enter a valid breed";
    }
    } else {
    $err['breed'] = "Please enter the breed";
    }
    //check dog color
    if (isset($_POST['color']) && !empty($_POST['color']) && trim($_POST['color'])) {
      $color = $_POST['color'];
      if (!preg_match('/^[A-Za-z\s]+$/', $_POST['color'])) {
          $err['color'] = "Please enter a valid  color";
      }
    } else {
      $err['color'] = "Please enter the  color";
    }
    //check dog size
    // if (isset($_POST['size']) && !empty($_POST['size'])) {
    //   $size = $_POST['size'];
    //  } else {
    //   $err['size'] = "select size";
    // }
     
    //check dog gender
    // if (isset($_POST['gender']) && !empty($_POST['gender']) && trim($_POST['gender'])) {
    // if (!preg_match('/^[A-Za-z\s]+$/', $_POST['gender'])) {
    //       $err['gender'] = "Please enter a  gender";
    // }
    //  $gender = $_POST['gender'];
    
    // } else {
    // $err['gender'] = "Please enter the gender ";
    // }
    //check image
    // print_r($_FILES);
    if ($_FILES['photo']['error'] == 0) {
        if ($_FILES['photo']['size'] <= 1000000) {
          $imFormat = ['image/png','image/jpg','image/jpeg'];
          if (in_array($_FILES['photo']['type'], $imFormat)) {
            $fname = uniqid() . '_' . $_FILES['photo']['name'];
          move_uploaded_file($_FILES['photo']['tmp_name'],'image/' . $fname );
          // echo 'Upload success';
          } else {
            $err['photo'] = 'Select Valid Image Format(png,jpg,JPEG)';
          }
            } else {
            $err['photo'] = 'Select Valid Image Size (less than 1MB)';
           }
         } else{
            $err['photo'] = 'Select Valid Image';
         }

//check dog message
if (isset($_POST['message']) && !empty($_POST['message']) && trim($_POST['message'])) {
    $message = $_POST['message'];
  
 } else {
   $err['message'] = "Please enter the Message";
 }
 $size = $_POST['size'];
 $gender = $_POST['gender'];
 $status = $_POST['status'];
$created_at = date('Y-m-d H:i:s');
$created_by = $_SESSION['admin_id'];

if (count($err) ==0) {
        require_once 'connection.php';
       $sql = "INSERT INTO dogs
       ( name,age,breed,color,size,gender,status,message,created_at,created_by,image) VALUES('$name','$age','$breed','$color','$size','$gender','$status','$message','$created_at','$created_by','$fname')";
        //execute query
       $connection->query($sql);
      
if ($connection->affected_rows == 1 && $connection->insert_id > 0){
        $success = 'category insert success';
       }
       else{
        $error = 'category insert failed';
       }
       }
}


?>


<!DOCTYPE html>
<html>
    <head>
        <title>Dog category Create </title>
        <link rel="stylesheet" href="style.css">

    </head>
<body>

<div class="wrapper">
<!-- <h5>Dogs category Create</h5> -->
    <div>
    <form action="" method="post" id="category_form" enctype="multipart/form-data">
        <fieldset> 
            <?php if(isset($error)) {?>
                <p class="error_message"><?php echo $error ?> </p>
            <?php } ?>
            <?php if(isset($success)) {?>
                <p class="success_message"><?php echo $success ?> </p>
            <?php } ?>
            <?php if(isset($_GET['err']) && $_GET['err']==1) {?>
                <p class="error_message">Please login to continue. </p>
            
            <?php } ?>
            <legend> Category Dog Form</legend>
            <div class="form-group">
                <label for="name"> Name </label>
                <input type="text" name="name" id="name" placeholder="Enter name"
                value="<?php echo isset($name)?$name:'' ?>">
                <?php if (isset($err['name'])) { ?>
                <span class="error"><?php echo $err['name'] ?> </span>
                <?php } ?>
               
            </div>

            <div class="form-group">
                <label for="age"> Age </label>
                <input type="text" name="age" id="age" placeholder="Enter age"
                value="<?php echo isset($age)?$age:'' ?>">
                <?php if (isset($err['age'])) { ?>
                <span class="error"><?php echo $err['age'] ?> </span>
                <?php } ?>
            </div>

            <div class="form-group">
                <label for="breed"> Breed </label>
                <input type="text" name="breed" id="breed" placeholder="Enter breed"
                value="<?php echo isset($breed)?$breed:'' ?>">
                <?php if (isset($err['breed'])) { ?>
                <span class="error"><?php echo $err['breed'] ?> </span>
                <?php } ?>
            </div>

            <div class="form-group">
                <label for="color"> Color </label>
                <input type="text" name="color" id="color" placeholder="Enter color"
                value="<?php echo isset($color)?$color:'' ?>">
                <?php if (isset($err['color'])) { ?>
                <span class="error"><?php echo $err['color'] ?> </span>
                <?php } ?> 
            </div>

            <div class="form-group">
                <label for="size"> Size </label>
                <select class="size" name="size" id="size" placeholder="Select size" style="padding:10px;"  >
                <option >select size</option>
                    <option value="large">large</option>
                    <option value="medium"> medium</option>
                    <option value="small">small</option>
                 </select>
            </div>

            <div class="form-group">
                <label for="gender">gender</label>
                <input type="radio" name="gender" value="male" >Male
                <input type="radio" name="gender"  value="female"> Female
            </div>
            <div class="form-group"> 
            <label>Dogs Image</label>
            <input type="file" name="photo" value="<?php echo isset($fname)?$fname:''?>"/>
            <?php  if (isset($err['photo'])){?>
            <span class="error"><?php echo $err['photo'] ?></span>
            <?php }?>
        </div> 
       
        <div class="form-group"> 
          <label>Dogs Message</label>
            <textarea type="text" placeholder="Type Message" name="message" value="<?php echo isset($message)?$message:''?>"></textarea>
            <?php  if (isset($err['message'])){?>
            <span class="error"><?php echo $err['message'] ?></span>
            <?php }?>
        </div>

            <div class="form-group">
                <label for="status">Status</label>
                <input type="radio" name="status"  value="1">Active
                <input type="radio" name="status"  value="0" checked> Deactive
               
            </div>
            
            <div class="form-group">
                <input type ="submit" name="btnAdd" id="add" value="Add">
                 <input type="reset" name="btnReset" id="reset" value="Clear">
             </div>
        </fieldset>
    </from>
        
    </div>
</div>

</body>
</html> 