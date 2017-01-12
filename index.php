<!doctype html>
	<head>
		<title>GitHub Stars</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.dataTables.min.css">
		<style>
		
		</style>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/responsive/2.0.2/js/dataTables.responsive.min.js"></script>
	</head>
	
	<body>
		<div class="container">
			<div id="welcome_row" class="row">
				<div class="col-md-1"></div>
				<div class="col-md-10">
					<div class="page-header"><h1>Welcome to the GitHub Stars App</h1></div>
					<button id="display_button" type="submit" class="btn btn-default" onclick="displaytable();">Display Data</button>
				</div>
			</div>
			<div id="table_row" class="row" style="display:none">
				<div class="col-md-1"></div>
				<div class="col-md-10">
					<table id="repos_table" class="display">
						<thead>
							<tr>
								<th></th><th>ID</th><th data-priority="1">Name</th><th data-priority="3">URL</th><th data-priority="6">Create Date</th><th data-priority="5">Push Date</th><th data-priority="4">Description</th><th data-priority="2">Stars</th>
							</tr>
						</thead>
						<tbody>
							<?php
								include('php/dbconn.php');
								
								$dbh = getDBConn();
								
								$query = "select * from repos";
								$stmt = $dbh->prepare($query);
								$stmt->execute();
								$result = $stmt->fetchALL(PDO::FETCH_ASSOC);
								foreach($result as $r){
									$id = $r['id'];
									$id_trunc = strlen($id) > 15 ? substr($id,0,15)."..." : $id;
									$name = $r['name'];
									$name_trunc = strlen($name) > 15 ? substr($name,0,15)."..." : $name;
									$name_trunc = htmlspecialchars($name_trunc);
									$url = $r['url'];
									$url_trunc = strlen($url) > 15 ? substr($url,0,15)."..." : $url;
									$createdt = $r['createdt'];
									$createdt_trunc = strlen($createdt) > 10 ? substr($createdt,0,10) : $createdt;
									$pushdt = $r['pushdt'];
									$pushdt_trunc = strlen($pushdt) > 10 ? substr($pushdt,0,10) : $pushdt;
									$description = $r['description'];
									$description_trunc = strlen($description) > 12 ? substr($description,0,12)."..." : $description;
									$description_trunc = htmlspecialchars($description_trunc);
									$stars = $r['stars'];
									$stars_trunc = strlen($stars) > 15 ? substr($stars,0,15)."..." : $stars;
									
									echo '<tr><td class="control"></td>
											<td>
												<a href="javascript:void(0)" data-toggle="popover" data-trigger="focus" data-content="<b>ID:</b> '.$id.'<br><b>Name:</b> '.htmlspecialchars($name).'<br><b>URL:</b> '.$url.'<br><b>Create Date:</b> '.$createdt.'<br><b>Push Date:</b> '.$pushdt.'<br><b>Description:</b> '.htmlspecialchars($description).'<br><b>Stars:</b> '.$stars.'">'.$id_trunc.'</a>
											</td>
											<td><a href="javascript:void(0)" data-toggle="popover" data-trigger="focus" data-content="'.$name.'">'.$name_trunc.'</a></td>
											<td><a href="javascript:void(0)" data-toggle="popover" data-trigger="focus" data-content="<a href=\''.$url.'\' target=\'_blank\'>'.$url.'</a>">'.$url_trunc.'</a></td>
											<td><a href="javascript:void(0)" data-toggle="popover" data-trigger="focus" data-content="'.$createdt.'">'.$createdt_trunc.'</a></td>
											<td><a href="javascript:void(0)" data-toggle="popover" data-trigger="focus" data-content="'.$pushdt.'">'.$pushdt_trunc.'</a></td>
											<td><a href="javascript:void(0)" data-toggle="popover" data-trigger="focus" data-content="'.htmlspecialchars($description).'">'.$description_trunc.'</a></td>
											<td><a href="javascript:void(0)" data-toggle="popover" data-trigger="focus" data-content="'.$stars.'">'.$stars_trunc.'</a></td>
										</tr>';
								}
							?>
						</tbody>
					</table>
				</div>
		</div>
		<script>
			$(document).ready(function(){
				$('[data-toggle="popover"]').popover({html:true});
				
				$('#repos_table').DataTable({
					responsive:true,
					columnDefs:[{
						className:'control',
						orderable:false,
						targets:0
					}],
					"order": [[ 7, "desc"]],
					"iDisplayLength": 10
				});
			});
			
			function displaytable(){
				$("#display_button").addClass("hidden");
				$("#table_row").fadeIn("slow");
				var table = $('#repos_table').DataTable();
				table.responsive.recalc();
			}
		</script>
	</body>
</html>