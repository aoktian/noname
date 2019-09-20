<?php $this->layout('layout')?>

<?php $this->start('main')?>

<table class="table table-bordered table-striped tmiddle tcenter">
<thead>
<tr>
<th>ID</th>
<th>姓名</th>
<th class="left">EMAIL</th>
<th>部门</th>
<th>操作</th>
</tr>
</thead>
<tbody>
<?php foreach ($users as $one): ?>
<tr>
<td><?=$one->id?></td>
<td><?=$one->name?></td>
<td class="left"><?=$one->email?></td>
<td><?=$departments[$one->department]->name?></td>
<td>
<div class="btn-group" role="group">
<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
操作菜单
</button>

<div class="dropdown-menu">
<a class="dropdown-item" href="javascript:ajax('/user/add?id=<?=$one->id?>');">修改编辑</a>
<a class="dropdown-item" href="javascript:ajax('/user/delform/<?=$one->id?>');">删除</a>
</div>

</div>

</td>
</tr>
<?php endforeach?>
</tbody>
</table>

<?php $this->stop();?>

<?php $this->start('script')?>
<script type="text/javascript">
var users = <?=json_encode($users)?>;
</script>
<?php $this->end()?>
