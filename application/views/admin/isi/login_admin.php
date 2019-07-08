<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=1,initial-scale=1,user-scalable=1" />
	<title>Login Admin NMS </title>
	
	<link href="http://fonts.googleapis.com/css?family=Lato:100italic,100,300italic,300,400italic,400,700italic,700,900italic,900" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>etc/bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>etc/login/styles.css" />
	
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

	<section class="container">
			<section class="login-form">
			<?php 
			if (isset($message)) echo $message;
			echo validation_errors(); 
			?> 
				<form method="post" action="<?php echo base_url();?>login_admin/user_login_process" role="login">
					<p>Login NMS FSM Undip</p>
					<img src="<?php echo base_url();?>etc/login/styles.css" class="img-responsive" alt="" />
			<input type="email" name="email" placeholder="Email" required class="form-control input-lg" value="<?php echo set_value('email'); ?>" />
					<input type="password" name="password" placeholder="Password" required class="form-control input-lg" value="<?php echo set_value('password'); ?>" />
					<button type="submit" name="go" class="btn btn-lg btn-primary btn-block">Sign in</button>
					<div>
						<!-- <a href="#">reset password</a> -->
					</div>
				</form>
				<div class="form-links">
					<!-- <?php echo validation_errors(); ?> -->
					<!-- <a href="#">www.website.com</a> -->
				</div>
			</section>
	</section>
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="<?php echo base_url();?>etc/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>