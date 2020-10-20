<div class="form-inline mb-2" id="changemoreform">

<div class="input-group">
<select class="form-control" onchange="onChangePro(this.value, '#changemore-tag', '不修改');">
<option value="0">项目</option>
<?php $this->insert('selection-users', ['data' => $pros, 'slt' => 0])?>
</select>
</div>

<div class="input-group ml-2">
<select itag="val" name="changeto[tag]" class="form-control" id="changemore-tag">
<option value="0">版本</option>
</select>
</div>

<div class="input-group ml-2">
<select itag="val" name="changeto[caty]" class="form-control">
<option value="0">类型</option>
<?php
$this->insert('selection-users', ['data' => $catys, 'slt' => 0])?>
</select>
</div>

<div class="input-group ml-2">
<select itag="val" name="changeto[status]" class="form-control">
<option value="0">状态</option>
<?php
$status = I\App::singleton()->getconfig('worktime', 'status');
$this->insert('selection', ['data' => $status, 'slt' => 0])?>
</select>
</div>

<div class="input-group ml-2">
<select style="width: 80px;" itag="val" name="changeto[priority]" class="form-control">
<option value="0">优先级</option>
<?php $this->insert('selection', ['data' => $prioritys, 'slt' => 0])?>
</select>
<input itag="val" name="changeto[level]" type="number" class="form-control" style="width: 80px;">
</div>

<div class="input-group ml-2">
<select class="form-control" onchange="onChangeDepartment( this.value, '#changemore-leader', '不修改' )">
<option value="0">部门</option>
<?php $this->insert('selection-users', ['data' => $departments, 'slt' => 0])?>
</select>
</div>

<div class="input-group ml-2">
<select itag="val" name="changeto[leader]" class="form-control" id="changemore-leader">
<option value="0">执行</option>
<?php $this->insert('selection-users', ['data' => $users, 'slt' => 0])?>
</select>
</div>

<div class="input-group ml-2">
<select itag="val" name="changeto[tester]" class="form-control">
<option value="0">验收</option>
<?php $this->insert('selection-users', ['data' => $users, 'slt' => 0])?>
</select>
</div>


<div class="input-group ml-2">
<div class="input-group-prepend">
<span class="input-group-text">限期</span>
</div>
<input onclick="showcalendar(event, this, true)" id="change_more_deadline" type="text" class="form-control" value="">
</div>


<button type="button" onclick="changeMore( );" class="btn btn-primary ml-2">批量修改</button>

</div>
