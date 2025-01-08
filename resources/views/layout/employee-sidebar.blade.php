<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from demo.adminkit.io/dashboard-ecommerce by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 29 Jul 2024 06:18:53 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" type="image/x-icon" href="{{asset('storage\logo\Digieagle-Favicon.png')}}">
	<link rel="preconnect" href="https://fonts.gstatic.com/">

	<title>Dashboard | Digieagle INC</title>

	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&amp;display=swap" rel="stylesheet">

	<link class="js-stylesheet" href="{{asset('css/light.css')}}" rel="stylesheet">
	<script src="{{asset('js/datatables.js')}}"></script>
	<style>
		body {
			opacity: 0;
		}
		.sidebar-link.active {
			/* border: 1px solid #ffffff3a;	 */
			background-color: transparent; /* Change to your desired highlight color */
			color: white; /* Adjust text color if necessary */
		}
	</style>
	<!-- END SETTINGS -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-120946860-10"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-120946860-10', { 'anonymize_ip': true });
</script>
</head>

<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
	<div class="wrapper">
		<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">
				<a class='sidebar-brand'>
					<span class="sidebar-brand-text align-middle">
						<img src="{{ asset('storage/logo/logo.png') }}" width="70%" height="70%" alt="logo">
					</span>
					<svg class="sidebar-brand-icon align-middle" width="32px" height="32px" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="1.5"
						stroke-linecap="square" stroke-linejoin="miter" color="#FFFFFF" style="margin-left: -3px">
						<path d="M12 4L20 8.00004L12 12L4 8.00004L12 4Z"></path>
						<path d="M20 12L12 16L4 12"></path>
						<path d="M20 16L12 20L4 16"></path>
					</svg>
				</a>
		
				<div class="sidebar-user">
					@yield('profile')
				</div>
		
				<ul class="sidebar-nav">
					<a href="{{ route('emp/dashboard') }}" class="sidebar-link {{ request()->routeIs('emp/dashboard') ? 'active' : '' }}">
						<i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
					</a>
		
					@if(session('has_bde_features'))
					<li class="sidebar-item">
						<a data-bs-target="#lead" data-bs-toggle="collapse" class="sidebar-link {{ request()->routeIs('emp/lead') || request()->routeIs('emp/lead-list') ? 'active' : 'collapsed' }}">
							<i class="fas fa-poll {{ request()->routeIs('emp/lead') || request()->routeIs('emp/lead-list') ? 'text-white' : '' }}"></i> 
							<span class="align-middle">Lead Management</span>
						</a>
						<ul id="lead" class="sidebar-dropdown list-unstyled collapse {{ request()->routeIs('emp/lead') || request()->routeIs('emp/lead-list') ? 'show' : '' }}" data-bs-parent="#sidebar">
							<li class="sidebar-item">
								<a class="sidebar-link {{ request()->routeIs('emp/lead') ? 'active' : '' }}" href="{{ route('emp/lead') }}">Add Lead</a>
							</li>
							<li class="sidebar-item">
								<a class="sidebar-link {{ request()->routeIs('emp/lead-list') ? 'active' : '' }}" href="{{ route('emp/lead-list') }}">Lead List</a>
							</li>
						</ul>
					</li>
					@endif

					@if(session('has_hr_features'))
					<li class="sidebar-item">
						<a data-bs-target="#leave" data-bs-toggle="collapse" class="sidebar-link {{ request()->routeIs('emp/leave-type') || request()->routeIs('emp/leave-list') ? 'active' : 'collapsed' }}">
							<i class="fa fa-line-chart {{ request()->routeIs('emp/leave-type') || request()->routeIs('emp/leave-list') ? 'text-white' : '' }}"></i> <span class="align-middle">Leave Management</span>
						</a>
						<ul id="leave" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
							<li class="sidebar-item"><a class='sidebar-link {{ request()->routeIs('emp/leave-type') ? 'active' : '' }}' href='{{ route('emp/leave-type') }}'>Leave Type</a></li>
							<li class="sidebar-item"><a class='sidebar-link {{ request()->routeIs('emp/leave') ? 'active' : '' }}' href='{{ route('emp/leave-list') }}'>Leave List</a></li>
						</ul>
					</li>

					<li class="sidebar-item">
						<a data-bs-target="#pages" data-bs-toggle="collapse" class="sidebar-link {{ request()->routeIs('emp/add-emp') || request()->routeIs('emp/list-emp') ? 'active' : 'collapsed' }}">
							<i class="fa fa-user-circle {{ request()->routeIs('emp/add-emp') || request()->routeIs('emp/list-emp') ? 'text-white' : '' }}" aria-hidden="true"></i> <span class="align-middle">Employee Management</span>
						</a>
						<ul id="pages" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
							<li class="sidebar-item"><a class='sidebar-link {{ request()->routeIs('emp/add-emp') ? 'active' : '' }}' href='{{ route('emp/add-emp') }}'>Add Employee</a></li>
							<li class="sidebar-item"><a class='sidebar-link {{ request()->routeIs('emp/list-emp') ? 'active' : '' }}' href='{{ route('emp/list-emp') }}'>List Employee</a></li>
						</ul>
					</li>
					@endif	
		
					<a href="{{ route('emp/attendance') }}" class="sidebar-link {{ request()->routeIs('emp/attendance') ? 'active' : '' }}">
						<i class='far fa-calendar-alt {{ request()->routeIs('emp/attendance') ? 'text-white' : '' }}'></i> <span class="align-middle">Attendance</span>
					</a>
		
					<a href="{{ route('emp/leave') }}" class="sidebar-link {{ request()->routeIs('emp/leave') ? 'active' : '' }}">
						<i class='far fa-calendar-alt {{ request()->routeIs('emp/leave') ? 'text-white' : '' }}'></i> <span class="align-middle">Leave Records</span>
					</a>

					<a href="{{ route('emp/work-report') }}" class="sidebar-link {{ request()->routeIs('emp/work-report') ? 'active' : '' }}">
						<i class='fa fa-file {{ request()->routeIs('emp/work-report') ? 'text-white' : '' }}'></i> <span class="align-middle">Work Report</span>
					</a>

					<a href="{{ route('emp/work-report-history') }}" class="sidebar-link {{ request()->routeIs('emp/work-report-history') ? 'active' : '' }}">
						<i class='fa fa-file {{ request()->routeIs('emp/work-report-history') ? 'text-white' : '' }}'></i> <span class="align-middle">Work History</span>
					</a>
		
					<a href="{{ route('emp/calendar') }}" class="sidebar-link {{ request()->routeIs('emp/calendar') ? 'active' : '' }}">
						<i class='fa fa-birthday-cake {{ request()->routeIs('emp/calendar') ? 'text-white' : '' }}'></i> <span class="align-middle">Calendar</span>
					</a>
		
					{{-- <a href="{{ route('emp/projects') }}" class="sidebar-link {{ request()->routeIs('emp/projects') ? 'active' : '' }}">
						<i class='fa fa-project-diagram {{ request()->routeIs('emp/projects') ? 'text-white' : '' }}'></i> <span class="align-middle">Projects</span>
					</a> --}}
				</ul>
			</div>
		</nav>
		

		<div class="main">
			<nav class="navbar navbar-expand navbar-light navbar-bg">
				<a class="sidebar-toggle js-sidebar-toggle">
					<i class="hamburger align-self-center"></i>
				</a>

				@yield('menu')
				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-bs-toggle="dropdown">
								<div class="position-relative">
									<i class="align-middle" data-feather="bell"></i>
									<span class="indicator" id="notification-count"></span>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
								<div class="dropdown-menu-header" >
									 New Notifications
								</div>
								<div class="list-group"  id="notification-list">
									
								</div>

							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle" href="#" id="messagesDropdown" data-bs-toggle="dropdown">
								<div class="position-relative">
									<i class="align-middle" data-feather="message-square"></i>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="messagesDropdown">
								<div class="dropdown-menu-header">
									<div class="position-relative">
										4 New Messages
									</div>
								</div>
								<div class="list-group">
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<img src="img/avatars/avatar-5.jpg" class="avatar img-fluid rounded-circle" alt="Vanessa Tucker">
											</div>
											<div class="col-10 ps-2">
												<div class="text-dark">Vanessa Tucker</div>
												<div class="text-muted small mt-1">Nam pretium turpis et arcu. Duis arcu tortor.</div>
												<div class="text-muted small mt-1">15m ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<img src="img/avatars/avatar-2.jpg" class="avatar img-fluid rounded-circle" alt="William Harris">
											</div>
											<div class="col-10 ps-2">
												<div class="text-dark">William Harris</div>
												<div class="text-muted small mt-1">Curabitur ligula sapien euismod vitae.</div>
												<div class="text-muted small mt-1">2h ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<img src="img/avatars/avatar-4.jpg" class="avatar img-fluid rounded-circle" alt="Christina Mason">
											</div>
											<div class="col-10 ps-2">
												<div class="text-dark">Christina Mason</div>
												<div class="text-muted small mt-1">Pellentesque auctor neque nec urna.</div>
												<div class="text-muted small mt-1">4h ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<img src="img/avatars/avatar-3.jpg" class="avatar img-fluid rounded-circle" alt="Sharon Lessman">
											</div>
											<div class="col-10 ps-2">
												<div class="text-dark">Sharon Lessman</div>
												<div class="text-muted small mt-1">Aenean tellus metus, bibendum sed, posuere ac, mattis non.</div>
												<div class="text-muted small mt-1">5h ago</div>
											</div>
										</div>
									</a>
								</div>
								<div class="dropdown-menu-footer">
									<a href="#" class="text-muted">Show all messages</a>
								</div>
							</div>
						</li>
						
						<li class="nav-item">
							<a class="nav-icon js-fullscreen d-none d-lg-block" href="#">
								<div class="position-relative">
									<i class="align-middle" data-feather="maximize"></i>
								</div>
							</a>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-icon pe-md-0 dropdown-toggle" href="#" data-bs-toggle="dropdown">
								<img src="{{asset('storage/profile_photos').'/'.session('employee')->profile_photo_path}}" class="avatar img-fluid rounded" />
							</a>
							<div class="dropdown-menu dropdown-menu-end">
								<a class='dropdown-item' href='{{route('emp/profile')}}'><i class="align-middle me-1" data-feather="user"></i> Profile</a>
								
								<a class="dropdown-item" href="{{route('emp/logout')}}">Log out</a>
							</div>
						</li>
					</ul>
				</div>
			</nav>

			<main class="content">
				<div class="container-fluid p-0">
					<div class="row mb-2 mb-xl-3">
						<div class="col-auto d-none d-sm-block">
							<h3><strong>Employee</strong> Dashboard</h3>
						</div>
					</div>

					@yield('content')
				</div>
			</main>

			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-start">
							<p class="mb-0">
								<a href="#" class="text-muted"><strong>Digieagle INC</strong></a> &copy;
							</p>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>

