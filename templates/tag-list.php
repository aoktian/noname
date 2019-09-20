<?php $this->layout('layout')?>
<?php $this->start('main')?>

<form method="POST" action="/tag/store">
<div class="form-inline mb-3">

<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">名称：</span>
</div>
<input type="text" class="form-control" name="row[name]" />
</div>

<div class="input-group ml-2">
<div class="input-group-prepend">
<span class="input-group-text">项目：</span>
</div>
<select name="row[pro]" class="form-control">
<?php $this->insert('selection-users', ['data' => $pros, 'slt' => $pro])?>
</select>
</div>

<div class="input-group ml-2">
<div class="input-group-prepend">
<span class="input-group-text">开始：</span>
</div>
<input name="row[t_start]" class="form-control" type="text" onclick="showcalendar(event, this)">
</div>

<div class="input-group ml-2">
<div class="input-group-prepend">
<span class="input-group-text">结束：</span>
</div>
<input name="row[t_end]" class="form-control" type="text" onclick="showcalendar(event, this)">
</div>

<button type="submit" class="btn btn-primary ml-2">添加</button>

</div>
</form>





<div class="form-inline mb-3">

<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">筛选条件：</span>
</div>
<select onchange="topage( 1 );" id="pro" class="form-control">
<option value="0">项目</option>
<?php $this->insert('selection-users', ['data' => $pros, 'slt' => $pro])?>
</select>
</div>

</div>

<table class="table table-bordered table-striped table-hover vertical-middle">
<thead>
<tr>
<th width="50">#id</th>
<th width="200"> 名称 </th>
<th width="200"> 项目 </th>
<th width="200"> 开始时间 </th>
<th width="200"> 结束时间 </th>
<th> 操作 </th>
</tr>
</thead>
<tbody id="tag-list">
<?php $this->insert('tag-list-content')?>
</tbody>
</table>


<?php $this->end()?>

<?php $this->start('script')?>

<script type="text/javascript">
function topage( page ) {
    var s = "page=" + page + "&pro=" + $( "#pro" ).val();
    $.ajax({
        data: s,
        type: "GET",
        url: '/tag/page',
        cache: false,
        success: function( res ) {
            $("#tag-list").html( res );
        }
    });
}
function onupdate( id ) {
    $.ajax({
        data: get_form_values( 'tag-' + id ),
        url:'/tag/store/' + id
  }).done(function() {alert('OK.')});
}
</script>
<?php $this->end()?>
