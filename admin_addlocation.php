<?php
    session_start();
    if(!isset($_SESSION['admin_id'])){
        header('location:index.php?err=1');
    }
?>
<?php
    error_reporting(0);
    $admin_id=$_SESSION['admin_id'];
    $admin_role=$_SESSION['admin_role'];
    require_once 'connection.php';
    $sql_adminrole="SELECT AdminRole from admin where AdminID='$admin_id' ";
    $adminrole =$connection->query($sql_adminrole);
    $dataadmin=[];
    $dataadmin=$adminrole->fetch_assoc();
    $roleadmin=$dataadmin['AdminRole'];
    $sql_parkadminid="SELECT admin.AdminID, admin.Username FROM admin
                        WHERE admin.AdminRole = 'parkingadmin'
                        AND admin.AdminID NOT IN (
                            SELECT DISTINCT parklocation.AdminID FROM parklocation
                        );";
    $parkadmin_id=$connection->query($sql_parkadminid);
    $data1=[];
    if($parkadmin_id-> num_rows>0){
        while($row1=$parkadmin_id->fetch_assoc()){
            $username=$row1['Username'];
            array_push($data1,$row1);
        }
    }
    $sql_existing = "SELECT LocationName, LocationLat, LocationLong FROM parklocation";
    $result_existing = $connection->query($sql_existing);
    $existing_locations = [];
    while ($row_existing = $result_existing->fetch_assoc()) {
        $existing_locations[] = $row_existing;
    }
    $locations_json=json_encode($existing_locations);
    if(isset($_POST['btnSave'])){
        $err=[];
        if(isset($_POST['location_name']) && !empty($_POST['location_name']) && trim($_POST['location_name']) ){
            $location_name=$_POST['location_name'];
            if(!preg_match('/^[A-Za-z\s]+$/',$location_name)){
                $err['location_name'] =  'Enter valid location name';
            }
        }else{
            $err['location_name']='Please enter location name';
        }
        if(isset($_POST['parking_admin_id']) && !empty($_POST['parking_admin_id']) && $_POST['parking_admin_id']!=0){
            $parking_admin_id=$_POST['parking_admin_id'];
        }else{
            $err['parking_admin_id']='Please select parking admin';
        }
        $location_lat = $_POST['location_lat'] ?? '';
        $location_long = $_POST['location_long'] ?? '';

        if(empty($location_lat) || empty($location_long)){
            $err['location_coordinates'] = 'Please select location from map.';
        } else {
            $sql_check = "SELECT * FROM parklocation WHERE 
                        ABS(LocationLat - '$location_lat') < 0.0001 
                        AND ABS(LocationLong - '$location_long') < 0.0001";
            $result = $connection->query($sql_check);
            if($result->num_rows > 0){
                $err['location_coordinates'] = 'Parking location already exists.';
            }
        }

        if(count($err)==0){
            require_once 'connection.php';
            $sql="insert into parklocation(LocationName,LocationLat,LocationLong,AdminID,TwoWheelSlot,FourWheelSlot)
                values('$location_name','$location_lat','$location_long','$parking_admin_id','0','0')
            ";
            $connection->query($sql);
            if($connection->affected_rows==1 && $connection->insert_id>0){
                $success='New location added successfully';
            }else{
                $error='Location addition failed';
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <?php require_once 'link.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        *{
            margin: 0;
            padding: 0;
            border:none;
            outline: none;
            box-sizing: border-box;
        }
        body{
            background-color: #d4e9ff;
            font-family:"Quicksand",sans-serif;
        }
        .header{          
            background-color:#f2fcff;
            height: 12vh;
            width: 100%;
        }
        .logo{
            display: flex;
            padding-left: 35px;
        }
        .logo i {
           font-size: 30px;
           padding:  25px 5px ;   
        }
        .profile{
            padding-left: 1050px;
        }
        .sidebar{
            position:sticky ;
            width: 250px;
            height: 88vh;
            color:#cbeeff;
            overflow:hidden;
            padding-left: 30px;
            transition: all 0.5s linear;
            background:#22356a;            
        }
        .menu{
            height: 88%;
            position: relative;
            list-style: none;
            padding: 0;
        }
        .menu li{
            padding: 1rem;
            margin: 8px 5px;
            border-radius: 8px;
            transition: all 0.5s ease-in-out;
        }
        .menu li:hover,.menu .active{
            background:#7B8FB5;
            color:#171a3a;
        }
        .menu a{
            color:#cbeeff;
            font-size: 18px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap:1.5rem;
        }
        .menu a span{
            overflow:hidden;
        }
        .menu a i{
            font-size: 1.2rem;
        }
        .main{
            position: absolute;
            top:100px;
            left: 300px;
            color:#22356a;
        }
        .main .mini-nav a{
            color:#22356a;
            text-decoration: none;
        }
        .main .mini-nav span{
            padding: 10px;
            border-radius: 10px;
            background-color:#f2fcff;
            box-shadow:0.5px 0.5px 0.5px 0.5px  #22356a inset;
        }
        .main .mini-nav span:hover,.main .mini-nav .active{
            text-decoration: none;
            padding: 10px;
            border-radius: 10px;
            color:#f2fcff;
            background-color:#22356a;
            box-shadow:1px 1px 1px 1px  #22356a ;
        }
        .main form{
            position: relative;
            padding: 20px;
            width: auto;
            margin: auto;
            height:auto;
        }
        form fieldset{
            height:auto;
            background-color: #f2fcff;
            border-radius:10px;
            box-shadow: 1px 1px 1px 1px #8a98b3;
            border:0.5px solid #8a98b3;
        }
        fieldset legend{
            border:0.5px solid #8a98b3;
            margin-left:5px;
            padding:5px;
            border-radius:10px;
            background-color: #d4e9ff;
            box-shadow: 1px 1px 0.5px 1px #8a98b3 inset;
        }
        form .label-input{
            padding: 10px;
            font-size: 14px;
            height: auto;
        }
        .label-input label{
            display: inline-block;
            width: 150px;
        }
        .label-input input[type=text]{
            padding: 5px;
            border-radius: 5px;
            width: 300px;
            background-color: #d4e9ff;
            box-shadow: 1px 1px 1px 1px #8a98b3 inset;
        }
        .label-input select{
            padding: 5px;
            border-radius: 5px;
            width: 300px;
            background-color: #d4e9ff;
            box-shadow: 1px 1px 1px 1px #8a98b3 inset;
        }
        .label-input .gmapAPI{
            padding: 5px;
            border-radius: 5px;
            width: 450px;
            height:300px;
            background-color: #d4e9ff;
            box-shadow: 1px 1px 1px 1px #8a98b3 inset;
        }
        .error_message_box{
            color:red;
            font-size:12px;
            text-align:right;
        }
        .btn{
            text-align: center;
            padding: 10px;
        }
        .btnSave{
            background-color: green;
            padding: 10px;
            border-radius: 10px;
            color: #e2e5ff;
        }
        .btnClear{
            background-color: red;
            padding: 10px;
            border-radius: 10px;
            color: #e2e5ff;
        }
        .err_msg{
            background-color: red;
            color: #e2e5ff;
            width: 500px;
            margin: auto;
            text-align: center;
            padding: 10px;
        }
        .success_msg{
            background-color: green;
            color: #e2e5ff;
            width: 500px;
            margin: auto;
            text-align: center;
            padding: 10px;
        }
    </style>

</head>
<body>
    <div class="header">
        <div class="logo"> 
            <i class='bx bx-slider'></i>
            <img src="images/fulllogo.png" alt="logo" height="75px"></a>
        </div>
    </div>
    <div class="sidebar">
            <div class="menu">
                <li>
                    <a href="dashboard.php">
                        <i class='bx bxs-dashboard'></i>
                        <span> Dashboard </span>
                    </a>
                </li>
                <?php if($roleadmin=='headadmin'){?>
                    <li class="active">
                        <a href="admin_listlocations.php">
                            <i class="fa-solid fa-location-dot"></i>
                            <span> Locations </span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_listusers.php">
                            <i class="fa-solid fa-user"></i>
                            <span> Users </span>
                        </a>
                    </li>   
                <?php }else{?>
                    <li>
                        <a href="admin_listparkingslots.php">
                            <i class="fa-solid fa-square-parking"></i>
                            <span> Parking Slots </span>
                        </a>
                    </li>   
                    <li>
                        <a href="admin_listbooking.php">
                            <i class="fa-solid fa-clipboard-check"></i>
                            <span> Booking </span>
                        </a>
                    </li>          
                <?php }?>              
                <li class="logout">
                    <a href="admin_logout.php">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span> Logout </span>
                    </a>
                </li>
        </div>
    </div>
    <div class="main">
        <div class="mini-nav">
            <a href="admin_listlocations.php"><span>List</span></a>
            <a href="admin_addparkingadmin.php"><span>Add Admin</span></a>
            <a href="#"><span class="active">Add Location</span></a>
            <!-- <a href="admin_maplocation.php"><span>Map</span></a> -->
        </div>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <fieldset>
                <legend>Add New Location</legend>
                <?php if(isset($error)) {?>
                    <p class="err_msg"><?php echo $error ?></p>
                <?php }?>
                <?php if(isset($success)) {?>
                    <p class="success_msg"><?php echo $success ?></p>
                <?php }?>
                <div class="label-input">
                    <label for="location_name">Location Name</label>
                    <input type="text" name="location_name" id="location_name" value="<?php echo isset($location_name)?$location_name:'' ?>">
                    <div class="error_message_box">
                        <span id="check_location"></span>
                        <?php if(isset($err['location_name'])) { ?>
                            <span class="err_message">
                                <?php echo $err['location_name'] ?>
                            </span>
                        <?php } ?>
                    </div>
                </div>
                <div class="label-input">
                    <label for="parking_admin_id">Assign Parking Admin</label>
                    <select name="parking_admin_id" id="parking_admin_id">
                        <option value="0">Select one</option>
                        <?php foreach($data1 as $key=>$value){ ?>
                            <option value="<?php echo $value['AdminID']; ?>" 
                                <?php echo (isset($parking_admin_id) && $parking_admin_id == $value['AdminID']) ? 'selected' : ''; ?>>
                                <?php echo $value['Username']; ?>
                            </option>
                        <?php } ?>
                    </select>   
                    <div class="error_message_box">
                        <?php if(isset($err['parking_admin_id'])) { ?>
                            <span class="err_message">
                                <?php echo $err['parking_admin_id'] ?>
                            </span>
                        <?php } ?>
                    </div> 
                </div>
                <div class="label-input label-map">
                    <label for="location_coordinates">Location in Map</label>
                    <div class="gmapAPI" id="gmapAPI"></div>
                    <div class="error_message_box">
                        <?php if(isset($err['location_coordinates'])) { ?>
                            <span class="err_message">
                                <?php echo $err['location_coordinates'] ?>
                            </span>
                        <?php } ?>
                    </div>
                    <input type="hidden" id="location_lat" name="location_lat" value="<?php echo isset($location_lat) ? $location_lat : ''; ?>">
                    <input type="hidden" id="location_long" name="location_long" value="<?php echo isset($location_long) ? $location_long : ''; ?>">
                </div>
                <div class="btn">
                    <button type="submit" name="btnSave" class="btnSave">Confirm</button>
                    <button type="clear" name="btnClear" class="btnClear">Clear</button>
                </div>
            </fieldset>
        </form>
    </div>
    <!-- JAVASCRIPT -->
    </script>
    <script>
        var map = L.map('gmapAPI').setView([27.7172, 85.3240], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let marker;
        let selectedLat = null;
        let selectedLng = null;

        const existingLocations = <?php echo $locations_json; ?>;
        const redIcon = L.icon({
            iconUrl: 'images/red_icon.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        existingLocations.forEach(loc => {
            L.marker([loc.LocationLat, loc.LocationLong])
                .addTo(map)
                .bindPopup(loc.LocationName);
        });
        const prevLat = document.getElementById('location_lat').value;
        const prevLng = document.getElementById('location_long').value;

        if (prevLat && prevLng) {
            const latLng = L.latLng(parseFloat(prevLat), parseFloat(prevLng));
            marker = L.marker(latLng,{icon:redIcon}).addTo(map);
        }

        map.on('click', function (e) {
        const lat = e.latlng.lat.toFixed(6);
        const lng = e.latlng.lng.toFixed(6);

        const exists = existingLocations.some(loc => {
            return (Math.abs(loc.LocationLat - lat) < 0.0001) && (Math.abs(loc.LocationLong - lng) < 0.0001);
        });

        if (exists) {
            alert("Location already exists at this position!");
            return;
        }

        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng,{icon:redIcon}).addTo(map);
        }
        document.getElementById('location_lat').value = lat;
        document.getElementById('location_long').value = lng;
    });
    </script>
    <script type="text/javascript" src="file/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#location_name').keyup(function(){
                var check_location=$(this).val();
                $.ajax({
                    url:'admin_checkparkinglocationname.php',
                    data:{'location_name':check_location},
                    dataType:'text',
                    method:'post',
                    success:function(resp){
                        $('#check_location').html(resp);
                        if(resp=='Location available'){
                            $('#check_location').css({color:'green'})
                        }else{
                            $('#check_location').css({color:'red'})
                        }
                    }
                });
            });                 
        });
    </script>
</body>
</html>