<?php

if (!empty($_GET['id'])) {
	$id = (int) $_GET['id'];
}

if (!empty($id)) {
	$pageTitle = "Edit User";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://localhost/tonic3-dev-test/app/api/getUser/".$id);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	$formdata = json_decode($response, true);
	curl_close($ch);
} else {
	$pageTitle = "Create User";
}

if (!empty($_POST)) {
	$formdata = _clean($_POST);
}

include ("views/includes/header.php");
include ("views/includes/body.php");
?>
<header class="page">
	<h2 class="page_title"><?=$pageTitle?></h2>
	<a href="/tonic3-dev-test/app/list/" class="back-btn">&lt; Back to User List</a>
</header>
<form name="api_form" autocomplete="off" id="api_form" action="#api" method="post">
	<div id="message"></div>
	<label id="firstname_element">
		<span class="label">Name *</span>
		<input type="text" name="firstname" id="firstname" value="<?php 
			if(!empty($formdata['firstname'])) {
				echo $formdata['firstname'];
			}
			?>" placeholder="Name" />
	</label>
	<label id="surname_element">
		<span class="label">Surname *</span>
		<input type="text" name="surname" id="surname" value="<?php 
			if(!empty($formdata['surname'])) {
				echo $formdata['surname'];
			}
			?>" placeholder="Surname" />
	</label>
	<label id="email_element">
		<span class="label">Email *</span>
		<input type="email" name="email" id="email" value="<?php 
			if(!empty($formdata['email'])) {
				echo $formdata['email'];
			}
			?>" placeholder="Email" />
	</label>
	<label id="password_element">
		<span class="label">Password *</span>
		<?php
		if (!empty($id)) {
			echo '<span class="info">To change your password, enter a new one</span>';
		}
		?>		
		<input type="password" name="password" id="password" />
	</label>
	<label id="confirm_password_element">
		<span class="label">Confirm Password *</span>
		<input type="password" name="confirm_password" id="confirm_password"/>
	</label>
	<label id="country_element">
		<span class="label">Country *</span>
		<input type="text" name="country" id="country" value="<?php 
			if(!empty($formdata['country'])) {
				echo $formdata['country'];
			}
			?>" placeholder="Country" />
		<input type="hidden" name="country_code" id="country_code" value="<?php 
			if(!empty($formdata['country_code'])) {
				echo $formdata['country_code'];
			}
			?>" />
	</label>
	<label id="phone_element">
		<span class="label">Phone *</span>
		<input type="phone" name="phone" id="phone" value="<?php 
			if(!empty($formdata['phone'])) {
				echo $formdata['phone'];
			}
			?>" placeholder="Phone" />
	</label>
	<input type="hidden" name="form_action" id="form_action" value="<?php
	if (!empty($id)) {
		echo "edit";
	} else {
		echo "create";
	}
	?>" />
	<input type="hidden" name="user_id" id="user_id" value="<?php
	if (!empty($id)) {
		echo $id;
	} else {
		echo "";
	}
	?>" />
	<button type="submit" id="submit_btn" onclick="return false;">Save</button>
</form>
<?php
include ("views/includes/subfooter.php");
include ("views/includes/footer.php");