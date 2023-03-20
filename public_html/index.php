<!doctype html>
<html lang="en" dir="ltr">
  
	<head>
		<?php include 'layouts/custom-styles.php'; ?>


	</head>

	<body class="login-img">

		<!-- BACKGROUND-IMAGE -->
		<div>

			<!-- GLOABAL LOADER -->
			<div id="global-loader">
				<img src="assets/images/loader.svg" class="loader-img" alt="Loader">
			</div>
			<!-- /GLOABAL LOADER -->

			<!-- PAGE -->
			<div class="page login-page">
				<div>
				    <!-- CONTAINER OPEN -->
					<div class="col col-login mx-auto mt-7">
						<div class="text-center">
							<img src="assets/images/brand/logo.png" class="header-brand-img" alt="">
						</div>
					</div>
					<div class="container-login100">
						<div class="wrap-login100 p-0">
							<div class="card-body">
								<form class="login100-form validate-form" method="post" onsubmit="return do_login();">
									<span class="login100-form-title">
										Login
									</span>
									<div class="wrap-input100 validate-input" data-bs-validate = "Valid email is required: ex@abc.xyz">
										<input class="input100" type="text" name="email" id="emailid" placeholder="Email" required>
										<span class="focus-input100"></span>
										<span class="symbol-input100">
											<i class="zmdi zmdi-email" aria-hidden="true"></i>
										</span>
									</div>
									<div class="wrap-input100 validate-input" data-bs-validate = "Password is required" required>
										<input class="input100" type="password" name="pass" id="password" placeholder="Password">
										<span class="focus-input100"></span>
										<span class="symbol-input100">
											<i class="zmdi zmdi-lock" aria-hidden="true"></i>
										</span>
									</div>

									<div class="container-login100-form-btn">
									<button class="btn btn-primary" type="submit" id="login">Login</button>
										</a>
									</div>
								</form>
							</div>
						</div>
					</div>
					<!-- CONTAINER CLOSED -->
				</div>
			</div>
			<!-- END PAGE -->

		</div>
		<!-- BACKGROUND-IMAGE CLOSED -->

		<?php include 'layouts/custom-scripts.php'; ?>
		<script type="text/javascript">
		function do_login(){
		var email=$("#emailid").val();
		var pass=$("#password").val();
		if(email!="" && pass!=""){
		$("#loading_spinner").css({"display":"block"});//btn-loading
			$.ajax({
				type:'post',
				url:'do_login.php',
				data:{
				do_login:"do_login",
				email:email,
				password:pass
				},
				success:function(response) {
					
					if(response == 'success'){
						window.location.href="booking-details.php";
					}
					else{
						$("#loading_spinner").css({"display":"none"});
						alert("Wrong Details");
					}
				}
			});
		}else
		{
			alert("Please Fill All The Details");
		}

		return false;
		}
</script>	
	</body>
</html>
