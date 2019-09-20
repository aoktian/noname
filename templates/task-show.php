<?php $this->layout('layout', ['title' => $task->title])?>
<?php $this->start('main')?>

<div class="card mb-3">
<div class="card-header">

<?php if ($task->related): ?>

<button data-toggle="collapse" href="#related-list" class="btn btn-secondary" type="button">
<i class="fas fa-angle-double-down"></i>
</button>

<span class="badge badge-warning">主任务</span>
<a href="/task/show/<?=$parent_task->id?>">#<?=$parent_task->id?> <?=$parent_task->title?></a>

<?php else: ?>
<div class="form-inline">

<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">关联任务：</span>
</div>
<input id="relatedid" class="form-control" type="text" placeholder="输入编号">
<div class="input-group-append">
<button onclick="addRelated()" class="btn btn-outline-secondary" type="button">确定</button>
</div>
</div>

<a href="/task/create/0/<?=$task->id?>" target="_blank" class="btn btn-success ml-2"><span class="glyphicon glyphicon-tasks"></span> 新增相关任务</a>

<button data-toggle="collapse" href="#related-list" class="btn btn-secondary ml-2" type="button">
<i class="fas fa-angle-double-down"></i>
</button>
</div>
<?php endif?>

</div>

<?php if ($tasks): ?>
<div class="card-body collapse" id="related-list">
<table class="table table-striped tmiddle tcenter" id="tasklist-table">

<thead>
<tr>
<th width="50">流程</th>
<th width="180"> 版本 </th>
<th> 状态 </th>
<th> 等级 </th>
<th> 类型 </th>
<th>剩余 </th>
<th class="left">标题 </th>
<th> 部门 </th>
<th> 负责人 </th>
<th> 验收人 </th>
<th>期限</th>
<th>修改时间</th>
</tr>
</thead>
<tbody id="tasklist">
<?php
$status = I\App::singleton()->getconfig('worktime', 'status');
foreach ($tasks as $one) {
    $tcolor = tr_color($one);
    ?>
<tr class="rlist <?=$tcolor?>">
<td>

<div class="btn-group" role="group">
<button type="button" class="btn <?=($one->status == 98 ? 'btn-success' : 'btn-secondary')?> dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<span class="oi oi-<?=($one->r > 0 ? 'arrow-thick-bottom' : 'minus')?>"></span>
</button>
<div class="dropdown-menu">
<a class="dropdown-item" href="javascript:ajax('/task/related?related=<?=$one->id?>&id=0');">取消关联</a>
<?php if ($one->r > 0): ?>
<a class="dropdown-item" href="javascript:up(this, '/task/rrr/<?=$one->id?>?updown=up');">上移</a>
<a class="dropdown-item" href="javascript:down(this, '/task/rrr/<?=$one->id?>?updown=down')"><a href="javascript:void(0);">下移</a>
<a class="dropdown-item" href="javascript:ajax('/task/no_r/<?=$one->id?>');">取消流程</a>
<?php else: ?>
<a class="dropdown-item" href="javascript:ajax('/task/add_r/<?=$one->id?>');">加入流程</a>
<?php endif?>
</div>

</div>

</td>
<td><?=isset($tags[$one->tag]) ? $tags[$one->tag]->name : ''?></td>
<td><?=$status[$one->status]?></td>
<td><?=isset($prioritys[$one->priority]) ? $prioritys[$one->priority] : ''?></td>
<td><?=$catys[$one->caty]->name?></td>

<td><?=getlast($one)?></td>

<td class="left"><a class="<?=$tcolor?>" href="/task/show/<?=$one->id?>" target="_blank">#<?=$one->id?> <?=cutstr($one->title, 80)?></a></td>
<td><?=$departments[$one->department]->name?></td>
<td><?=$users[$one->leader]->name?></td>
<td><?=isset($users[$one->tester]) ? $users[$one->tester]->name : '-'?></td>
<td><?=date('Y-m-d H:i:s', $one->deadline)?></td>
<td><?=$one->updated_at?></td>
</tr>
<?php }?>
</tbody>

