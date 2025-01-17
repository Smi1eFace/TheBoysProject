<?php
	if(!isset($_COOKIE['UniqueIdentifier']))
		header("Location: index.php");

	include "Tools/DB.php";

	$ui = $_COOKIE['UniqueIdentifier'];
	$result = Select("*", "Sec", "UniqueIdentifier", $_COOKIE['UniqueIdentifier']);
	if ($result->num_rows !== 1)
		header("Location: index.php");
	
	include 'Tools/header.php';
?>

<body>
	<link rel="stylesheet" href="Styles/BTStyle.css">
	<link rel="stylesheet" href="Styles/MainStyle.css">
	
	<div id="welcomeMsgDiv"><h1>
		Welcome, <?php
			echo Select("Names","Sec","UniqueIdentifier",$ui)->fetch_assoc()["Names"];
		?>.
	</h1></div>

	<div id="popup">
		<input type="button" id="exit" class="Button" value="X" onclick="closePopup()">
		<div id="popupContainer">
			<table class="popupTable">
				<tr>
					<td class="picCol" id="popupImageTd"><img id="popupImage"></td>
					<td class="nameCol" id="popupName"></td>
					<td class="debtCol" id="popupDebt"></td>
				</tr>
			</table>
			
			<form method="POST" action="Tools/AddDebt.php">
				<div class="textBoxDiv">
					<input class="textBox" type="number" id="DebtAmountTextbox" name="DebtAmount" placeholder="Debt Amount" required>
					<div id="iqd">IQD</div>
				</div>

				<div class="textBoxDiv">
					<input class="textBox" type="text" id="NoteTextbox" name="Note" placeholder="Note" required>
				</div>

				<input type="text" id="whom" name="Whom" style="display: none;">
				<input type="text" id="Common" name="Common" value="False" style="display: none;">

				<input type="submit" class="Button" value="Add">
			</form>
			
			<table class="popupTable" id="LogTable">
				<tr>
					<td class="picCol">Date</td>
					<td class="nameCol">Note</td>
					<td class="debtCol">Debt</td>
				</tr>
			</table>
		</div>
	</div>

	<div id="MainTable">
		<table>
			<tr>
				<td class="picCol">Picture</td>
				<td class="nameCol">Name</td>
				<td class="debtCol">Debt</td>
			</tr>
			<?php
				$result = Select("UniqueIdentifier","Sec","1","1");
				while($row = $result->fetch_assoc())
				{
					if(!($row['UniqueIdentifier'] === $ui))
					{
						$img = Select("ImagePath","Sec","UniqueIdentifier",$row['UniqueIdentifier'])->fetch_assoc()['ImagePath'];
						$name = Select("Names","Sec","UniqueIdentifier",$row['UniqueIdentifier'])->fetch_assoc()['Names'];
						$debt = Select("TotalDebt",$ui,"UniqueIdentifier",$row['UniqueIdentifier'])->fetch_assoc()['TotalDebt'];
						$log = Select("Log",$ui,"UniqueIdentifier",$row['UniqueIdentifier'])->fetch_assoc()['Log'];
						echo "<tr onclick=\"showPopup('$img', '$name', '$debt IQD','$log')\">";
						echo "<td><img src=\"$img\"></td>";
						echo "<td>$name</td>";
						echo "<td>$debt IQD</td>";
						echo "</tr>";
					}
				}
			?>
		</table>
	</div>

	<div id="bottomDiv">
		<div id="bottomContainer">

			<h1>Add Common Debt</h1>
			
			<select id="selectPerson" name="selectPerson">
				<option>Select Person</option>
				<?php
					$result = Select("Names","Sec","1","1");
					while($row = $result->fetch_assoc())
					{
						$name = $row["Names"];
						echo "<option value=\"$name\">$name</option>";
					}
				?>
			</select>
		
			<form method="POST" action="Tools/AddDebt.php">
				<textarea placeholder="Names" id="nameList" name="Whom" readonly></textarea>

				<select id="removePerson" name="removePerson">
					<option>Remove Person</option>
				</select>

				<div class="textBoxDiv">
					<input class="textBox" type="number" id="DebtAmountTextbox" name="DebtAmount" placeholder="Debt Amount" required>
					<div id="iqd">IQD</div>
				</div>

				<div class="textBoxDiv">
					<input class="textBox" type="text" id="NoteTextbox" name="Note" placeholder="Note" required>
				</div>

				<input type="text" id="Common" name="Common" value="True" style="display: none;">

				<input type="submit" class="Button" value="Add">
			</form>
		</div>
	</div>

	<script type="text/javascript">
		const sb = document.getElementById('selectPerson');
		const sbr = document.getElementById('removePerson');
		const list = document.getElementById('nameList');
	
		function showPopup(ImagePath, Name, Debt, Log) {
			document.getElementById('popup').style.display = 'flex';
			document.getElementById('popupImage').src = ImagePath;
			document.getElementById('popupName').textContent = Name;
			document.getElementById('popupDebt').textContent = Debt;
			document.getElementById('whom').value = Name;
			
			var temp = Log.split(',');
			var ArrayOfLogsData = [];
			for (var i = 0; temp.length-1 > i; i++) {
				ArrayOfLogsData.push(temp[i].split(';'));
			}

			for (var i = ArrayOfLogsData.length-1;  i >= 0; i--)
			{
				var newRow = document.getElementById('LogTable').insertRow();
				for(var u = 0; u < 3; u++)
				{
					newRow.insertCell(u).textContent = ArrayOfLogsData[i][u];
				}
			}

			document.body.style.overflow = 'hidden';
		}
		
		function closePopup() {
			document.getElementById('popup').style.display = 'none';

			var table = document.getElementById("LogTable");
			table.innerHTML = "<tr><td class=\"picCol\">Date</td><td class=\"nameCol\">Note</td><td class=\"debtCol\">Debt</td></tr>"

			document.body.style.overflow = '';
		}
		
		sb.addEventListener('change',
			function() {
				const selectedOption = sb.options[sb.selectedIndex];
				list.value += selectedOption.text + '\n';
				
				sb.remove(sb.selectedIndex);

				const newOption = document.createElement('option');
	    		newOption.value = selectedOption.value;
				newOption.text = selectedOption.text;
				sbr.appendChild(newOption);
			}
		);

		sbr.addEventListener('change',
			function() {
				var arrayOfvalues = list.value.split('\n');
				const selectedOption = sbr.options[sbr.selectedIndex];
				const index = arrayOfvalues.indexOf(selectedOption.text);
				
				arrayOfvalues.splice(arrayOfvalues.indexOf(selectedOption.text), 1);
				list.value = arrayOfvalues.join('\n');
				
				sbr.remove(sbr.selectedIndex);
				
				const newOption = document.createElement('option');
				newOption.value = selectedOption.value;
				newOption.text = selectedOption.text;
				sb.appendChild(newOption);
			}
		);
	</script>
</body>