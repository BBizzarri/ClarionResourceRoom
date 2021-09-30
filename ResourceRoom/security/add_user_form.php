<?php
	$title = "Control Panel - Add User";
	require '../security/headerInclude.php';
?>
<script>
	function checkUserName(submitForm) {
		$.getJSON("../security/index.php",
			{	action: "SecurityCheckUserNameExists",
				username: $('#UserName').val()
			},
			function(jsonReturned) {
				// alert(JSON.stringify(jsonReturned));
				if (jsonReturned.dupeFound) {
					alert('That username is already in use.');
					$('#UserName').select();
				} else if (submitForm) {
					$('#AddUserForm').submit();
				}
			}
		);
	}
</script>
    <h1>Add User</h1>

    <form id="AddUserForm" action="../security/index.php?action=SecurityProcessUserAddEdit" method="post">

        First Name*: <input type="text" name="FirstName" size="20" value="" autofocus required ><br/>

        Last Name*: <input type="text" name="LastName" size="20" value=""><br/>

        User Name*: <input type="text" name="UserName" id="UserName" onchange="checkUserName(false)" size="20" value="" required ><br/>

        Password*: <input type="password" name="Password" size="20" value=""><br/>

        Email*: <input type="text" name="Email" size="20" value=""><br/>

        <br/>

        <input type="button" onclick="checkUserName(true)" value="Submit" />

    </form>

<?php
	require '../security/footerInclude.php';
?>
