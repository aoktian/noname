<?php
$newnum = $one['new'];
unset($one[90]);
unset($one[99]);
unset($one['new']);

$status = I\App::Singleton()->getconfig('worktime', 'status');
foreach ($status as $key => $value) {
    if (!isset($one[$key])) {
        $one[$key] = 0;
    }
}

$total = array_sum($one);
$p     = 0;
if ($total > 0) {
    $p = intval($one[98] / $total * 10000) / 100;
}
?>
<td><?=$total?></td>
<td><?=$newnum?></td>
<td><?=$one[10] + $one[11] + $one[12] + $one[21] + $one[22] + $one[23] + $one[24] + $one[25]?></td>
<td><?=$one[30]?></td>
<td><?=$one[50]?></td>
<td><?=$one[60]?></td>
<td><?=$one[98]?></td>
<td class="left">
<?php $this->insert('progress', ['p' => $p])?>
</td>
