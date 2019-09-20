<?php $this->layout('layout')?>

<?php $this->start('main')?>

<div class="form-inline mb-3" id="groupadd">

<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">名字</span>
</div>
<input itag="forme" name="udata[name]" type="text" value="" class="form-control" >
<div class="input-group-append">
<button onclick="submitform('/golist/groupadd', 'groupadd')" class="btn btn-outline-secondary" type="button">添加</button>
</div>
</div>

</div>


<div class="row">

<?php foreach ($list as $k => $one) {

    ?>
<div class="col-lg-3">
<table class="table table-striped tmiddle">
<tr>
<th><?=$one->name?></th>
<th>

<div class="btn-group" role="group">
<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
操作
</button>

<div class="dropdown-menu">
<a class="dropdown-item" href="javascript:ajax('/golist/groupslt/<?=$one->id?>');">添加</a>
<a class="dropdown-item" href="javascript:void(0);">删除</a>
</div>
</div>

</th>
</tr>
<?php if (isset($gs[$one->id])) {foreach ($gs[$one->id] as $value) {?>
<tr class="rlist">
<td><?=$tasks[$value->tid]->name?></td>
<td>
<div class="btn-group" role="group">
<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
操作
</button>

<div class="dropdown-menu">
<a onclick="up(this, '/golist/group_r?id=<?=$value->id?>&updown=up');" class="dropdown-item" href="javascript:void(0);">上移</a>
<a onclick="down(this, '/golist/group_r?id=<?=$value->id?>&updown=down')" class="dropdown-item" href="javascript:void(0);">下移</a>
</div>
</div>

</td>
</tr>
<?php }}?>

</table>
</div>
<?php }?>

</div>
<?php $this->stop();?>

<?php $this->start('script')?>
<script type="text/javascript">
function addslted(id) {
    var els = $('#golist-groupslt .btn-success')
    var data = 'id=' + id
    data += '&name=' + $('#modify-group-name').val()
    for (var i = 0; i < els.length; i++) {
        var tid = $(els[i]).attr('data-id')
        data += '&ids['+tid+']=' + tid
    }
    ajax('/golist/groupslted', data)
}
</script>
<?php $this->insert('updown');?>
<?php $this->stop()?>
