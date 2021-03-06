<?php
/*******************************************************************************
 *
 *  filename    : PaddleNumList.php
 *  last change : 2009-04-15
 *  website     : http://www.churchdb.org
 *  copyright   : Copyright 2009 Michael Wilt
 *
 *  ChurchInfo is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

//Include the function library
require "Include/Config.php";
require "Include/Functions.php";

$linkBack = FilterInput($_GET["linkBack"]);

$iFundRaiserID = $_SESSION['iCurrentFundraiser'];

if ($iFundRaiserID > 0) {
	//Get the paddlenum records for this fundraiser
	$sSQL = "SELECT pn_ID, pn_fr_ID, pn_Num, pn_per_ID,
	                a.per_FirstName as buyerFirstName, a.per_LastName as buyerLastName
	         FROM paddlenum_pn
	         LEFT JOIN person_per a ON pn_per_ID=a.per_ID
	         WHERE pn_FR_ID = '" . $iFundRaiserID . "' ORDER BY pn_Num"; 
	 $rsPaddleNums = RunQuery($sSQL);
} else {
	$rsPaddleNums = 0;
}

$sPageTitle = gettext("Buyers for this fundraiser:");
require "Include/Header.php";
?>

<?php
echo "<form method=\"post\" action=\"Reports/FundRaiserStatement.php?CurrentFundraiser=$iFundRaiserID&linkBack=FundRaiserEditor.php?FundRaiserID=$iFundRaiserID&CurrentFundraiser=$iFundRaiserID\">\n";
if ($iFundRaiserID > 0)
	echo "<input type=button class=icButton value=\"".gettext("Select all")."\" name=SelectAll onclick=\"javascript:document.location='PaddleNumList.php?CurrentFundraiser=$iFundRaiserID&SelectAll=1&linkBack=FundRaiserEditor.php?FundRaiserID=$iFundRaiserID&CurrentFundraiser=$iFundRaiserID';\">\n";
	echo "<input type=button class=icButton value=\"".gettext("Select none")."\" name=SelectNone onclick=\"javascript:document.location='PaddleNumList.php?CurrentFundraiser=$iFundRaiserID&linkBack=FundRaiserEditor.php?FundRaiserID=$iFundRaiserID&CurrentFundraiser=$iFundRaiserID';\">\n";
	echo "<input type=button class=icButton value=\"".gettext("Add Buyer")."\" name=AddBuyer onclick=\"javascript:document.location='PaddleNumEditor.php?CurrentFundraiser=$iFundRaiserID&linkBack=FundRaiserEditor.php?FundRaiserID=$iFundRaiserID&CurrentFundraiser=$iFundRaiserID';\">\n";
	echo "<input type=submit class=icButton value=\"".gettext("Generate Statements for Selected")."\" name=GenerateStatements>\n";
?>

<table cellpadding="5" cellspacing="0" width="100%">

<tr class="TableHeader">
	<td><?php echo gettext("Select"); ?></td>
	<td><?php echo gettext("Number"); ?></td>
	<td><?php echo gettext("Buyer"); ?></td>
	<td><?php echo gettext("Edit"); ?></td>
	<td><?php echo gettext("Delete"); ?></td>
</tr>

<?php
$tog = 0;

//Loop through all buyers
if ($rsPaddleNums) {
	while ($aRow =mysql_fetch_array($rsPaddleNums))
	{
		extract($aRow);
	
		$sRowClass = "RowColorA";
	?>
		<tr class="<?php echo $sRowClass ?>">
			<td>
				<input type="checkbox" name="Chk<?php echo $pn_ID."\""; if (isset($_GET["SelectAll"])) echo " checked=\"yes\"";?>></input>
			</td>
			<td>
				<?php echo $pn_Num?>&nbsp;
			</td>
			<td>
				<?php echo $buyerFirstName . " " . $buyerLastName ?>&nbsp;
			</td>
			<td>
				<a href="PaddleNumEditor.php?PaddleNumID=<?php echo $pn_ID . "&linkBack=PaddleNumList.php";?>">Edit</a>
			</td>
			<td>
				<a href="PaddleNumDelete.php?PaddleNumID=<?php echo $pn_ID . "&linkBack=PaddleNumList.php?FundRaiserID=" . $iFundRaiserID;?>">Delete</a>
			</td>
		</tr>
	<?php
	} // while
} // if
?>

</table>
</form>

<?php
require "Include/Footer.php";
?>
