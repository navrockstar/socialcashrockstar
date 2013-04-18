<?php
if(@$_GET['file']){
$file = 'fixtures/pdf/test.pdf';
$filename = 'test.pdf'; /* Note: Always use .pdf at the end. */

header('Content-type: application/pdf');
header('Content-Disposition: inline; filename="' . $filename . '"');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($file));
header('Accept-Ranges: bytes');

@readfile($file);
}
?>
<?php



?>
<html>
  <title>click2mail UI</title>
	<h2>Send Postcard to click2mail</h2>
	<form method="POST" action="test_click2mail.php" enctype="multipart/form-data">
	<div align="right" style="width:550px;"><span>Selected file to upload </span><a href="click2mail_UI.php?file=test.pdf">test.pdf</a></div>
	<input type="hidden" name="filename" value="fixtures/pdf/test.pdf">
		<table>
			<tr>
				<th>Username</th>
				<td><input type="text" name="txtuname"></td>
			</tr>
			<tr>
				<th>Password</th>
				<td><input type="password" name="txtpwd"></td>
			</tr>
			<tr><TD></TD></tr>
			<tr><TD>Upload a Mailing List</TD><TD><input type="file"  name="csv"  value=""></TD></tr>
			<!--
			<tr><TD>Recipient Details</TD></tr>
			<tr><TD></TD></tr>
			<tr>
				<th>Name</th>
				<td><input type="text" name="txtname"></TD>
			</tr>
			<tr>
				<th>Address</th>
				<td><input type="text" name="txtadd"></td>
			</tr>
			<tr>
				<th>City</th>
				<td><input type="text" name="txtcity"></td>
			</tr>
			<tr>
				<th>State</th>
				<td><input type="text" name="txtstate"></td>
			</tr>
			<tr>
				<th>Zip</th>
				<td><input type="text" name="txtzip"></td>
			</tr>-->

		</table>
		<div align="left"><input type="submit" name="submit" value="Send"></div>

	</form>

</html>
