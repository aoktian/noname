<?php foreach ($tags as $tag): ?>
<tr id="tag-<?=$tag->id?>">
<td><?=$tag->id?></td>
<td><input itag="val" type="text" class="form-control" name="row[name]" value="<?=$tag->name?>"></td>
<td><select itag="val" name="row[pro]" class="form-control">
<?php $this->insert('selection-users', ['data' => $pros, 'slt' => $tag->pro])?>
</select></td>
<td><input itag="val" type="text" class="form-control" name="row[t_start]" value="<?=$tag->t_start?>" onclick="showcalendar(event, this)"></td>
<td><input itag="val" type="text" class="form-control" name="row[t_end]" value="<?=$tag->t_end?>" onclick="showcalendar(event, this)"></td>
<td>
<button onclick="onupdate(<?=$tag->id?>);" class="btn btn-primary">修改</button>
<a href="/tag/vvv?tag=<?=$tag->id?>" class="btn btn-secondary">查看统计</a>
<a href="/tag/destroy/<?=$tag->id?>?page=<?=$curpage?>&pro=<?=$pro?>" class="btn btn-danger">删除</a>
</td>
</tr>
<?php endforeach?>
<tr><td colspan="6" class="text-left">
<?php $this->insert('ajax-page')?>
</td></tr>
