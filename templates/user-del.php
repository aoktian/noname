<div class="modal-dialog" role="document"> <div class="modal-content">
<form method="POST" action="/user/del">
<div class="modal-header">
<h3 class="modal-title">删除角色 <?=$user->name?></h3>
<button type="button" class="btn btn-warning" data-dismiss="modal">关闭</button>
</div>

<div class="modal-body">
<input type="hidden" name="id" value="<?=$user->id?>">

<p>把他的任务转移给其他人，包括负责的任务和发布的任务</p>

<div class="form-group">
<label>部门：</label>
<select class="form-control form-control-lg" onchange="onChangeDepartment( this.value, '#slt-leaders', '选择' )">
<option value="0">选择部门</option>
<?php $this->insert('selection-users', ['data' => $departments, 'slt' => 0])?>
</select>
</div>

<div class="form-group">
<label>转到：</label>
<select class="form-control form-control-lg" name="toid"  id="slt-leaders">
<option value="0">选择部门</option>
</select>
</div>


</div>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal"> 取 消 </button>
<button type="submit" class="btn btn-primary"> 确 定 </button>
</div>
</form>
</div> </div>
