<html>
	<body>
		<h1>Create an Event</h1>
		<form action="../events/createEvent.php" method="post">
			Name: <input type="text" name="name" /><br><br>

			Admin: <input type="number" name="admin" /><br><br>

			State: <input type="text" name="state" /><br><br>

			City: <input type="text" name="city" /><br><br>

			Zip: <input type="text" name="zip" /><br><br>

			Street: <input type="text" name="street" /><br><br>

			Description: <input type="text" name="description" /><br><br>

			StartDate: <input type="datetime-local" name="startDate" /><br><br>

			EndDate: <input type="datetime-local" name="endDate" /><br><br>

			URL: <input type="url" name="url" /><br><br>
			<input type="submit" />
			<input type="button" value="Go Back" onclick="location.href='adminInterface.php'" />
		</form>
	</body>
</html>