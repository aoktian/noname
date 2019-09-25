<?php $this->layout('layout')?>

<?php $this->start('main')?>

<div class="form-inline mb-2" id="taskfilter">
<input type="hidden" itag="val" name="search[author]" value="<?=(isset($options['author']) ? $options['author'] : 0)?>" >

<div class="input-group">
<div class="input-group-prepend">
<span class="input-group-text">项目</span>
</div>
<select onchange="onChangePro( this.value, '#filterTags', '版本' );topage( 1 );" itag="val" name="search[pro]" class="form-control">
<option value="0">项目</option>
<?php $this->insert('selection-users', ['data' => $pros, 'slt' => isset($options['pro']) ? $options['pro'] : 0])?>
</select>
</div>

<div class="input-group ml-2">
<select onchange="topage( 1 );" itag="val" name="search[tag]" class="form-control" id="filterTags">
<option value="0">版本</option>
<?php $this->insert('selection-users', ['data' => $tags, 'slt' => isset($options['tag']) ? $options['tag'] : 0])?>
</select>
</div>

<div class="input-group ml-2">
<select onchange="topage( 1 );" itag="val" name="search[caty]" class="form-control">
<option value="0">类型</option>
<?php $this->insert('selection-users', ['data' => $catys, 'slt' => isset($options['caty']) ? $options['caty'] : 0])?>
</select>
</div>

<div class="input-group ml-2">
<select onchange="topage( 1 );" itag="val" name="search[status]" class="form-control">
<option value="0">状态</option>
<?php $this->insert('selection', ['data' => $status, 'slt' => isset($options['status']) ? $options['status'] : 0])?>
</select>
</div>

<div class="input-group ml-2">
<select onchange="topage( 1 );" itag="val" name="search[priority]" class="form-control">
<option value="0">优先级</option>
<?php $this->insert('selection', ['data' => $prioritys, 'slt' => isset($options['priority']) ? $options['priority'] : 0])?>
</select>
</div>

<div class="input-group ml-2">
<div class="input-group-prepend">
<span class="input-group-text">负责人</span>
</div>
<select onchange="onChangeDepartment(this.value, '#filterLeaders', '负责人');topage( 1 );" itag="val" name="search[department]" class="form-control">
<option value="0">部门</option>
<?php $this->insert('selection-users', ['data' => $departments, 'slt' => isset($options['departments']) ? $options['departments'] : 0])?>
</select>
<select onchange="topage( 1 );" itag="val" name="search[leader]" class="form-control" id="filterLeaders">
<option value="0">负责人</option>
<?php $this->insert('selection-users', ['data' => $users, 'slt' => isset($options['leader']) ? $options['leader'] : 0])?>
</select>
</div>

<div class="input-group ml-2">
<div class="input-group-prepend">
<span class="input-group-text">验收人</span>
</div>
<select onchange="onChangeDepartment(this.value, '#filter-tester', '验收人');" itag="val" class="form-control">
<option value="0">验收部门</option>
<?php $this->insert('selection-users', ['data' => $departments, 'slt' => 0])?>
</select>
<select onchange="topage( 1 );" itag="val" name="search[tester]" class="form-control" id="filter-tester">
<option value="0">验收人</option>
<?php $this->insert('selection-users', ['data' => $users, 'slt' => isset($options['tester']) ? $options['tester'] : 0])?>
</select>
</div>

<div class="input-group ml-2">
<select onchange="topage( 1 );" itag="val" name="orderby" class="form-control">
<option value="">排序</option>
<?php $this->insert('selection', ['data' => ['updated_at desc' => '修改时间', 'deadline' => '限期时间', 'created_at' => '创建时间'], 'slt' => $orderby])?>
</select>
</div>

<div class="input-group ml-2">
<select onchange="topage( 1 );" itag="val" name="ismain" class="form-control">
<option value="0">类型</option>
<?php $this->insert('selection', ['data' => [0 => '全部', 1 => '主任务'], 'slt' => $ismain])?>
</select>
</div>

<button onclick="topage( 1 );" class="btn btn-dark ml-2"><i class="fas fa-retweet"></i></button>

<a href="/task/create" target="_blank" class="btn btn-primary ml-2" type="button">发布任务</a>
</div>

<table class="table table-bordered table-striped tmiddle tcenter">
<?php $this->insert('task-list-table')?>
</table>

<?php $this->insert('task-changemore')?>

<?php $this->end()?>

<?php $this->start('script')?>

<script type="text/javascript">
var users = <?=json_encode($users)?>;
var tags = <?=json_encode($tags)?>;

function topage( _page ) {
    if (_page) {
      page = _page;
    }
    var s = "page=" + page + "&" + get_form_values( "taskfilter" );
    s += "&title=" + $("#stitle").val();
    console.log(s);
    getlist( s );
}

function getlist( s ) {
  $.ajax({
      data: s,
      type: "GET",
      url: '/task/index',
      cache: false,
      success: function( res ) {
          $("#tasklist").html( res );
      }
  });
}

setInterval( "topage( );", 1000 * 60 * 5 );

function checkall( id, name, b ) {
    var els = $("#" + id + " :checkbox");
    for (var i = 0; i < els.length; i++) {
        var el = $(els[i]);
        if (name == el.prop("name")) {
            if ("undefined" == typeof(b) ) {
                if (el.prop("checked")) {
                    el.prop("checked", false);
                } else {
                    el.prop("checked", true);
                }
            } else{
                el.prop("checked", b);
            }
        }
    }
}

</script>

<?php $this->end()?>