</table>
</div>

<?php if (!$task->related): ?>
<div class="card-footer">
<div id="taskfilter">
<input type="hidden" itag="val" name="perpage" value="100" >
<input type="hidden" itag="val" name="search[related]" value="<?=$task->id?>" >
</div>
<input type="hidden" id="stitle">
<?php $this->insert('task-changemore', ['show_clear_related' => true])?>
</div>
<?php endif?>

<?php endif?>

</div>

<div class="card mb-3">
<div class="card-header" id="title">
#<?=$task->id?> <?=$task->title?>
</div>

<div class="card-body">
<p>
作者：<?=$users[$task->author]->name?> (<?=$task->created_at?>)
&nbsp;&nbsp;&nbsp;&nbsp;
修改：<?=$users[$task->changer]->name?> (<?=$task->updated_at?>)
</p>

<hr />

<div id="taskinfo">

<div class="form-row mb-2"><div class="form-inline">

<div class="input-group">
<select class="form-control" onchange="onChangePro(this.value, '#update-tag');">
<?php $this->insert('selection-users', ['data' => $pros, 'slt' => $task->pro])?>
</select>
</div>

<div class="input-group ml-2">
<select itag="val" name="row[tag]" class="form-control" id="update-tag">
<?php foreach ($tags as $tag): ?>
<?php if ($tag->pro == $task->pro): ?>
<option value="<?=$tag->id?>" <?=$tag->id == $task->tag ? 'selected' : ''?>><?=$tag->name?></option>
<?php endif?>
<?php endforeach?>
</select>
</div>

<div class="input-group ml-2">
<input style="width:500px;" itag="val" id="task-title" name="row[title]" type="text" class="form-control" value="<?=str_replace(array('\'', '"'), array('&apos;', '&quot;'), $task->title)?>">
</div>

<div class="input-group ml-2">
<select id="caty" itag="val" name="row[caty]" class="form-control">
<?php $this->insert('selection-users', ['data' => $catys, 'slt' => $task->caty])?>
</select>
</div>

<div class="input-group ml-2">
<select itag="val" name="row[priority]" class="form-control">
<?php
$prioritys = I\App::singleton()->getconfig('worktime', 'priority');
$this->insert('selection', ['data' => $prioritys, 'slt' => $task->priority])?>
</select>
</div>

<div class="input-group ml-2">
<select itag="val" name="row[status]" class="form-control">
<?php
$statuss = I\App::singleton()->getconfig('worktime', 'status');
$this->insert('selection', ['data' => $statuss, 'slt' => $task->status])?>
</select>
</div>

<div class="input-group ml-2">
<select itag="val" name="row[isonline]" class="form-control">
<?php $this->insert('selection', ['data' => [0 => '否', 1 => '是'], 'slt' => $task->isonline])?>
</select>
</div>

</div></div>

<div class="form-row"><div class="form-inline">

<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">部门</span>
</div>
<select class="form-control" onchange="onChangeDepartment( this.value, '#update-leader' )">
<?php $this->insert('selection-users', ['data' => $departments, 'slt' => $task->department])?>
</select>
</div>

<div class="input-group ml-2">
<div class="input-group-prepend">
<span class="input-group-text">负责人</span>
</div>
<select itag="val" name="row[leader]" class="form-control" id="update-leader">
<?php foreach ($users as $user): ?>
<?php if ($user->department == $task->department): ?>
<option value="<?=$user->id?>" <?=$user->id == $task->leader ? 'selected' : ''?>><?=$user->name?></option>
<?php endif?>
<?php endforeach?>
</select>
</div>

