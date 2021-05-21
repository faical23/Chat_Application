<?php
    session_start();
    include "../model/db.php";

    if(!empty($_SESSION["user_login"])){
        $id = $_GET["id"];
        
        $user_enline = new CRUD("users");
        $result = $user_enline->select("",["id" => $id]);
        foreach($result as $value){
            $user_name = $value["name"];
            $user_pic = $value["user_pic"];
            $UserId = $value["id"];
        }
    }
    else{
        header('Location:../vue/index.php');
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>ChatPage</title>
</head>
<body>
<input type ="text" value="<?Php echo $UserId?>" id='UserId'>
<input type ="text" value="<?Php echo $_GET['connect']?>" id='FriendId'>
<section class="myChat">
    <div class="SideBare">
        <div class="profile">
            <div class="user_img">
                <h1 >Profile</h1>
                <img class="UserPicProfile"src="<?php echo $user_pic?>">
            </div>
            <div class="nickname">
                <h6>Your nickname</h6>
                <h2 class="UserNameProfil"><?php echo $user_name;?></h2>
            </div>
            <div class="user_status">
                <h6>Status</h6>
                <select name="status" id="status" >
				<?php
					if(isset($_SESSION['available']) || (!isset($_SESSION['available']) && !isset($_SESSION['Not available']))){
				?>
					<option value="available">available</option>
					<option value="Not available">Not available</option>
				<?php
					}
					else if(isset($_SESSION['Not available'])){
				?>
					<option value="Not available">Not available</option>
					<option value="available">available</option>
				<?php
					}
				?>
                </select>
            </div>
        </div>
        <div class="chat_users">
            <h1 class="name_message">Discussions<h1>
			<div class="all_message">
			<?php
					$arr =[];
					$all_message = "";
					$user_enline = new CRUD("chat");
					$result = $user_enline->select_msg_u_send();
					foreach($result as $value){
						$was_connect_with = $value["id_message_going"];
						if(!($was_connect_with == $UserId)){
							array_push($arr,$was_connect_with);
						}
					}
					$result = $user_enline->select_msg_u_recu();
					foreach($result as $value){
						$was_connect_with = $value["id_message_comming"];
						if(!(in_array($was_connect_with,$arr))){
							array_push($arr,$was_connect_with);
						}
					}
					$arr_reverse = array_reverse($arr, false);

					for($i = 0 ; $i <count($arr_reverse) ; $i++){
						//// select name and pic
						 $user_enline = new CRUD("users");
						$result = $user_enline->select("", ["id" => $arr_reverse[$i]]);
						foreach($result as $value){
							$pic_connect_with = $value["user_pic"];
							$name_connect_with = $value["name"];
							$status_connect_with = $value['status'];
						}
						/////////// select last msg
						 $result = $user_enline->select_last_Discussions($UserId ,$arr_reverse[$i]);
						foreach($result as $value){
							$last_message = $value["message"];
							if($arr_reverse[$i]== $value["id_message_comming"]){
			?>
								<div class="user_message" onclick="select(<?php echo $arr_reverse[$i]?>); select_message(<?php echo $UserId?>,<?php echo $arr_reverse[$i]?>); read_message(<?php echo $UserId?>,<?php echo $arr_reverse[$i]?>)">
										<div class="img_user">
											<img src="<?php echo  $pic_connect_with?>">
										</div>
										<div class="name_user">
											<h5><?php echo $name_connect_with?></h5>
			<?php
						if($value['read_msg'] == 0){
			?>
											<p class='user_message_recu' id='<?php echo "message" . $arr_reverse[$i]?>'><?php echo  $last_message . " ... "?></p>
			<?php
						}
						else{
			?>
											<p  id='<?php echo $arr_reverse[$i]?>'><?php echo $last_message . " ... "?></p>

			<?php
						}
			?>
										</div>
										<div class="status_users">
			<?php
						if($status_connect_with == 1){
			?>
											<i class="fa fa-circle" style='color:green'></i>
			<?php
						}
						else{
			?>
											<i class="fa fa-circle" style='color:red'></i>

			<?php
						}
			?>
										</div>
								</div>
			<?php
							}
							else{
			?>
								<div class="user_message" onclick="select(<?php echo $arr_reverse[$i]?>); select_message(<?php echo $UserId?>,<?php echo $arr_reverse[$i]?>)">
										<div class="img_user">
											<img src="<?php echo  $pic_connect_with?>">
										</div>
										<div class="name_user">
											<h5><?php echo $name_connect_with?></h5>
											<p ><?php echo  "You : " . $last_message . " ... "?></p>
										</div>
										<div class="status_users">
										<?php
						if($status_connect_with == 1){
			?>
											<i class="fa fa-circle" style='color:green'></i>
			<?php
						}
						else{
			?>
											<i class="fa fa-circle" style='color:red'></i>

			<?php
						}
			?>							</div>
								</div>
			<?php
							}
						}
					}
					

			?>
			</div>
        </div>
        <div class="user_enline">
				<h1 class="name_message">Friends<h1>
					<form class="zone_search">
						<input type="text" placeholder="Search ..." id='user_enline_input' onkeyup="SearchUser()">
					</form>

				

				
			<div class="all_friends">

			<!-- space user enline -->
						<?php
							$user_enline = new CRUD("users");
							$result = $user_enline->select();
							foreach($result as $value){
						?>
							<div class="user_message" onclick="select(<?php echo $value['id']?>); select_message(<?php echo $id ?>,<?php echo $value['id']?>);">
								<img src="<?php echo $value["user_pic"]?>">
								<h5><?php echo $value["name"]?></h5>
								<?php
									if($value["status"] == 1)
									{
								?>
									<i class="fa fa-circle" style="color:green"></i>
								<?php
									}
									else{
								?>
									<i class="fa fa-circle" style="color:red"></i>
								<?php
									}
								?>
							</div>
						<?php
                        }
                    	?>


			</div>
        </div>



        <div class="setting">
			<h1 class="name_message">Settings<h1>
			<div class="user_img">
                <img src="<?php echo $user_pic?>" class="last_user_img" id="UserImgSetting">
				<input type="file" id="theFileInput" style="display:none;"/>
				<div class="btn_add_image">
					<button class="update_pic">+</button>
				</div>
            </div>
            <div class="nickname">
                <h6>Your nickname</h6>
				<input type="text" id='username' value='<?php echo $user_name;?>'>
            </div>
			<div class="update_btn">
					<button class="btn">update</button>
			</div>
			<div class="logout_btn">
				<a href="../controler/logout_logic.php" class="btn">Logout 
<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30px" height="30px"
	 viewBox="0 0 310 310" style="enable-background:new 0 0 310 310;" xml:space="preserve">
<g id="XMLID_15_">
	<path id="XMLID_16_" d="M221.742,46.906c-7.28-3.954-16.387-1.259-20.341,6.021c-3.955,7.279-1.259,16.386,6.02,20.341
		C242.937,92.561,265,129.626,265,170c0,60.654-49.346,110-110,110S45,230.654,45,170c0-40.198,21.921-77.186,57.208-96.531
		c7.265-3.982,9.925-13.1,5.943-20.364c-3.983-7.264-13.101-9.925-20.364-5.943C42.891,71.775,15,118.844,15,170
		c0,77.196,62.804,140,140,140s140-62.804,140-140C295,118.62,266.929,71.453,221.742,46.906z"/>
	<path id="XMLID_17_" d="M155,130c8.284,0,15-6.716,15-15V15c0-8.284-6.716-15-15-15c-8.284,0-15,6.716-15,15v100
		C140,123.284,146.716,130,155,130z"/>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>
</a>
			</div>
        </div>




<!-- options -->





        <div class="user_options">


            <div class="icon_user"  onclick="switch_side('profile')" >
                
<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" height="50px"  width="50px" 
	 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
<g>
	<g>
		<path d="M256,288.389c-153.837,0-238.56,72.776-238.56,204.925c0,10.321,8.365,18.686,18.686,18.686h439.747
			c10.321,0,18.686-8.365,18.686-18.686C494.56,361.172,409.837,288.389,256,288.389z M55.492,474.628
			c7.35-98.806,74.713-148.866,200.508-148.866s193.159,50.06,200.515,148.866H55.492z"/>
	</g>
</g>
<g>
	<g>
		<path d="M256,0c-70.665,0-123.951,54.358-123.951,126.437c0,74.19,55.604,134.54,123.951,134.54s123.951-60.35,123.951-134.534
			C379.951,54.358,326.665,0,256,0z M256,223.611c-47.743,0-86.579-43.589-86.579-97.168c0-51.611,36.413-89.071,86.579-89.071
			c49.363,0,86.579,38.288,86.579,89.071C342.579,180.022,303.743,223.611,256,223.611z"/>
	</g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>

            </div>

            <div class="icon_user"  onclick="switch_side('chat_users')">
    
<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" height="50px"  width="50px" 
	 viewBox="0 0 512.001 512.001" style="enable-background:new 0 0 512.001 512.001;" xml:space="preserve">
<g>
	<g>
		<g>
			<path d="M468.53,306.575c-4.14-10.239-15.798-15.188-26.038-11.046c-10.241,4.14-15.187,15.797-11.047,26.038L455,379.833
				l-69.958-30.839c-5.064-2.232-10.827-2.267-15.917-0.095c-23.908,10.201-49.52,15.373-76.124,15.373
				c-107.073,0-179-83.835-179-162.136c0-89.402,80.299-162.136,179-162.136s179,72.734,179,162.136
				c0,6.975-0.65,15.327-1.781,22.913c-1.63,10.925,5.905,21.102,16.83,22.732c10.926,1.634,21.103-5.905,22.732-16.83
				c1.431-9.59,2.219-19.824,2.219-28.815c0-54.33-23.006-105.308-64.783-143.543C405.936,20.809,351.167,0,293.001,0
				S180.067,20.809,138.784,58.592c-37.332,34.168-59.66,78.516-63.991,126.335C27.836,216.023,0.001,265.852,0.001,319.525
				c0,33.528,10.563,65.34,30.67,92.717L1.459,484.504c-3.051,7.546-1.224,16.189,4.621,21.855
				c3.809,3.694,8.828,5.642,13.925,5.642c2.723-0.001,5.469-0.556,8.063-1.7l84.229-37.13c21.188,7.887,43.585,11.88,66.703,11.88
				c0.5,0,0.991-0.039,1.482-0.075c33.437-0.253,65.944-9.048,94.098-25.507c25.218-14.744,45.962-34.998,60.505-58.917
				c14.199-2.55,28.077-6.402,41.547-11.551l107.301,47.3c2.595,1.143,5.34,1.7,8.063,1.7c5.097-0.001,10.117-1.949,13.926-5.642
				c5.845-5.666,7.672-14.308,4.621-21.855L468.53,306.575z M179.002,445c-0.273,0-0.539,0.03-0.81,0.041
				c-20.422-0.104-40.078-4.118-58.435-11.95c-5.089-2.173-10.852-2.138-15.916,0.095l-46.837,20.646l15.109-37.375
				c2.793-6.909,1.512-14.799-3.322-20.47c-18.835-22.097-28.79-48.536-28.79-76.462c0-31.961,13.445-62.244,36.969-85.206
				c7.324,39.925,27.989,78.117,59.162,108.119c38.791,37.333,90.101,58.961,145.506,61.565
				C255.626,429.608,218.402,445,179.002,445z"/>
			<circle cx="292.001" cy="203" r="20"/>
			<circle cx="372.001" cy="203" r="20"/>
			<circle cx="212.001" cy="203" r="20"/>
		</g>
	</g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>
        
            </div>
			<div class="icon_user" onclick="switch_side('user_enline')">
            
			<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" height="50px"  width="50px" 
				 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
			<g>
				<g>
					<g>
						<path d="M234.071,471.132H60.391c-6.453,0-12.333-2.991-16.135-8.207c-3.803-5.218-4.85-11.736-2.874-17.883
							c19.732-61.346,79.908-104.191,146.336-104.191c30.909,0,60.591,9.308,85.838,26.916c9.043,6.307,21.485,4.09,27.795-4.953
							c6.306-9.043,4.089-21.486-4.954-27.794c-12.498-8.717-25.85-15.828-39.817-21.257c18.583-16.896,30.911-40.555,33.053-67.048
							c28.177-27.448,65.111-42.488,104.704-42.488c30.909,0,60.591,9.308,85.838,26.916c9.043,6.307,21.486,4.09,27.795-4.953
							c6.306-9.043,4.089-21.486-4.954-27.794c-12.498-8.717-25.85-15.828-39.817-21.257c20.499-18.638,33.386-45.506,33.386-75.328
							C496.586,45.673,450.913,0,394.774,0c-56.14,0-101.812,45.673-101.812,101.813c0,29.701,12.784,56.473,33.139,75.102
							c-2.785,1.072-5.55,2.212-8.295,3.42c-12.492,5.497-24.241,12.245-35.162,20.183c-15.068-37.415-51.746-63.893-94.49-63.893
							c-56.14,0-101.812,45.673-101.812,101.813c0,29.614,12.71,56.316,32.96,74.938c-54.148,20.292-98.053,63.87-115.927,119.444
							c-5.928,18.431-2.788,37.976,8.616,53.623c11.402,15.645,29.042,24.618,48.401,24.618h173.68
							c11.026,0,19.963-8.938,19.963-19.963S245.096,471.132,234.071,471.132z M394.775,39.926c34.124,0,61.886,27.762,61.886,61.886
							s-27.762,61.886-61.886,61.886c-34.124,0-61.886-27.762-61.886-61.886S360.651,39.926,394.775,39.926z M188.155,176.55
							c34.124,0,61.886,27.762,61.886,61.886s-27.762,61.886-61.886,61.886s-61.886-27.762-61.886-61.886
							S154.031,176.55,188.155,176.55z"/>
						<path d="M503.217,326.082c-8.965-6.418-21.436-4.354-27.853,4.612l-98.4,137.447c-2.687,3.116-6.055,3.789-7.859,3.909
							c-1.857,0.127-5.463-0.114-8.555-3.057l-63.703-61.168c-7.954-7.638-20.593-7.379-28.226,0.573
							c-7.637,7.952-7.38,20.59,0.572,28.226l63.767,61.228c9.55,9.091,22.298,14.149,35.414,14.149c1.127,0,2.257-0.037,3.387-0.113
							c14.288-0.952,27.628-7.9,36.599-19.062c0.233-0.289,0.455-0.584,0.672-0.885l98.799-138.006
							C514.247,344.97,512.183,332.5,503.217,326.082z"/>
					</g>
				</g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			</svg>
			
						</div>
            <div class="icon_user"  onclick="switch_side('setting')" >
            
<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" height="50px"  width="50px" 
	 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
<g>
	<g>
		<path d="M490.667,405.333h-56.811C424.619,374.592,396.373,352,362.667,352s-61.931,22.592-71.189,53.333H21.333
			C9.557,405.333,0,414.891,0,426.667S9.557,448,21.333,448h270.144c9.237,30.741,37.483,53.333,71.189,53.333
			s61.931-22.592,71.189-53.333h56.811c11.797,0,21.333-9.557,21.333-21.333S502.464,405.333,490.667,405.333z M362.667,458.667
			c-17.643,0-32-14.357-32-32s14.357-32,32-32s32,14.357,32,32S380.309,458.667,362.667,458.667z"/>
	</g>
</g>
<g>
	<g>
		<path d="M490.667,64h-56.811c-9.259-30.741-37.483-53.333-71.189-53.333S300.736,33.259,291.477,64H21.333
			C9.557,64,0,73.557,0,85.333s9.557,21.333,21.333,21.333h270.144C300.736,137.408,328.96,160,362.667,160
			s61.931-22.592,71.189-53.333h56.811c11.797,0,21.333-9.557,21.333-21.333S502.464,64,490.667,64z M362.667,117.333
			c-17.643,0-32-14.357-32-32c0-17.643,14.357-32,32-32s32,14.357,32,32C394.667,102.976,380.309,117.333,362.667,117.333z"/>
	</g>
</g>
<g>
	<g>
		<path d="M490.667,234.667H220.523c-9.259-30.741-37.483-53.333-71.189-53.333s-61.931,22.592-71.189,53.333H21.333
			C9.557,234.667,0,244.224,0,256c0,11.776,9.557,21.333,21.333,21.333h56.811c9.259,30.741,37.483,53.333,71.189,53.333
			s61.931-22.592,71.189-53.333h270.144c11.797,0,21.333-9.557,21.333-21.333C512,244.224,502.464,234.667,490.667,234.667z
			 M149.333,288c-17.643,0-32-14.357-32-32s14.357-32,32-32c17.643,0,32,14.357,32,32S166.976,288,149.333,288z"/>
	</g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>

            </div>
        </div>

    </div>
    <div class="Chat">
        <div class="connect_with">
			<?php
				$connWith = $_GET['connect'];
				$execustion = new CRUD('user');
				$result = $user_enline->select("",["id" => $connWith]);
				foreach($result as $value){
					$user_name = $value["name"];
					$user_pic = $value["user_pic"];
				}
				
			?>
            <img src="<?php echo $user_pic ?>" alt="">
            <h1><?php echo $user_name ?></h1>
        </div>
        <div class="message">
        </div>
		<?php
		
			include 'emoji.php';		

		?>
        <div class="write_message">
		<form onsubmit="return SendGetMsg();">
            <div class="row">
                <div class="col-xxl-1 col-xl-1 col-lg-1 col-md-1 col-1">
    
<svg class="icon_emoji" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="50px" height="50px"
	 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
<g>
	<g>
		<path d="M256,0C114.615,0,0,114.615,0,256s114.615,256,256,256s256-114.615,256-256S397.385,0,256,0z M256,480
			C132.288,480,32,379.712,32,256S132.288,32,256,32s224,100.288,224,224S379.712,480,256,480z"/>
	</g>
</g>
<g>
	<g>
		<circle cx="176" cy="176" r="32"/>
	</g>
</g>
<g>
	<g>
		<circle cx="336" cy="176" r="32"/>
	</g>
</g>
<g>
	<g>
		<path d="M368,256c0,61.856-50.144,112-112,112s-112-50.144-112-112h-32c0,79.529,64.471,144,144,144s144-64.471,144-144H368z"/>
	</g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>

                </div>
					<div class="col-xxl-10 col-xl-10 col-lg-10 col-md-10 col-10" >
						<input type="text" placeholder="Type your message her ..." name="message"  class="send_msg" >
					</div>
					<div class="col-xxl-1 col-xl-1 col-lg-1 col-md-1 col-1">
						<div class="send_msg">
							<button type="submit" name="submit" class="SendMsg" >
								<svg id="Layer_1" enable-background="new 0 0 496.007 496.007" height="45px" viewBox="0 0 496.007 496.007" width="45px" xmlns="http://www.w3.org/2000/svg"><path d="m205.892 403.822c-6.25-6.25-16.38-6.25-22.63 0l-41.92 41.92c-6.25 6.24-6.25 16.38 0 22.62 6.206 6.226 16.348 6.282 22.63 0l41.92-41.92c6.25-6.251 6.25-16.38 0-22.62zm-113.71-113.711c-6.24-6.25-16.37-6.25-22.62 0l-41.92 41.92c-6.25 6.25-6.25 16.38 0 22.63 6.24 6.239 16.354 6.266 22.62 0l41.92-41.92c6.25-6.249 6.25-16.38 0-22.63zm75.81 37.901c-6.25-6.25-16.38-6.24-22.63 0l-106.24 106.24c-6.25 6.25-6.25 16.38 0 22.63 6.248 6.229 16.358 6.252 22.63 0l106.24-106.24c6.25-6.25 6.25-16.38 0-22.63zm327.2-307.02-151.62 464c-4.286 13.097-22.084 15.008-29.04 3.07l-101.96-175.35c-3.22-5.53-2.83-12.44.98-17.58l36.61-49.29-49.29 36.61c-5.14 3.81-12.05 4.2-17.58.98l-175.35-101.961c-11.896-6.919-10.066-24.741 3.07-29.04l464-151.62c12.402-4.047 24.245 7.727 20.18 20.181z"/></svg>
							</button>
						</div>
					</div>
				</form>
            </div>
        </div>
    </div>
</div>


</section>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
<script src="../node_modules/jquery/dist/jquery.min.js"></script>
<script src="assets/js/main.js"></script>
<script>






/////////////////// search users friends


const SearchUser = () =>{
                    const request = new XMLHttpRequest(); 
                    let inputValue = document.querySelector("#user_enline_input").value ;

                    request.onreadystatechange = function(){
                                    if(request.readyState === 4 && this.status == 200){
                                        console.log(this.responseText);
                                        let all_users = document.querySelector(".all_friends")
                                        all_users.innerHTML = this.responseText;
                                    }
                        }

                        request.open("GET",`../controler/user_enline_login.php?id=${<?php echo $id?>}&name=${inputValue}`,true);
                        request.send();

                }

/////////////////// if click users friends switch to this user

const select = (connect) =>{
                    const request = new XMLHttpRequest();
					let FriendId = document.querySelector('#FriendId');
					FriendId.value = connect;
                    request.onreadystatechange = function(){
                                    if(request.readyState === 4 && this.status == 200){
                                        let zone_user_chat = document.querySelector(".connect_with")
                                        zone_user_chat.innerHTML = this.responseText;                                        
                                    }
                        }

                        request.open("GET",`../controler/conncet_with.php?id=${connect}`,true);
                        request.send();
                }

///////////////////// show message with this users friend clicked


const select_message = (id,connect) =>{
                    const request = new XMLHttpRequest();

                    request.onreadystatechange = function(){
                                    if(request.readyState === 4 && this.status == 200){
                                        let message = document.querySelector(".message")
                                        message.innerHTML = this.responseText;
                                    }
                        }
                        request.open("GET",`../controler/get_message.php?user=${id}&id=${connect}`,true);
                        window.history.pushState('new', 'SomePage', `../vue/Chat_Page.php?id=${id}&connect=${connect}`);

                        request.send();              
              }



/////////////////////////////// make message as read
const read_message = (id,connect) =>{
	const request = new XMLHttpRequest();

	request.onreadystatechange = function(){
				if(request.readyState === 4 && this.status == 200){
					let message_read = document.querySelector(`#message${this.responseText}`)
					message_read.style='font-weight: 500';
				}
	}

	request.open("GET",`../controler/read_message.php?user=${id}&id=${connect}`,true);
	request.send();  
}
////////////////// check data base and get all message evevry 0.1s
setInterval(function(){ 

				let FriendRecu = document.querySelector('#FriendId').value;
				let FriendSent = document.querySelector('#UserId').value;
                const request = new XMLHttpRequest();
                request.onreadystatechange = function(){
                                if(request.readyState === 4 && this.status == 200){
									let message = document.querySelector(".message")
                                    message.innerHTML = this.responseText;
                                }
                    }
					request.open("GET",`../controler/get_message.php?user=${FriendSent}&id=${FriendRecu}`,true);
                    request.send();   
            }, 100);


//////////////// send message
const SendGetMsg = () =>{
                var data = new FormData();
                data.append("User_recu", document.querySelector('#FriendId').value);
                data.append("User_sent", document.querySelector('#UserId').value);
				data.append("message", document.querySelector('.send_msg').value);
                data.append("submit", document.querySelector('.SendMsg').value);

    
                var request = new XMLHttpRequest();
                request.open("POST",'../controler/Chat_logic.php');
                request.send(data);
                let SpaceMsg = document.querySelector('.send_msg');
                SpaceMsg.value = "";
                return false;
            }
//////////////////////// user status
let status = document.querySelector('#status');
status.addEventListener('change' , function(){
		let UserId = document.querySelector('#UserId').value;
		Urstatus = status.value
		const request = new XMLHttpRequest();

		request.onreadystatechange = function(){
					if(request.readyState === 4 && this.status == 200){
							console.log(this.responseText)
					}
		}

		request.open("GET",`../controler/Userstatus.php?id=${UserId}&status=${Urstatus}`,true);
		request.send();  
})
///////////// user update pic and name
let update = document.querySelector('.update_btn')

update.addEventListener('click' , () =>{
	let username = document.querySelector('#username').value
	let userImg = document.querySelector('#theFileInput').value
	let NewUserImg = userImg.replace("fakepath", "")
	let New2UserImg = NewUserImg.replace("C:\\\\", "")
	let lastImgUser = document.querySelector(".last_user_img").src
	let NewLastimg = lastImgUser.replace("http://localhost/ChatSysteme/vue/", "")

	$.post(`../controler/Update_user.php`,{id : <?php echo $UserId?> , UserName : username, NewImg :New2UserImg , LastImg : NewLastimg},function(data,status,xhr){
		if(status == 'success')
		{
			let UserNameProfil= document.querySelector('.UserNameProfil');
			let UserPicProfile = document.querySelector('.UserPicProfile');
			let userPicSetting = document.querySelector("#UserImgSetting");
			UserNameProfil.innerHTML =  username;
			UserPicProfile.src = data;
			userPicSetting.src = data;
			console.log(userPicSetting.src);
		}
	})
	
})

let emoji = document.querySelectorAll('.emoji_icon')

emoji.forEach(Element =>{
	Element.addEventListener('click', () =>{
		let sendMsg = document.querySelector('.send_msg')
		console.log(sendMsg)
		console.log(Element.innerHTML);
		sendMsg.value += Element.innerHTML

	})
})


let emoji_work = true;
$('.icon_emoji').click(function(){
	$('.emojo').toggle();
	if(emoji_work){
		$('.message').css("height","43.3%");
		emoji_work = false
	}
	else{

		$('.message').css("height","70.7%");
		emoji_work = true
	}
  });
</script>



</body>
</html>