<?php

session_start();

if (isset($_SESSION['id'])) {



   include('include/config.php');
   include('include/header.php');
   include('include/navbar.php');

   $do = '';


   if (isset($_GET['do'])) {
      $do = $_GET['do'];
   } else {


      $do = 'center';
   }





   if ($do == 'center') {
?>


      <center>
         <h2 style="line-height: 100px">Center Managment</h2>
      </center>
      <div class="container">
         <a type="button" style="margin-bottom: 10px" class="btn btn-primary " href="?do=add">Add new center</a>
         <table class="table table-striped">

            <thead>
               <tr class="thead-dark">

                  <th scope="col">center name</th>
                  <th scope="col">stage</th>
                  <th scope="col">Day</th>
                  <th scope="col">From</th>
                  <th scope="col">To</th>
                  <th scope="col">Control</th>
               </tr>
            </thead>
            <tbody>
               <?php
               $stmt = $conn->prepare('SELECT * FROM center INNER JOIN time ON center.time = time.id ');
               $stmt->execute();
               $rows = $stmt->fetchAll();

               foreach ($rows as $row) {
               ?>
                  <tr>

                     <td> <?php echo ucWords($row['center_name'], " "); ?> </td>
                     <td> <?php


                           if ($row['stage'] == 1) {
                              echo  $row['stage'] . ' primary';
                           } elseif ($row['stage'] == 2) {
                              echo $row['stage'] . ' preparatory';
                           } else {

                              echo $row['stage'] .  ' secondary';
                           }



                           ?> </td>
                     <td> <?php echo ucWords($row['day'], " "); ?> </td>
                     <td> <?php echo ucWords($row['from'], " "); ?> </td>
                     <td> <?php echo ucWords($row['to'], " "); ?> </td>
                     <td><a type="button" class="btn btn-danger btn-sm" href="?do=delete&id=<?php echo $row['id'] ?>">Delete</a>
                        <a type="button" class="btn btn-primary btn-sm" href="?do=edit&id=<?php echo $row['id'] ?>">Edit</a></td>

                  </tr>
               <?php



               }

               ?>
            </tbody>
         </table>

      </div>
   <?php

   } elseif ($do == 'add') {

   ?>



      <center><br>
         <h2>Creat new time</h2><br>
      </center>

      <div class="container">



         <form action="?do=insert" method="post">
            <div class="form-row">
               <div class="form-group col-md-4">
                  <label for="inputEmail4"> Center name</label>
                  <input type="text" class="form-control" name="name" id="inputEmail4">
               </div>

               <div class="form-group col-md-6">
                  <label for="inputPassword4">Stage</label>
                  <select class="form-control " name="stage" required>
                     <option value="" selected disabled>please choose student stage </option>
                     <option value="1"> 1 </option>
                     <option value="2"> 2 </option>
                     <option value="3"> 3 </option>
                  </select>

               </div>


               <!-- <div class="form-group col-md-2">
                  <label for="inputPassword4">Year</label>
                  <select class="form-control " name="year" required>
                     <option value="" selected disabled> student year </option>
                     <option value="1">1</option>
                     <option value="2">2</option>
                     <option value="3">3</option>

                  </select>
               </div> -->


               <div class="form-group col-md-12">



                  <label for="inputPassword4">Day</label>
                  <select class="form-control " name="day" required>

                     <option selected disabled>please choose day</option>

                     <option value='Sunday'> Sunday </option>
                     <option value='Monday'> Monday </option>
                     <option value='Tuesday'> Tuesday </option>
                     <option value='Wednesday'> Wednesday </option>
                     <option value='Thursday'> Thursday </option>
                     <option value='Friday'> Friday </option>
                     <option value='Saturday'> Saturday </option>

                  </select>

               </div>


               <div class="form-group col-md-6">
                  <label for="inputPassword4">From</label>
                  <input type="time" name="from" class="form-control" id="inputEmail4">
               </div>
               <div class="form-group col-md-6">
                  <label for="inputPassword4">To</label>
                  <input type="time" name="to" class="form-control" id="inputEmail4">
               </div>
            </div>

            <button type="submit" class="btn btn-primary">Add</button>
         </form>


      </div>


   <?php
   } elseif ($do == 'insert') {

      $name  = $_POST['name'];
      $from1 = $_POST['from'];
      $to2   = $_POST['to'];
      $day   = $_POST['day'];
      $from  = date('h:i A', strtotime($from1));
      $to    = date('h:i A', strtotime($to2));


      $stmt = $conn->prepare('INSERT INTO `time` (`day`,`from`, `to`)
                                                  
                                   VALUES(
                                
                                      :day,  :from, :to
                                        
                                        )');


      $stmt->execute(array('day' => $day,  'from' => $from, 'to' => $to));



      $stmt = $conn->prepare('Select id from time
                        
                        where day=? AND time.from=? AND time.to=?
                        ');

      $stmt->execute(array($day, $from, $to));


      $row = $stmt->fetchColumn();


      $stage = $_POST['stage'];
      // $year = $_POST['year'];
      $stmt = $conn->prepare('INSERT INTO `center` (`center_name`,`time`,`stage`)
                                                  
                                   VALUES(
                                
                                      :center_name,  :time ,:stage 
                                        
                                        )');


      $stmt->execute(array('center_name' => $name, 'time' => $row, 'stage' => $stage));



   ?>
      <div class="container" style="margin-top: 50px">
         <div class="alert alert-success alert-dismissible fade show" role="alert">
            <center>New center added successfully</center>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <center> <br>
            <p style="color:gray"> you will be redirected in 3 seconds</p>
         </center>
      </div>


   <?php

      header('Refresh:3; url=center.php');
   } elseif ($do == 'delete') {


      $stmt = $conn->prepare('delete from time where id=?');
      $stmt->execute(array($_GET['id']));
   ?>
      <div class="container" style="margin-top: 50px">
         <div class="alert alert-success alert-dismissible fade show" role="alert">
            <center> center deleted successfully</center>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <center> <br>
            <p style="color:gray"> you will be redirected in 3 seconds</p>
         </center>
      </div>
   <?php

      header('Refresh:3; url=center.php');
   } elseif ($do == 'edit') {

      $stmt = $conn->prepare('SELECT * FROM center INNER JOIN time ON center.time = time.id  where time=?');
      $stmt->execute(array($_GET['id']));
      $row = $stmt->fetch();
   ?>


      <center><br>
         <h2>Edit <?php echo $row['center_name'] ?> center </h2><br>
      </center>

      <div class="container">



         <form action="?do=update" method="post">
            <div class="form-row">
               <div class="form-group col-md-6">
                  <label for="inputEmail4"> Center name</label>
                  <input type="text" class="form-control" value=" <?php echo $row['center_name'] ?>" name="name" id="inputEmail4">
               </div>


               <div class="form-group col-md-6">
                  <label for="inputPassword4">Day</label>
                  <select class="form-control " name="day" required>



                     <option value='Sunday' <?php if ($row['day'] == 'Sunday') {
                                                echo  'selected';
                                             } ?>> Sunday </option>
                     <option value='Monday' <?php if ($row['day'] == 'Monday') {
                                                echo  'selected';
                                             } ?>> Monday </option>
                     <option value='Tuesday' <?php if ($row['day'] == 'Tuesday') {
                                                echo  'selected';
                                             } ?>> Tuesday </option>
                     <option value='Wednesday' <?php if ($row['day'] == 'Wednesday') {
                                                   echo  'selected';
                                                } ?>> Wednesday </option>
                     <option value='Thursday' <?php if ($row['day'] == 'Thursday') {
                                                   echo  'selected';
                                                } ?>> Thursday </option>
                     <option value='Friday' <?php if ($row['day'] == 'Friday') {
                                                echo  'selected';
                                             } ?>> Friday </option>
                     <option value='Saturday' <?php if ($row['day'] == 'Saturday') {
                                                   echo  'selected';
                                                } ?>> Saturday </option>

                  </select>

               </div>


               <div class="form-group col-md-6">
                  <label for="inputPassword4">From</label>
                  <input type="time" name="from" class="form-control" id="inputEmail4">
               </div>
               <div class="form-group col-md-6">
                  <label for="inputPassword4">To</label>
                  <input type="time" name="to" class="form-control" id="inputEmail4">
               </div>
            </div>
            <input type="text" name="id" value="<?php echo $_GET['id'] ?>" class="form-control" id="inputEmail4" hidden>
            <button type="submit" class="btn btn-primary">update</button>
         </form>


      </div>
   <?php


   } elseif ($do == 'update') {


      $from  = date('h:i A', strtotime($_POST['from']));
      $to    = date('h:i A', strtotime($_POST['to']));

      $stmt = $conn->prepare('UPDATE `center` SET `center_name`=?  WHERE `time`=? ');
      $stmt->execute(array($_POST['name'], $_POST['id']));

      $stmt = $conn->prepare('UPDATE `time` SET `day`=? , `from`=? , `to`=?  WHERE `id`=? ');
      $stmt->execute(array($_POST['day'], $from, $to, $_POST['id']));
   ?>

      <div class="container" style="margin-top: 50px">
         <div class="alert alert-success alert-dismissible fade show" role="alert">
            <center> Edited successfully</center>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <center> <br>
            <p style="color:gray"> you will be redirected in 3 seconds</p>
         </center>
      </div>
<?php

      header('Refresh:3; url=center.php');
   } else {
   }














   include('include/footer.php');
} else {


   header('location:login.php');
}
?>