<div class="input-group ml-2">
<div class="input-group-prepend">
<span class="input-group-text">验收部门</span>
</div>
<select class="form-control" onchange="onChangeDepartment( this.value, '#update-tester' )">
<?php
$tester = $users[$task->tester];
$this->insert('selection-users', ['data' => $departments, 'slt' => $tester->department]);
?>
</select>
</div>

<div class="input-group ml-2">
<div class="input-group-prepend">
<span class="input-group-text">验收人</span>
</div>
<select itag="val" name="row[tester]" class="form-control" id="update-tester">
<?php foreach ($users as $user): ?>
<?php if ($user->department == $tester->department): ?>
<option value="<?=$user->id?>" <?=$user->id == $task->tester ? 'selected' : ''?>><?=$user->name?></option>
<?php endif?>
<?php endforeach?>
</select>
</div>

<div class="input-group ml-2">
<div class="input-group-prepend">
<span class="input-group-text">限期</span>
</div>
<input readonly onclick="showcalendar(event, this, true)" itag="val" name="row[deadline]" type="text" class="form-control" value="<?=date('Y-m-d H:i:s', $task->deadline)?>">
</div>


<button type="button" onclick="updateTaskOnchange(<?=$task->id?>);" class="btn btn-primary ml-2"><span class="glyphicon glyphicon-wrench"></span> 修改属性</button>


</div></div>

</div>

</div><!-- /.panel-body -->
</div>


<div class="card mb-3">
<div class="card-header">
<a href="/task/create/<?=$task->id?>" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> 编辑内容</a>
</div>
<div class="card-body">
<div id="taskcontent"> <?=$task->content?> </div>
</div><!-- /.card-body -->
<div class="card-footer">
<a href="/task/create/<?=$task->id?>" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> 编辑内容</a>
</div>
</div>

<?php $this->insert('task-feedback')?>


<?php if ($logs): ?>
<div class="line"></div>
<hr/>
<table class="table table-striped tcenter">
<thead>
<tr>
<th width="50">
</th>
<th width="100">#</th>
<th>时间</th>
<th>操作</th>
<th>状态</th>
<th>负责人</th>
<th>项目</th>
<th>版本</th>
<th>类型</th>
<th class="left">标题</th>
<th>内容</th>
<th>部门</th>
<th>验收人</th>
<th>等级</th>
<th>期限</th>
</tr>
</thead>
<tbody id="tasklogs">
<?php foreach ($logs as $log): ?>
<tr>
<td>
    <input <?=(!$log->content ? 'disabled' : '')?> onclick="checklog( this, 'task', 'taskcontent');" type="checkbox" value="<?=$log->id?>">
</td>
<td> <?=$log->id?> </td>
<td> <?=$log->created_at?> </td>
<td> <?=$log->changer?> </td>
<td> <?=$log->status?> </td>
<td> <?=$log->leader?> </td>
<td> <?=$log->pro?> </td>
<td> <?=$log->tag?> </td>
<td> <?=$log->caty?> </td>
<td class="left"> <?=$log->title?> </td>
<td>
<?php if ($log->content): ?>
<a href="javascript:diff( 'task', <?=$log->id?>, <?=$task->id?>, 'taskcontent' );" class="" target="_blank">和当前对比</a>
<?php endif?></td>
<td> <?=$log->department?> </td>
<td> <?=$log->tester?> </td>
<td> <?=$log->priority?> </td>
<td> <?=$log->deadline ? date('Y-m-d H:i:s', $log->deadline) : ''?> </td>
</tr>
<?php endforeach?>
</tbody>
</table>
<?php endif?>


<?php $this->end()?>


<?php $this->start('script')?>
<?php $this->insert('updown');?>
<script type="text/javascript">
function addRelated() {
    var related = $("#relatedid").val();

    ajax('/task/related', "id=<?=$task->id?>&related=" + related)
    $("#relatedid").val("");
}


$(document).ready(function( ) {
  initEditor( "summernote" );
});
</script>
<?php $this->end()?>

