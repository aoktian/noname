<?php $this->layout('layout')?>

<?php $this->start('main')?>

<div class="row">
<div class="col-lg-10">
<button onclick="filter_caty(this, 0)" type="button" class="btn btn-primary">总览</button>
<button onclick="filter_caty(this, 1)" type="button" class="btn btn-dark">系统</button>
<button onclick="filter_caty(this, 2)" type="button" class="btn btn-dark">资源</button>
</div>

<div class="col-lg-2 right">
<button type="button" class="btn btn-success">完成</button>
<button type="button" class="btn btn-warning">未完</button>
<button type="button" class="btn btn-dark">不做</button>
</div>
</div>

<div class="progress mb-2 mt-2" style="height: 30px; font-size: 1rem">
<div id="progress-success" class="progress-bar bg-success" style="width: 50%"></div>
<div id="progress-warning" class="progress-bar bg-warning" style="width: 50%"></div>
</div>

<div class="row" id="tables">
<?php
$count = count($mods);
for ($i = 0; $i < 2; $i++) {
    ?>
<div class="col-lg-6">
<?php for ($key = $i; $key < $count; $key += 2) {
        $mod = $mods[$key];
        if (isset($gst[$mod->gid])) {
            $ts = $gst[$mod->gid];
        } else {
            $ts = [];
        }
        ?>


<table data-caty="<?=$mod->caty?>" class="table table-sm table-bordered table-striped tmiddle tcenter mfont">
<tbody>
<tr class="table-primary">
<th width="150">
<a name="mod-<?=$mod->id?>"></a>
<div class="btn-group" role="group">
<button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<?=$mod->name?>
</button>

<div class="dropdown-menu">
<a class="dropdown-item" href="javascript:ajax('/golist/one_add?modid=<?=$mod->id?>');">添加功能</a>
</div>
</div>

</th>
<?php foreach ($ts as $ts_one): ?>
<th><?=$tasks[$ts_one->tid]->name?></th>
<?php endforeach?>

</tr>

<?php if (isset($ones[$mod->id])) {
            foreach ($ones[$mod->id] as $one) {
                $onests_one = $onests[$one->id];
                ?>
<tr class="rlist">

<td>

<div class="btn-group" role="group">
<button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<?=$one->name?>
</button>

<div class="dropdown-menu">
<a class="dropdown-item" href="javascript:ajax('/golist/one_add?id=<?=$one->id?>');">修改</a>
<a onclick="up(this, '/golist/one_r?updown=up&id=<?=$one->id?>')" class="dropdown-item" href="javascript:void(0);">上移</a>
<a onclick="down(this, '/golist/one_r?updown=down&id=<?=$one->id?>')" class="dropdown-item" href="javascript:void(0);">下移</a>
<a class="dropdown-item" href="javascript:ajax('/golist/one_del/<?=$one->id?>');">删除</a>
</div>
</div>


</td>

<?php foreach ($ts as $ts_one) {
                    $onests_one_t = $onests_one[$ts_one->tid];
                    $style        = 'secondary';
                    if ($onests_one_t->isdo) {
                        if ($onests_one_t->status) {
                            $style = 'success';
                        } else {
                            $style = 'warning';
                        }
                    }
                    ?>
<td>
<div class="btn-group" role="group">
<button data-op="<?=$style?>" type="button" class="btn btn-<?=$style?> btn-sm dropdown-toggle nodown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
&nbsp;&nbsp;&nbsp;&nbsp;
</button>

<div class="dropdown-menu">
<a onclick="modify_status(this, <?=$onests_one_t->id?>, 0, 1)" class="dropdown-item" href="javascript:void(0);">不做</a>
<a onclick="modify_status(this, <?=$onests_one_t->id?>, 1, 1)" class="dropdown-item" href="javascript:void(0);">完成</a>
<a onclick="modify_status(this, <?=$onests_one_t->id?>, 1, 0)" class="dropdown-item" href="javascript:void(0);">未完</a>
</div>
</div>


</td>
<?php }?>

</tr>
<?php }}?>

</tbody>
</table>


<?php }?>
</div>
<?php }?>
</div>

<?php $this->stop();?>

<?php $this->start('style');?>
<style type="text/css">
.nodown:after {
    content:none;
}
</style>
<?php $this->stop();?>

<?php $this->start('script');?>
<script type="text/javascript">
function modify_status(dom, id, isdo, status) {
    ajax('/golist/one_status/' + id + '?udata[isdo]=' + isdo + '&udata[status]=' + status);

    var btn = $(dom).parent().parent().children().first()
    btn.removeClass('btn-default btn-success btn-warning')
    if (!isdo) {
        btn.addClass('btn-default')
    } else {
        if (status) {
            btn.addClass('btn-success')
        } else {
            btn.addClass('btn-warning')
        }
    }
}

function filter_caty(o, caty) {
    $(o).parent().children('button').removeClass('btn-primary').addClass('btn-dark')
    $(o).removeClass('btn-dark').addClass('btn-primary')
    if (caty == 0) {
        var els = $('#tables table')
    } else {
        $('#tables table').hide()
        var els = $('#tables [data-caty=' + caty + ']')
    }

    els.show()

    var cols = $('#tables .col-lg-6')
    for (var i = 0; i < 2; i++) {
        var col = $(cols[i])
        for (var key = i; key < els.length; key += 2) {
            col.append(els[key])
        }
    }

    js_p(els)
}

function js_p(tables) {
    var successss = tables.find('button[data-op=success]')
    var warningss = tables.find('button[data-op=warning]')
    var total = successss.length + warningss.length
    var p = Math.floor(successss.length / total * 100)
    $('#progress-success').css('width', p + '%').html(p + '% (' + successss.length + '/' + total + ')')
    $('#progress-warning').css('width', (100-p) + '%')
}

$(document).ready(function() {
    js_p($('#tables table'))
})
</script>
<?php $this->insert('updown');?>
<?php $this->stop();?>
