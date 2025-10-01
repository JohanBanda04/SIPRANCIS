<div class="limiter">
	<div class="container-login100">
		<div class="wrap-login100" style="padding:40px;">
			<div class="login100-pic js-tilt">
				<center><img src="img/logo.png" alt="IMG"></center>
			</div>
			<form class="login100-form validate-form" action="" method="post">
				<span class="login100-form-title" style="padding:40px;">
					Silahkan Login
				</span>
				<?php
					echo $this->session->flashdata('msg');
				?>
				<div class="wrap-input100 validate-input" data-validate = "Username is required">
					<input class="input100" type="text" placeholder="Username" name="username" autofocus>
					<span class="focus-input100"></span>
					<span class="symbol-input100">
						<i class="fa fa-user" aria-hidden="true"></i>
					</span>
				</div>

				<div class="wrap-input100 validate-input" data-validate = "Password is required">
					<input class="input100" type="password" placeholder="Password" name="password">
					<span class="focus-input100"></span>
					<span class="symbol-input100">
						<i class="fa fa-lock" aria-hidden="true"></i>
					</span>
				</div>

				<div class="container-login100-form-btn">
					<button type="submit" name="btnlogin" class="login100-form-btn">
						Login
					</button>
				</div>
				<hr>
				<!-- <div class="text-center p-t-136"> -->
					<a class="txt2" href="web">
						Beranda
						<i class="fa fa-home m-l-5" aria-hidden="true"></i>
					</a><span>   </span>
					<a class="txt2" href="web/user_register.html">
						Buat Akun 
						<i class="fa fa-user m-l-5" aria-hidden="true"></i>
					</a>
				 </div> 
				<!-- <div class="text-center p-t-136"></div> -->
			</form>
		</div>
	</div>
</div>
