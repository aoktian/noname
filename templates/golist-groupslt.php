<div class="modal-dialog modal-lg" role="document"> <div class="modal-content">
<div class="modal-header">
<h3 class="modal-title">选择组内任务</h3>
<button type="button" class="btn btn-warning" data-dismiss="modal">关闭</button>
</div>

<div class="modal-body" id="golist-groupslt">

<div class="input-group mb-3">
<div class="input-group-prepend">
<span class="input-group-text">名字</span>
</div>
<input id="modify-group-name" type="text" value="<?=$group->name?>" class="form-control" >
</div>

<?php foreach ($list as $one) {?>
<button data-id="<?=$one->id?>" class="btn <?=(isset($gs[$one->id]) ? 'btn-success' : 'btn-default')?> mb-3" onclick="$(this).toggleClass('btn-success')"><?=$one->name?></button>
<?php }?>

</div>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal"> 取 消 </button>
<button type="button" class="btn btn-primary" onclick="addslted(<?=$id?>);"> 提 交 </button>
</div>

</div> </div>
