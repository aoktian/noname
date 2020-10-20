<?php $this->layout('layout')?>
<?php $this->start('main')?>

<?php
$titlecatys = I\App::singleton()->getconfig('worktime', 'title');
?>
<form method="POST" action="/title/store">

<div class="form-inline mb-3">

<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">名称</span>
</div>
<input itag="val" name="row[name]" type="text" class="form-control">
</div>

<div class="input-group ml-2">
<div class="input-group-prepend">
<span class="input-group-text">类型</span>
</div>
<select itag="val" name="row[caty]" class="form-control">
<option value="">选择类型</option>
<?php $this->insert('selection', ['data' => $titlecatys, 'slt' => 0])?>
</select>
</div>

<button type="submit" class="btn btn-primary ml-2">添加</button>

</div>

</form>


<table class="table table-bordered table-striped table-hover vertical-middle">
<thead>
<tr>
<th width="50">#id</th>
<th width="150"> 名称 </th>
<th width="150"> 排序 </th>
<th width="150"> 类型 </th>
<th> 操作 </th>
</tr>
</thead>
<tbody>
<?php foreach ($titles as $row): ?>
<?php $title = (object) $row;?>
<tr>
<form method="POST" action="/title/store">
<input type="hidden" name="id" value="<?=$title->id?>">
<td><?=$title->id?></td>
<td>
<input type="text" class="form-control" name="row[name]" value="<?=$title->name?>">
</td>
<td>
<input type="text" class="form-control" name="row[r]" value="<?=$title->r?>">
</td>
<td>
<?php if ($title->locked): ?>
<?=$titlecatys[$title->caty]?>
<?php else: ?>
<select itag="val" name="row[caty]" class="form-control">
<?php $this->insert('selection', ['data' => $titlecatys, 'slt' => $title->caty])?>
</select>
<?php endif?>
</td>
<td>
<button type="submit" class="btn btn-primary"> 修改 </button>
<?php if (!$title->locked): ?>
<a href="/title/del/<?=$title->id?>" class="btn btn-danger">删除</a>
<?php endif?>
</td>
</form>
</tr>
<?php endforeach?>
</tbody>
</table>


<?php $this->end()?>
