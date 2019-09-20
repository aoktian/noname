<?php $this->layout('layout')?>

<?php $this->start('main')?>

<div class="form-inline mb-3" id="task_ae">

<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">名字</span>
</div>
<input itag="forme" name="udata[name]" type="text" value="" class="form-control" >
<div class="input-group-append">
<button onclick="submitform('/golist/task_ae', 'task_ae')" class="btn btn-outline-secondary" type="button">添加</button>
</div>
</div>

</div>


<div class="form-inline">
<?php foreach ($list as $i => $one) {?>
<div class="input-group mr-2 mb-2">
<input id="val-<?=$one->id?>" type="text" class="form-control" value="<?=$one->name?>">
<div class="input-group-append">
<button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">操作</button>
<div class="dropdown-menu">
<a class="dropdown-item" href="javascript:ajax('/golist/task_ae?id=<?=$one->id?>&udata[name]='+$('#val-<?=$one->id?>').val());">修改</a>
<a class="dropdown-item" href="#">删除</a>
</div>
</div>
</div>
<?php }?>
</div>
<?php $this->stop();?>
