<?php $this->layout('layout', ['title' => '给你点颜色'])?>
<?php $this->start('main')?>

<?php
$colors = [
    '白' => '#ffffff',
    '蓝' => '#007bff',
    '绿' => '#28a745',
    '红' => '#dc3545',
    '橙' => '#ffc107',
    '紫' => '#c477f4',
    '黑' => '#343a40',
    '灰' => '#6c757d',
];
?>
<table class="table table-bordered">
<?php foreach ($colors as $name => $color): ?>
<tr>
<th width="200">
<?=$name . $color?>
</th>
<td style="background-color:<?=$color?>"> </td>
</tr>
<?php endforeach?>
</table>

<?php $this->end()?>
