<?php $this->layout('layout')?>
<?php $this->start('main')?>

<form>
<textarea id="content" class="form-control" rows="3"></textarea>

<br />
<button type="button" onclick="wahaha()" class="btn btn-danger btn-lg btn-block"> 提 交 </button>
</form>

<div class="line"></div>
<table class="table table-bordered table-striped tmiddle tcenter">
<thead>
<tr>
<th>
<input type="checkbox"></th>
<th> 项目 </th>
<th> 版本 </th>
<th> 状态 </th>
<th> 等级 </th>
<th> 类型 </th>
<th class="left">标题 </th>
<th> 关联 </th>
<th> 部门 </th>
<th> 负责人 </th>
<th> 验收人 </th>
<th>期限</th>
<th>修改时间</th>
</tr>
</thead>
<tbody id="tasklist">
</tbody>

</table>

<?php $this->end()?>


<?php $this->start('script')?>
<script type="text/javascript">
function wahaha( ) {
    $.ajax({
        data: {c: $('#content').val()},
        type: "POST",
        url: '/task/sql',
        dataType: "text",
        cache: false,
        success: function( res ) {
            $("#tasklist").html( res );
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#msg-body').html(XMLHttpRequest.responseText);
            $('#msg').modal('show');
        },
        complete: function (jqXHR, textStatus) {
        }
    });
}
</script>
<?php $this->end()?>