<script src="{{asset('js/app.js')}}"></script>

<script>

		$(document).ready(function() {
		
		function fetchNotifications() {
			$.ajax({
				url: "{{ url('emp/getnotification') }}",
				method: 'GET',
				success: function(response) {
					$('#notification-list').empty(); // Clear existing notifications
		
					let notificationCount = response.length; // Store notification count
		
					// Update notification count display
					$('#notification-count').text(notificationCount);
		
					if (notificationCount === 0) {
						// No notifications available
						$('#notification-list').append('<div class="text-muted">No notifications available.</div>');
					} else {
						response.forEach(function(notification) {
		
							var url = notification.url;
							var id = notification.id;
		
							var notificationHtml = `
								<a href="${url}" class="list-group-item notification-link" data-id="${id}">
									<div class="row g-0 align-items-center">
										<div class="col-10 ps-2">
											<div class="text-dark">${notification.title}</div>
											<div class="text-muted small mt-1">${notification.message}</div>
											<div class="text-muted small mt-1">${notification.created_at}</div>
										</div>
									</div>
								</a>
							`;
							$('#notification-list').append(notificationHtml);
						});
					}
				},
				error: function(xhr) {
					console.error('Error fetching notifications:', xhr.responseText);
				}
			});
		}
		
		
		// Initial fetch of notifications when the page loads
		fetchNotifications();
		
		// Set interval to refresh notifications every 60 seconds
		setInterval(fetchNotifications, 60000);
		});
		
</script>

@yield('scripts')
</body>
</html>
