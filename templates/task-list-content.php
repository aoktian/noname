<?php
$status = I\App::singleton()->getconfig('worktime', 'status');
foreach ($tasks as $task) {
    $tcolor = tr_color($task);
    ?>
<tr class="<?=$tcolor?>">
<td><input itag="val" name="ids[]" type="checkbox" value="<?=$task->id?>"></td>

<?php if (isset($related_op) || getgpc('is_related')) {?>
<td>

<div class="btn-group" role="group">
<button type="button" class="btn <?=($task->status == 98 ? 'btn-success' : 'btn-secondary')?> dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<span class="oi oi-<?=($task->r > 0 ? 'arrow-thick-bottom' : 'minus')?>"></span>
</button>
<div class="dropdown-menu">
<a class="dropdown-item" href="javascript:ajax('/task/related?related=<?=$task->id?>&id=0');">取消关联</a>
<?php if ($task->r > 0): ?>
<a class="dropdown-item" href="javascript:up(this, '/task/rrr/<?=$task->id?>?updown=up');">上移</a>
<a class="dropdown-item" href="javascript:down(this, '/task/rrr/<?=$task->id?>?updown=down')">下移</a>
<a class="dropdown-item" href="javascript:ajax('/task/no_r/<?=$task->id?>');">取消流程</a>
<?php else: ?>
<a class="dropdown-item" href="javascript:ajax('/task/add_r/<?=$task->id?>');">加入流程</a>
<?php endif?>
</div>
</div>

</td>
<?php }?>

<td><?=$tags[$task->tag]->name?></td>
<td><?=$status[$task->status]?></td>
<td><?=$prioritys[$task->priority]?>(<?=$task->level?>)</td>
<td><?=$catys[$task->caty]->name?></td>

<td><?=getlast($task)?></td>

<td class="left"><a class="<?=$tcolor?>" href="/task/show/<?=$task->id?>" target="_blank">#<?=$task->id?> <?=cutstr($task->title, 80)?></a></td>
<td><?=$departments[$task->department]->name?></td>
<td><?=$users[$task->leader]->name?></td>
<td><?=$users[$task->tester]->name?></td>
<td><?=date('m-d H:i', $task->deadline)?></td>
<td><?=substr($task->updated_at, 5, -3)?></td>

</tr>
<?php }?>
<tr><td colspan="12" class="left">
<?php $this->insert('ajax-page')?>
</td></tr>
