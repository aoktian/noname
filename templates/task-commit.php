<?php $this->layout('layout')?>
<?php $this->start('main')?>

<?php
if (is_null($task)) {
    $task             = (object) array();
    $task->id         = 0;
    $task->title      = '没有标题的标题';
    $task->caty       = 0;
    $task->priority   = 0;
    $task->level      = 0;
    $task->department = $authed->department;
    $task->leader     = $authed->id;
    $task->pro        = 0;
    $task->tag        = 0;
    $task->tester     = $authed->id;
    $task->deadline   = 0;
    $task->content    = '';

    if ($related) {
        $task->title    = $related->title;
        $task->level    = $related->level;
        $task->pro      = $related->pro;
        $task->tag      = $related->tag;
        $task->priority = $related->priority;
    }
}
?>
<h1><?=($related ? sprintf('上级任务：#%s %s', $related->id, $related->title) : '提交新任务')?></h1>
<hr />

<form method="POST" action="/task/store" onsubmit="return oncommit( );">

<input id="task-id" type="hidden" name="id" value="<?=$task->id?>" />
<input type="hidden" id="taskContent" name="row[content]">
<input type="hidden" name="row[related]" value="<?=($related ? $related->id : 0)?>">

<div class="form-row mb-3">
<div class="form-inline">

<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">标题</span>
</div>
<input id="task-title" name="row[title]" type="text" style="width:380px" class="form-control" value="<?=str_replace(array('\'', '"'), array('&apos;', '&quot;'), $task->title)?>">

<select style="width: 150px;" class="form-control" onchange="onChangePro(this.value, '#update-tag', '版本');">
<option value="0">选择项目</option>
<?php $this->insert('selection-users', ['data' => $pros, 'slt' => $task->pro])?>
</select>
<select style="width: 150px;" name="row[tag]" class="form-control" id="update-tag">
<option value="0">未选项目</option>
<?php $this->insert('selection-users', ['data' => $tags, 'slt' => $task->tag])?>
</select>

<select style="width: 100px;" name="row[priority]" class="form-control">
<?php
$prioritys = I\App::singleton()->getconfig('worktime', 'priority');
$this->insert('selection', ['data' => $prioritys, 'slt' => $task->priority])?>
</select>
<input name="row[level]" type="number" style="width:100px" class="form-control" value="<?=$task->level?>">

<select style="width: 100px;" id="caty" name="row[caty]" class="form-control">
<?php $this->insert('selection-users', ['data' => $catys, 'slt' => $task->caty])?>
</select>

</div>


<div class="input-group ml-2">
<div class="input-group-prepend">
<span class="input-group-text">线上</span>
</div>
<select name="row[isonline]" class="form-control">
<?php $this->insert('selection', ['data' => [0 => '否', 1 => '是'], 'slt' => 0])?>
</select>
</div>

</div>
</div>

<div class="form-row mb-3">
<div class="form-inline">

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
<select name="row[leader]" class="form-control" id="update-leader">
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
<span class="input-group-text">验收</span>
</div>
<select name="row[tester]" class="form-control" id="update-tester">
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
<input onclick="showcalendar(event, this, true)" name="row[deadline]" type="text" class="form-control" value="<?=date('Y-m-d H:i:s', $task->deadline ? $task->deadline : time() + 86400 * 7)?>">
</div>

</div>
</div>

<textarea id="summernote" height="500"><?=$task->content?></textarea>

<div class="mb-3"></div>
<button type="submit" class="btn btn-primary btn-lg btn-block"> 提 交 </button>
</form>

<?php $this->end()?>


<?php $this->start('script')?>
<script type="text/javascript">
var users = <?=json_encode($users)?>;
var tags = <?=json_encode($tags)?>;

$(document).ready(function( ) {
  initEditor( "summernote" );
});

function oncommit( ) {

  if ($("#update-leader").val() <= 0) {
    alert('没有选择部门或者负责人');
    return false;
  }

  if ($("#update-tag").val() <= 0) {
    alert('没有选择项目或者版本');
    return false;
  }

  var c = $('#summernote').summernote( 'code' );
  if (c.indexOf("data:image/png;base64") > 0) {
    alert('不正确的图片格式，不要从word、有道等软件中直接粘贴过来，建议使用ctl+shift+v');
    return false;
  }
  $('#taskContent').val( c );
  return true;
}

var markupStr = '<p>问题描述：</p>'
markupStr += '<p>重现环境：</p>'
markupStr += '<p>版本信息：</p>'
markupStr += '<p>重现步骤：</p>'
markupStr += '<p>期望结果：</p>'
markupStr += '<p>附件信息：</p>'
$(document).ready(function() {
    if ($('#task-id').val() > 0) {
        return
    }

    $('#summernote').summernote('code', markupStr);
})
</script>
<?php $this->end()?>
