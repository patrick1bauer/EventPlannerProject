
<?php
include '../events/listEvents.php';
?>

<html>

	<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	</head>
	
	<body>
		<h1>Admin Interface</h1>
		<p> Welcome to the Admin Interface here you can...
			<ul>
				<li>View a list of the title and the URL of all the events you have organized.</li>
				<li>Toggle the display list to only display active events</li>
				<li>Create a new event</li>
			</ul>
		</p>
		<h2>Have an event?</h2>
		<button>Create an Event</button>
		<h2>List of Events You've Organized...</h2>
		<input 
			type='checkbox' 
			name='my_checkbox'
        	onclick="check(this);" >
		<label for='my_checkbox'>List only active events</label>
		
		<p></p>

		<?php
		// Get the list of the events with eid=1
		getList(1);
		?>

	</body>

	<script>
		function check(cb)
		{
			var checked = cb.checked;

			var table = document.getElementById("ListEvents");
			var tr = table.getElementsByTagName("tr");

			// Loop through all table rows, and hide those who don't match the search query
			for (i = 1; i < tr.length; i++) {
				td = tr[i].getElementsByTagName("td")[2];
				if(!checked)
					tr[i].style.display = "";
				else if (td) 
				{
					var txtValue = td.textContent || td.innerText;
					if (txtValue.indexOf('true') > -1) {
						tr[i].style.display = "";
				} 
				else 
				{
					tr[i].style.display = "none";
				}
				}
			}
		}
	</script>
</html>