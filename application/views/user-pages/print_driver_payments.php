<!-- CSS goes in the document HEAD or added to your external stylesheet -->
<style type="text/css">
table.gridtable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
}
table.gridtable th {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #dedede;
}
table.gridtable td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
}
</style>

<!-- Table goes in the document BODY -->
<table class="gridtable" width="100%">
<tr>
	<th>Sl.no</th><th>Particulars</th><th>Amount</th>
</tr>
<tr>

	<td width="10%">1</td>
	<td width="70%"></td>
	<td width="20%"><?php echo $values[0]['Driver_name']; ?></td>
</tr>

</table>