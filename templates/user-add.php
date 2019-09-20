<div class="modal-dialog modal-lg" role="document"> <div class="modal-content">
<form method="POST" class="form-horizontal" action="/user/add">

<div class="modal-header">
<h3 class="modal-title">修改信息</h3>
<button type="button" class="btn btn-warning" data-dismiss="modal">关闭</button>
</div>

<div class="modal-body" id="golist-groupslt">


<input type="hidden" name="id" value="<?=$user->id?>">

<div class="input-group mb-3">
<div class="input-group-prepend">
<span class="input-group-text">姓名</span>
</div>
<input name="udata[name]" type="text" class="form-control" value="<?=$user->name?>">
</div>

<div class="input-group mb-3">
<div class="input-group-prepend">
<span class="input-group-text">EMAIL</span>
</div>
<input value="<?=$user->email?>" name="udata[email]" value="" type="email" class="form-control">
</div>

<div class="input-group mb-3">
<div class="input-group-prepend">
<span class="input-group-text">部门</span>
</div>
<select name="udata[department]" class="form-control">
<?php $this->insert('selection-users', ['data' => $departments, 'slt' => $user->department])?>
</select>
</div>

<div class="input-group mb-3">
<div class="input-group-prepend">
<span class="input-group-text">密码</span>
</div>
<input name="udata[password]" type="text" class="form-control">
</div>


<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal"> 取 消 </button>
<button type="submit" class="btn btn-primary"> 提 交 </button>
</div>

</form>
</div>
