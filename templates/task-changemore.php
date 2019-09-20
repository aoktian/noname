<div class="form-inline mb-2" id="changemoreform">
<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">状态</span>
</div>
<select itag="val" name="changeto[status]" class="form-control">
<option value="0">不修改</option>
<?php
$status = I\App::singleton()->getconfig('worktime', 'status');
$this->insert('selection', ['data' => $status, 'slt' => 0])?>
</select>
</div>

<div class="input-group ml-2">
<div class="input-group-prepend">
<span class="input-group-text">优先级</span>
</div>
<select itag="val" name="changeto[priority]" class="form-control">
<option value="0">不修改</option>
<?php $this->insert('selection', ['data' => $prioritys, 'slt' => 0])?>
</select>
</div>

<div class="input-group ml-2">
<div class="input-group-prepend">
<span class="input-group-text">部门</span>
</div>
<select class="form-control" onchange="onChangeDepartment( this.value, '#changemore-leader', '不修改' )">
<option value="0">不修改</option>
<?php $this->insert('selection-users', ['data' => $departments, 'slt' => 0])?>
</select>
</div>

<div class="input-group ml-2">
<div class="input-group-prepend">
<span class="input-group-text">执行</span>
</div>
<select itag="val" name="changeto[leader]" class="form-control" id="changemore-leader">
<option value="0">不修改</option>
<?php $this->insert('selection-users', ['data' => $users, 'slt' => 0])?>
</select>
</div>

<div class="input-group ml-2">
<div class="input-group-prepend">
<span class="input-group-text">验收</span>
</div>
<select itag="val" name="changeto[tester]" class="form-control">
<option value="0">不修改</option>
<?php $this->insert('selection-users', ['data' => $users, 'slt' => 0])?>
</select>
</div>

<div class="input-group ml-2">
<div class="input-group-prepend">
<span class="input-group-text">项目</span>
</div>
<select class="form-control" onchange="onChangePro(this.value, '#changemore-tag', '不修改');">
<option value="0">不修改</option>
<?php $this->insert('selection-users', ['data' => $pros, 'slt' => 0])?>
</select>
</div>

<div class="input-group ml-2">
<div class="input-group-prepend">
<span class="input-group-text">版本</span>
</div>
<select itag="val" name="changeto[tag]" class="form-control" id="changemore-tag">
<option value="0">不修改</option>
</select>
</div>

<button type="button" onclick="changeMore( );" class="btn btn-primary ml-2">批量修改</button>

</div>
