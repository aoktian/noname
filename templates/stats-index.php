<?php $this->layout('layout')?>
<?php $this->start('main')?>


<div class="form-row">
<div class="col-lg-6">
<form method="GET" action="/stats/tag">
<div class="form-inline">

<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">条件</span>
</div>
<select onchange="onChangePro( this.value, '#filterTags', '版本' );" name="pro" class="form-control">
<option value="0">项目</option>
<?php $this->insert('selection-users', ['data' => $pros, 'slt' => isset($pro_slt) ? $pro_slt : 0])?>
</select>
</div>

<div class="input-group ml-2">
<select name="tag" class="form-control" id="filterTags">
<option value="0">版本</option>
<?php $this->insert('selection-users', ['data' => $tags, 'slt' => isset($tag_slt) ? $tag_slt : 0])?>
</select>
<div class="input-group-append">
<button class="btn btn-primary" type="submit">根据版本查询</button>
</div>
</div>

</div>
</form>

</div>
<div class="col-lg-6">

<form role="form" method="GET" action="/stats/intime">
<div class="form-inline">

<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">开始</span>
</div>
<input value="<?=(isset($t_start_show) ? $t_start_show : '')?>" name="t_start" class="form-control" type="text" onclick="showcalendar(event, this)">
</div>

<div class="input-group ml-2">
<div class="input-group-prepend">
<span class="input-group-text">结束</span>
</div>
<input value="<?=(isset($t_end_show) ? $t_end_show : '')?>" name="t_end" class="form-control" type="text" onclick="showcalendar(event, this)">
<div class="input-group-append">
<button class="btn btn-primary" type="submit">根据时间查询</button>
</div>
</div>

</form>
</div>

</div>
</div>

<hr>

<?php if (isset($s_all)) {?>
<div class="row">
<div class="col-lg-12">
<table class="table table-bordered table-striped table-hover tmiddle tcenter">
<thead>
<tr>
<th width="200">#id</th>
<th width="80">总数</th>
<th width="80">新增</th>
<th width="80">处理</th>
<th width="80">已解决</th>
<th width="80">可测试</th>
<th width="80">已通过</th>
<th width="80">完成</th>
<th class="left">进度</th>
</tr>
</thead>
<tbody>
<tr>
<td>全部</td>
<?php $this->insert('tag-statistics-td', ['one' => $s_all])?>
</tr>
<tr> <td colspan="11"></td> </tr>
<?php foreach ($s_department as $id => $one) {?>
<tr>
<td><?php echo $departments[$id]->name; ?></td>
<?php $this->insert('tag-statistics-td', ['one' => $one])?>
</tr>
<?php }?>

<tr> <td colspan="11"></td> </tr>
<?php foreach ($s_pro as $id => $one) {?>
<tr>
<td><?php echo $pros[$id]->name; ?></td>
<?php $this->insert('tag-statistics-td', ['one' => $one])?>
</tr>
<?php }?>

<tr> <td colspan="11"></td> </tr>
<?php foreach ($s_leader as $id => $one) {?>
<tr>
<td><?php echo $users[$id]->name; ?></td>
<?php $this->insert('tag-statistics-td', ['one' => $one])?>
</tr>
<?php }?>


</tbody>
</table>
</div>
</div>


<?php }?>

<?php $this->end()?>

<?php $this->start('script')?>
<script type="text/javascript">
var tags = <?=json_encode($tags)?>;
</script>
<?php $this->end()?>
