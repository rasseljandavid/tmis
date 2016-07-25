<?php

include('config.php');


$sql = "SELECT firstname, lastname, loans.id as loan_id , capital, interest, monthsToPay FROM loans, clients WHERE paid = 0 and loans.active = 1 and clients.active = 1 AND client_id = clients.id ORDER BY lastname ASC";

$res  = mysql_query($sql);
echo "<table>";
$total = 0;
while($row  = mysql_fetch_object($res)) {
$sql2 = "SELECT sum(capital+interest) as total FROM payments WHERE loan_id = '{$row->loan_id}' AND active = 1";
$res2  = mysql_query($sql2);
$row2  = mysql_fetch_object($res2);

$balance = ($row->capital + ($row->capital * $row->interest * $row->monthsToPay)) - $row2->total;
echo "<tr>";
echo "<td>{$row->firstname} {$row->lastname}</td>";

echo "<td>P" . number_format($balance, 2) . "</td>";
echo "</tr>";
$total += $balance;

}

?>
<tr>
	<td>Total:</td>
	<td>P<?php echo number_format($total, 2); ?></td>
</tr>
</table>