<?php 
   session_start();

   include '/home/customer/www/client.desertcitystays.com/smoobu/db-connect.php';

   include '/home/customer/www/client.desertcitystays.com/smoobu/functions.php';

   $booking_details_for_ll = get_booking_details_for_ll($conn, $_SESSION['id']);

?>   
<!doctype html>
<html lang="en" dir="ltr">
	
	<head>
		<?php include 'layouts/styles.php'; ?>
		

	</head>

	<body class="app sidebar-mini ltr light-mode">

		<!-- GLOBAL-LOADER -->
		<div id="global-loader">
			<img src="assets/images/loader.svg" class="loader-img" alt="Loader">
		</div>
		<!-- /GLOBAL-LOADER -->

		<!-- PAGE -->
		<div class="page">
			<div class="page-main">

				<!-- APP-HEADER -->
                <?php include 'layouts/app-header.php'; ?>

                <!-- /APP-HEADER -->

                <!--APP-SIDEBAR-->
                <?php include 'layouts/app-sidebar.php'; ?>

                <!--/APP-SIDEBAR-->

				<!-- APP-CONTENT OPEN -->
				<div class="main-content app-content mt-0">
					<div class="side-app">

						<!-- CONTAINER -->
						<div class="main-container container-fluid">

							<!-- PAGE-HEADER -->
							<div class="page-header">
								<div>
									<h1 class="page-title">Booking Details</h1>
									<ol class="breadcrumb">
										<li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
										<li class="breadcrumb-item active" aria-current="page">Booking Details</li>
									</ol>
								</div>
							</div>
							<!-- PAGE-HEADER END -->

							<!-- ROW -->
							<div class="row row-sm">
								<div class="col-lg-12">
									<div class="card">
										<div class="card-header">
											<h3 class="card-title">File Export</h3>
										</div>
										<div class="card-body">
											<div class="table-responsive export-table">
												<table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom  w-100">
													<thead>
														<tr>
															<!-- <th class="border-bottom-0">Booking Date</th> -->
															<th class="border-bottom-0">Property Name</th>
															<th class="border-bottom-0">Booking Amount</th>
															<th class="border-bottom-0">Booking Channel</th>
															<th class="border-bottom-0">Arrival Date</th>
															<th class="border-bottom-0">Departure Date</th>
														</tr>
													</thead>
													<tbody>
														<?php
														foreach($booking_details_for_ll as $booking_detail_for_ll){
															extract($booking_detail_for_ll);

															$booking_date = $booking_detail_for_ll['smb_created-at'];
															$apartment_name = $booking_detail_for_ll['smb_apartment-name'];
															$channel_name = $booking_detail_for_ll['smb_channel-name'];

															$u_booking_amount = calculate_booking_amount_for_ll($channel_name, $smb_price);

															$table_row = "";
															$table_row.= "<tr>";
															//$table_row.= "<td>$booking_date </td>";
															$table_row.= "<td>$apartment_name</td>";
															$table_row.= "<td>AED $u_booking_amount</td>";
															$table_row.= "<td>$channel_name</td>";
															$table_row.= "<td>$smb_arrival</td>";
															$table_row.= "<td>$smb_departure</td>";
															$table_row.= "</tr>";

															echo $table_row;
														}
														?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- END ROW -->

						</div>
						<!-- CONTAINER CLOSED -->
					</div>
				</div>
				<!-- APP-CONTENT CLOSED -->
			</div>

			<!--SIDEBAR-RIGHT-->
            <?php include 'layouts/sidebar-right.php'; ?>

            <!--/SIDEBAR-RIGHT-->

            <!-- FOOTER -->
            <?php include 'layouts/footer.php'; ?>

            <!-- FOOTER END --> 

		</div>

		<?php include 'layouts/scripts.php'; ?>

    
		<!-- INTERNAL SELECT2 JS -->
		<script src="assets/plugins/select2/select2.full.min.js"></script>

		<!-- DATA TABLE JS-->
		<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
		<script src="assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
		<script src="assets/plugins/datatable/js/dataTables.buttons.min.js"></script>
		<script src="assets/plugins/datatable/js/buttons.bootstrap5.min.js"></script>
		<script src="assets/plugins/datatable/js/jszip.min.js"></script>
		<script src="assets/plugins/datatable/pdfmake/pdfmake.min.js"></script>
		<script src="assets/plugins/datatable/pdfmake/vfs_fonts.js"></script>
		<script src="assets/plugins/datatable/js/buttons.html5.min.js"></script>
		<script src="assets/plugins/datatable/js/buttons.print.min.js"></script>
		<script src="assets/plugins/datatable/js/buttons.colVis.min.js"></script>
		<script src="assets/plugins/datatable/dataTables.responsive.min.js"></script>
		<script src="assets/plugins/datatable/responsive.bootstrap5.min.js"></script>
		<script src="assets/js/table-data.js"></script>

		<?php include 'layouts/main-scripts.php'; ?>


	</body>
</html>