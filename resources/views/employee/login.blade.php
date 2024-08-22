<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from demo.adminkit.io/pages-sign-in by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 29 Jul 2024 06:19:06 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
	<meta name="author" content="AdminKit">
	<meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

	<link rel="preconnect" href="https://fonts.gstatic.com/">
	<link rel="shortcut icon" href="img/icons/icon-48x48.png" />

	<link rel="canonical" href="pages-sign-in-2.html" />

	<title>Sign In | Digieagle | Employee</title>

	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&amp;display=swap" rel="stylesheet">
	<link class="js-stylesheet" href="{{asset('css/light.css')}}" rel="stylesheet">
	<style>
		body {
			opacity: 0;
		}
	</style>
	<!-- END SETTINGS -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-120946860-10"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-120946860-10', { 'anonymize_ip': true });
</script></head>


<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
	<main class="d-flex w-100 h-100">
		<div class="container d-flex flex-column">
			<div class="row vh-100">
				<div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
					<div class="d-table-cell align-middle">

						<div class="text-center mt-4">
							<h1 class="h2">Digieagle Employee</h1>
							<p class="lead">
								Sign in to your account to continue
							</p>
						</div>

						<div class="card">
							<div class="card-body">
								<div class="m-sm-3">
									@if(Session::has('success'))
									<div class="alert alert-success">{{Session::get('success')}}</div>
									@endif
									@if(Session::has('error'))
									<div class="alert alert-danger">{{Session::get('error')}}</div>
									@endif
									<div class="d-grid gap-2 mb-3">
										<a class='btn btn-microsoft btn-lg' href='#'><i class="fab fa-fw fa-google"></i> Sign in with google</a>
									</div>
									<div class="row">
										<div class="col">
											<hr>
										</div>
										<div class="col-auto text-uppercase d-flex align-items-center">Or</div>
										<div class="col">
											<hr>
										</div>
									</div>
									<form method="post" action="{{route('emp/authenticate')}}">
										@csrf
										<div class="mb-3">
											<label class="form-label">Username</label>
											<input class="form-control form-control-lg @error('username') is-invalid @enderror" type="text" name="username" value="{{old('username')}}" placeholder="Enter your username" />
											@error('username')
											<p class="invalid-feedback">{{$message}}</p>
											@enderror
										</div>
										<div class="mb-3 position-relative">
											<label class="form-label">Password</label>
											<input 
												id="password" 
												class="form-control form-control-lg @error('password') is-invalid @enderror" 
												type="password" 
												name="password" 
												placeholder="Enter your password" 
											/>
											<i 
												id="toggleIcon" 
												class="fa fa-eye position-absolute" 
												onclick="togglePasswordVisibility()" 
												style="cursor: pointer; right: 10px; top: 72%; transform: translateY(-50%);"
											></i>
											@error('password')
											<p class="invalid-feedback">{{$message}}</p>
											@enderror
										</div>
										<small>
											<a href='{{route('emp/resetpassword')}}'>Forgot password?</a>
										</small>						
										<div class="d-grid gap-2 mt-3">
											<button class='btn btn-lg btn-primary' >Sign in</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
</body>

<script>
	function togglePasswordVisibility() {
		var passwordInput = document.getElementById('password');
		var toggleIcon = document.getElementById('toggleIcon');
		if (passwordInput.type === "password") {
			passwordInput.type = "text";
			toggleIcon.classList.remove('fa-eye');
			toggleIcon.classList.add('fa-eye-slash');
		} else {
			passwordInput.type = "password";
			toggleIcon.classList.remove('fa-eye-slash');
			toggleIcon.classList.add('fa-eye');
		}
	}
</script>

</html>