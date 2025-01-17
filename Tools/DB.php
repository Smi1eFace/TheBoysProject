<?php
	$servername = "localhost";
	$sqliUsername = "root";
	$sqliPassword = "1234";
	$dbname = "TheBoys";

	$conn = new mysqli($servername,$sqliUsername,$sqliPassword,$dbname);

	function Select($sel,$from,$where,$like)
	{
		global $conn;
		$sqlCommand = "SELECT $sel FROM `$from` WHERE $where LIKE ?";
		$stmt = $conn->prepare($sqlCommand);
		$like = "%" . $like . "%";
		$stmt->bind_param("s", $like);
		$stmt->execute();
		return $stmt->get_result();
	}

	function AddOnATableCell($update, $set, $addedText, $uniqueIdentifier)
	{
		global $conn;
		$addedText = $conn->real_escape_string($addedText);
		$uniqueIdentifier = $conn->real_escape_string($uniqueIdentifier);

		$sqlCommand = "UPDATE `$update` SET $set = CONCAT($set, ?) WHERE UniqueIdentifier = ?;";
		
		$stmt = $conn->prepare($sqlCommand);
		$stmt->bind_param("ss", $addedText,$uniqueIdentifier);
		$stmt->execute();
	}

	function AddDebt($update, $amount, $uniqueIdentifier)
	{
		global $conn;
		$amount = $conn->real_escape_string($amount);
		$uniqueIdentifier = $conn->real_escape_string($uniqueIdentifier);

		$sqlCommand = "UPDATE `$update` SET TotalDebt = TotalDebt + ? WHERE UniqueIdentifier = ?;";
		$stmt = $conn->prepare($sqlCommand);
		$stmt->bind_param("is", $amount,$uniqueIdentifier);
		$stmt->execute();
	}
?>