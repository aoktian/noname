<?php
$status = I\App::singleton()->getconfig('worktime', 'status');
foreach ($tasks as $task) {
    $tcolor = tr_color($task);
    ?>
<tr class="<?=$tcolor?>">
<td><input itag="val" name="ids[]" type="checkbox" value="<?=$task->id?>"></td>
<td><?=isset($tags[$task->tag]) ? $tags[$task->tag]->name : ''?></td>
<td><?=$status[$task->status]?></td>
<td><?=isset($prioritys[$task->priority]) ? $prioritys[$task->priority] : ''?></td>
<td><?=$catys[$task->caty]->name?></td>

<td><?=getlast($task)?></td>

<td class="left"><a class="<?=$tcolor?>" href="/task/show/<?=$task->id?>" target="_blank">#<?=$task->id?> <?=cutstr($task->title, 80)?></a></td>
<td><?=$departments[$task->department]->name?></td>
<td><?=$users[$task->leader]->name?></td>
<td><?=isset($users[$task->tester]) ? $users[$task->tester]->name : '-'?></td>
<td><?=date('m-d H:i', $task->deadline)?></td>
<td><?=substr($task->updated_at, 5, -3)?></td>

</tr>
<?php }?>
<tr><td colspan="12" class="left">
<?php $this->insert('ajax-page')?>
</td></tr>
