<?php
	if(!isset($_COOKIE['UniqueIdentifier']))
		header("Location: /index.php");

	include "DB.php";

	$ui = $_COOKIE['UniqueIdentifier'];
	$result = Select("*", "Sec", "UniqueIdentifier", $_COOKIE['UniqueIdentifier']);
	if ($result->num_rows !== 1)
		header("Location: /index.php");

	if(isset($_POST['DebtAmount']) && isset($_POST['Note']) && isset($_POST['Whom'])) {
		try{
			$debtAmount = (int)(str_replace([";",","] , "", $_POST['DebtAmount']));
		}
		catch(Exception $e) {
			header("Location: /index.php");
		}
		$note = str_replace([";",","] , "", $_POST['Note']);
		$whom = $_POST['Whom'];

		if($_POST['Common'] == "False")
		{
			$uiP2 = Select("UniqueIdentifier","Sec","Names",$whom)->fetch_assoc()['UniqueIdentifier'];
			$AddToLogP1 = date("Y/m/d"). ";" . $note . ";" . (string)$debtAmount. ",";
			$AddToLogP2 = date("Y/m/d"). ";" . $note . ";" . (string)($debtAmount*-1) . ",";
			AddOnATableCell($ui,"Log",$AddToLogP1,$uiP2);
			AddOnATableCell($uiP2,"Log",$AddToLogP2,$ui);
			AddDebt($ui, $debtAmount, $uiP2);
			AddDebt($uiP2, $debtAmount*-1, $ui);
			header("Location: /index.php");
		}
		else {
			$whom = explode("\n", $whom);
			array_pop($whom);
			$dividedDebt = round($debtAmount / count($whom),0);
			foreach ($whom as $person) {
				$person = substr($person, 0, -1);
				$uiP2 = Select("UniqueIdentifier","Sec","Names",$person)->fetch_assoc()['UniqueIdentifier'];
				if(!($uiP2 == $ui)){
					$AddToLogP1 = date("Y/m/d"). ";" . $note . ";" . (string)$dividedDebt. ",";
					$AddToLogP2 = date("Y/m/d"). ";" . $note . ";" . (string)($dividedDebt*-1) . ",";
					AddOnATableCell($ui,"Log",$AddToLogP1,$uiP2);
					AddOnATableCell($uiP2,"Log",$AddToLogP2,$ui);
					AddDebt($ui, $dividedDebt, $uiP2);
					AddDebt($uiP2, $dividedDebt*-1, $ui);
				}
			}
			header("Location: /index.php");
		}
	}
?>