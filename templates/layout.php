<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
<?php if (isset($title)): ?>
<title><?=($title . ' - ')?><?=C\Config_App::name?></title>
<?php else: ?>
<title><?=C\Config_App::name?></title>
<?php endif?>

<!-- Bootstrap -->
<link rel="stylesheet" href="/bootstrap-4.3.1-dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link href="/assets/toast/jquery.toast.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

<link href="/open-iconic/css/open-iconic-bootstrap.css" rel="stylesheet">
<link href="/fontawesome-free-5.10.1-web/css/all.css" rel="stylesheet">

<link rel="stylesheet" href="/summernote-0.8.12-dist/summernote-bs4.css">
<link rel="stylesheet" href="/assets/calendar.css" />

<style type="text/css">
caption {
    text-align: center;
    font-weight: bold;
}

.dropdown-item:hover {background-color: #007bff;color: #fff}
.line {margin-bottom:10px;}
.tmiddle > tbody > tr > th, .tmiddle > tbody > tr > td {
vertical-align: middle;
}
.tcenter > tbody > tr > th, .tcenter > thead > tr > th, .tcenter > tbody > tr > td {
text-align: center;
}

.center {
text-align: center;
}
.right {
text-align: right;
}
.left {
text-align: left;
}

#main .center td, #main .center th {
text-align: center;
}
#main  td.left, #main  th.left {
text-align: left;
}
@keyframes changeshadow {
0%{ text-shadow: 0 0 5px orange}
50%{ text-shadow: 0 0 50px orange}
100%{ text-shadow: 0 0 5px orange}
}
.shan_bg{
color:orange;
font-weight:bold;
animation: changeshadow 1.5s  ease-in  infinite ;
}
.shan_bg a {color:orange}
.youxianji-ji {font-weight:bold;}
</style>
<?=$this->section('style')?>

</head>
<body>
<div id="append_parent"></div>

<div class="line"></div>

<?php if (isset($authed)): ?>

<div class="container-fluid">
<div class="row">
<div class="col-md-10">

<table>
<tr>
<td>

<?php
$navs = I\App::singleton()->getconfig('nav');
$curr = isset($currmenu) ? $currmenu : CONTROLLER;
$nav1 = '';
$nav2 = '';
foreach ($navs as $ctl => $name) {
    if ($ctl == $curr) {
        $nav1 = $name['name'];
    }
    ?>
<?php if (is_array($name)): ?>

<div class="btn-group" role="group">
<button type="button" class="btn <?=($ctl == $curr ? 'btn-primary' : 'btn-dark')?> btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<?=$name['name']?>
</button>
<div class="dropdown-menu">
<?php foreach ($name['children'] as $action => $cname) {
        if (PATH == $action) {
            $nav2 = $cname;
        }
        ?>
<a class="dropdown-item" href="<?=$action?>"><?=$cname?></a>
<?php }?>
</div>
</div>

<?php else: ?>
<a
href="/<?=$ctl?>/index"
class="btn btn-default<?=($ctl == CONTROLLER ? ' btn-success' : '')?>">
<?=$name?>
</a>
<?php endif?>

<?php }?>


</td>
<td>

<div class="form-inline">

<div class="input-group ml-2">
<input id="gid" type="text" class="form-control" placeholder="输入编号直接打开">
<div class="input-group-append">
<button onclick="window.open( '/task/show/' + $('#gid').val() );" class="btn btn-outline-secondary" type="button">Go!</button>
</div>
</div>

<div class="input-group ml-2">
<input id="stitle" type="text" class="form-control" placeholder="标题模糊查询">
<div class="input-group-append">
<button onclick="getlist( 'title=' + $('#stitle').val() );" class="btn btn-outline-secondary" type="button">Search</button>
</div>
</div>

</div>


</td>
</tr>
</table>

</div>



