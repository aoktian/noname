<div class="modal-dialog modal-lg" role="document"> <div class="modal-content">
<div class="modal-header">
<h3 class="modal-title">修改模块</h3>
<button type="button" class="btn btn-warning" data-dismiss="modal">关闭</button>
</div>

<div class="modal-body" id="mod_modify">

<div class="form-row">
<input itag="forme" type="hidden" name="id" value="<?=$one->id?>">

<div class="input-group mb-3">
<div class="input-group-prepend">
<span class="input-group-text">模块名字</span>
</div>
<input itag="forme" name="udata[name]" type="text" value="<?=$one->name?>" class="form-control" >
</div>

<div class="input-group mb-3">
<div class="input-group-prepend">
<span class="input-group-text">类型</span>
</div>
<select itag="forme" name="udata[caty]" class="form-control">
<option value="1" <?=$one->caty == 1 ? 'selected' : ''?>>系统</option>
<option value="2" <?=$one->caty == 2 ? 'selected' : ''?>>资源</option>
</select>
</div>

<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">任务组</span>
</div>
<select itag="forme" name="udata[gid]" class="form-control">
<?php foreach ($g as $k => $v): ?>
<option value="<?=$v->id?>" <?=$v->id == $one->gid ? 'selected' : ''?>><?=$v->name?></option>
<?php endforeach?>
</select>
</div>

</div>

</div>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal"> 取 消 </button>
<button type="button" class="btn btn-primary" onclick="submitform('/golist/mod_ae', 'mod_modify')"> 提 交 </button>
</div>

</div> </div>
