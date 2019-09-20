<?php $this->layout('layout')?>
<?php $this->start('main')?>

<form method="POST" action="/pro/store">
<div class="form-inline mb-3">

<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">添加项目</span>
</div>
<input type="text" class="form-control" name="row[name]" />
<div class="input-group-append">
<button type="submit" class="btn btn-primary">添加</button>
</div>
</div>

</div>
</form>


<table class="table table-bordered table-striped table-hover tmiddle">
<thead>
<tr>
<th width="50">#id</th>
<th width="120"> 名称 </th>
<th width="200"> 创建时间 </th>
<th> 操作 </th>
</tr>
</thead>
<tbody>
<?php foreach ($pros as $pro): ?>
<tr>
<form method="POST" action="/pro/store">
<input type="hidden" name="id" value="<?=$pro->id?>">
<td><?=$pro->id?></td>
<td>
<input type="text" class="form-control" name="row[name]" value="<?=$pro->name?>">
</td>
<td><?=$pro->created_at?></td>
<td>
<button type="submit" class="btn btn-primary">修改名字</button>
<a href="/pro/destroy/<?=$pro->id?>" class="btn btn-danger">删除</a>
</td>
</form>
</tr>
<?php endforeach?>
</tbody>
</table>


<?php $this->end()?>
