<thead>
<tr>
<th width="20">
<input type="checkbox" onclick="checkall('tasklist', 'ids[]', $(this).prop('checked') );"></th>
<th> 版本 </th>
<th> 状态 </th>
<th> 等级 </th>
<th> 类型 </th>
<th>剩余 </th>
<th class="left">标题 </th>
<th> 部门 </th>
<th> 负责人 </th>
<th> 验收人 </th>
<th>期限</th>
<th>修改时间</th>
</tr>
</thead>
<tbody id="tasklist">
<?php $this->insert('task-list-content')?>
</tbody>
