<?php $this->layout('layout')?>

<?php $this->start('main')?>

<div class="form-inline" id="mod_ae">

<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">模块名字</span>
</div>
<input itag="forme" name="udata[name]" type="text" value="" class="form-control" >
</div>

<div class="input-group ml-2">
<div class="input-group-prepend">
<span class="input-group-text">类型</span>
</div>
<select itag="forme" name="udata[caty]" class="form-control">
<option value="1">系统</option>
<option value="2">资源</option>
</select>
</div>

<div class="input-group ml-2">
<div class="input-group-prepend">
<span class="input-group-text">任务组</span>
</div>
<select itag="forme" name="udata[gid]" class="form-control">
<option value="0">选择任务组</option>
<?php foreach ($g as $k => $v): ?>
<option value="<?=$v->id?>"><?=$v->name?></option>
<?php endforeach?>
</select>
</div>

<button type="button" class="btn btn-primary ml-2" onclick="submitform('/golist/mod_ae', 'mod_ae')">添加</button>

</div>


<hr>


<table class="table table-striped tmiddle">
<tr>
<th>ID</th>
<th>名字</th>
<th>分类</th>
<th>任务组</th>
<th>详细</th>
<th>操作</th>
</tr>
<?php
$catys = [1 => '系统', 2 => '资源'];
foreach ($list as $one) {
    ?>
<tr class="rlist">
<td><?=$one->id?></td>
<td><?=$one->name?></td>
<td><?=$catys[$one->caty]?></td>
<td><?=$g[$one->gid]->name?></td>
<td>
<?php if (isset($gs[$one->gid])) {
        foreach ($gs[$one->gid] as $gsone) {?>
<button type="button" class="btn btn-primary"><?=$tasks[$gsone->tid]->name?></button>
<?php }
    }
    ?>
</td>
<td>
<div class="btn-group" role="group">
<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
操作
</button>

<div class="dropdown-menu">
<a onclick="up(this, '/golist/mod_r?id=<?=$one->id?>&updown=up')" class="dropdown-item" href="javascript:void(0);">上移</a>
<a onclick="down('/golist/mod_r?id=<?=$one->id?>&updown=down')" class="dropdown-item" href="javascript:void(0);">下移</a>
<a class="dropdown-item" href="javascript:ajax('/golist/mod_modify/<?=$one->id?>');">编辑</a>
<a class="dropdown-item" href="javascript:ajax('/golist/mod_del/<?=$one->id?>');">删除</a>
</div>
</div>


</td>
</tr>
<?php }?>
</table>


<?php $this->stop();?>

<?php $this->start('script')?>
<?php $this->insert('updown');?>
<?php $this->stop()?>
