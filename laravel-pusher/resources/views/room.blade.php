<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Chat Room</title>
	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="./css/app.css">
</head>
<body>
	<center><h1>Chat Room</h1></center>
	<div class="comntainer m-8">
		<div class="row m-8 p-5">
			<div class="col-xs-6">
				<div class="card">
					<div class="card-body">
						<form id="chatForm">
							<div class="mb-3" id="messageOutput"></div>
							<hr>
							<div class="form-group mb-3">
								<input type="text" class="form-control" id="message" placeholder="Message">
							</div>
							<button type="submit" class="btn btn-success">Send</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="./js/app.js"></script>
</body>
</html>