<div class="col-md-2" style="text-align: right;">
<strong style="height:48px; line-height: 48px;font-size: 25px;"><?=C\Config_App::name?></strong>
</div>

</div>
</div>

<hr>


<nav aria-label="breadcrumb" style="display: none;">
<ol class="breadcrumb">
<li class="breadcrumb-item" aria-current="page"><?=$nav1?></li>
<?php if ($nav2): ?>
<li class="breadcrumb-item active" aria-current="page"><?=$nav2?></li>
<?php endif?>
<?php if (isset($title)): ?>
<li class="breadcrumb-item active"><?=$title?></li>
<?php endif?>
</ol>
</nav>


<div class="container-fluid" id="main">
<?=$this->section('main')?>
</div>

<?php else: ?>
<div class="container" style="width:500px;padding-top: 100px;">
<?=$this->section('main')?>
</div>
<?php endif?>

<hr>
<p class="center">aoktian@foxmail.com</p>

<!-- Modal -->
<div class="modal" id="dialog" tabindex="-1" data-backdrop="static">
</div>

<script src="/bootstrap-4.3.1-dist/js/jquery-3.4.1.min.js"></script>
<script src="/bootstrap-4.3.1-dist/js/popper.min.js"></script>
<script src="/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>

<script src="/assets/toast/jquery.toast.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<script src="/summernote-0.8.12-dist/summernote-bs4.js"></script>
<script src="/assets/summernote/lang/summernote-zh-CN.js"></script>

<script src="/assets/calendar.js"></script>
<script src="/assets/worktime.js?t=20190807"></script>

<script type="text/javascript">
function go(url) {
    window.location.href = url
}
function ajax(url, param, callback) {
    $.ajax({
        data: param,
        type: "POST",
        url: url,
        dataType: "json",
        cache: false,
        success: function( r ) {
            console.log(r);
            if (r.redirect_url) {
              window.location.href = r.redirect_url
              return
            }

            if (r.assertToast) {
                return $.toast({
                    text: r.assertToast,
                    allowToastClose: false,
                    hideAfter: 10000,
                    position : 'mid-center'
                })
            }

            if (r.assertAlert) {
                return $.alert({
                    title: '提示信息',
                    content: r.assertAlert,
                });
            }

            if (r.assertDialog) {
                $('#dialog').html(r.assertDialog).modal('show')
                return
            }

            if (r.toast) {
                $.toast({
                    text: r.toast,
                    allowToastClose: false,
                    hideAfter: 10000,
                    position : 'mid-center'
                })
            }

            if (r.alert) {
                $.alert({
                    title: '提示信息',
                    content: r.alert,
                });
            }

            if (r.doms) {
                for (var domid in r.doms) {
                    $(domid).html( r.doms[domid] )
                }
            }

            if (r.dialog) {
                $('#dialog').html(r.dialog).modal('show')
            }

            if (r.script) {
                eval(r.script)
            }

            if (callback) {
                callback( r );
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#dialog').html(XMLHttpRequest.responseText).modal('show');
        },
        complete: function (jqXHR, textStatus) {
        }
    });
}

function hidedialog() {
    $('#dialog').modal('hide')
}
function submitform(path, id, forme ) {
    var param = "";

    if (!forme) {
        forme = 'forme';
    }

    var els = $("#" + id + " [itag='" + forme + "']");

    var dot = "";
    for (var i = 0; i < els.length; i++) {
        var el = $(els[i]);
        if ( el.prop('type') != "checkbox" || el.prop("checked")) {
            param += dot + el.prop("name") + "=" + encodeURIComponent(el.val());
            dot = "&";
        }
    }

    ajax(path, param)
}

function mainmenu(t, path, active) {
    var o = $(t)
    var els = o.parent().children()
    for (var i = 0; i < els.length; i++) {
        var el = $(els[i]);
        el.removeClass(active);
    }
    o.addClass(active)
    ajax(path)
}
</script>
<?=$this->section('script')?>

</body>
</html>
