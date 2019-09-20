<div class="modal-dialog modal-lg" role="document"> <div class="modal-content">
<div class="modal-header">
<h3 class="modal-title">添加功能</h3>
<button type="button" class="btn btn-warning" data-dismiss="modal">关闭</button>
</div>

<div class="modal-body" id="one_add">


<div class="form-row">
<input itag="forme" type="hidden" name="id" value="<?=$one->id?>">


<div class="input-group mb-3">
<div class="input-group-prepend">
<span class="input-group-text">功能名称</span>
</div>
<input itag="forme" name="udata[name]" type="text" value="<?=$one->name?>" class="form-control" >
</div>

<div class="input-group mb-3">
<div class="input-group-prepend">
<span class="input-group-text">模块</span>
</div>
<select itag="forme" name="udata[modid]" class="form-control">
<?php foreach ($mods as $k => $v): ?>
<option value="<?=$v->id?>" <?=$v->id == $one->modid ? 'selected' : ''?>><?=$v->name?></option>
<?php endforeach?>
</select>
</div>


</div>

</div>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal"> 取 消 </button>
<button type="button" class="btn btn-primary" onclick="submitform('/golist/one_add', 'one_add')"> 提 交 </button>
</div>

</div> </div>
