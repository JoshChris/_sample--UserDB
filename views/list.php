<?php

$pageTitle = "User List";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost/tonic3-dev-test/app/api/get/");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$users = json_decode($response, true);
curl_close($ch);

include ("views/includes/header.php");
include ("views/includes/body.php");
?>
	<header class="page">
		<h2 class="page_title">User List</h2>
		<div class="search">
			<form name="searchForm" action="#search" method="POST">
				<input type="text" id="keyword" placeholder="Search Keywords" />
			</form>
		</div>
	</header>
	<div id="message"></div>
	<div class="userlist" id="searchlist" style="display: none;">
		<h2>Search Results</h2>
		<div class="labels">
			<div class="label name">Name</div>
			<div class="label surname">Surname</div>
			<div class="label email">Email Address</div>
			<div class="label options">Options</div>
		</div>
		<div id="ajax_list"></div>
	</div>
	<div class="userlist" id="userlist">
		<div class="labels">
			<div class="label name">Name</div>
			<div class="label surname">Surname</div>
			<div class="label email">Email Address</div>
			<div class="label options">Options</div>
		</div>
		<?php foreach ($users as $user): ?>
			<div class="user" id="user<?=$user['id']?>">
				<div class="label name"><?=$user['firstname']?></div>
				<div class="label surname"><?=$user['surname']?></div>
				<div class="label email"><?=$user['email']?></div>
				<div class="label options"><a class="editUser" href="/tonic3-dev-test/app/edit/?id=<?=$user['id']?>">Edit</a> <a href="#delete" data-id="<?=$user['id']?>" class="deleteUser">Delete</a></div>
			</div>
		<?php endforeach; ?>
		<script type="text/template" id="user_template">
			<div class="user" id="user">
				<div class="label name"></div>
				<div class="label surname"></div>
				<div class="label email"></div>
				<div class="label options"></div>
			</div>
		</script>
		<a href="/tonic3-dev-test/app/create/" class="create-btn">+ Create New User</a>
	</div>
<?php
include ("views/includes/subfooter.php");
include ("views/includes/footer.php");