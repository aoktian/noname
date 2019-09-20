<div class="card mb-3">
<div class="card-header">
<a name="feedback.<?=$feedback->id?>">#<?=$feedback->id?></a>
&nbsp;&nbsp;&nbsp;&nbsp;
作者：<?=$users[$feedback->author]->name?> (<?=$feedback->created_at?>)
&nbsp;&nbsp;&nbsp;&nbsp;
修改：<?=$users[$feedback->changer]->name?> (<?=$feedback->updated_at?>)
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="/feedback/edit/<?=$feedback->id?>">重新编辑</a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="/feedback/show/<?=$feedback->id?>">修改日志</a>
    </div>
    <div class="card-body" id="feedback-<?=$feedback->id?>">
<?=$feedback->message;?>
    </div>
    <!-- /.panel-body -->
</div>
