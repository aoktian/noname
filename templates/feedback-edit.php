<?php $this->layout('layout')?>
<?php $this->start('main')?>

<form method="POST" action="/feedback/store" onsubmit="return oncommit_feedback();">
<input type="hidden" name="id" value="<?=$feedback->id?>">
<input type="hidden" name="row[pid]" value="<?=$feedback->pid?>">
<input type="hidden" id="feedbackContent" name="row[message]">

<div class="row">
<div class="col-lg-12">
<div class="form-group">
<div id="summernote" height="500"><?=$feedback->message?></div>
</div>
</div>
</div>

<div class="row">
<div class="col-sm-4">
<button type="submit" class="btn btn-danger btn-lg btn-block"> 提 交 </button>
</div>
</div>

</form>

<?php $this->end()?>
<?php $this->start('script')?>

<script type="text/javascript">
$(document).ready(function( ) {
    initEditor( "summernote" );
});

function oncommit_feedback( ) {
    var c = $('#summernote').summernote( 'code' );

    if (c.indexOf("data:image/png;base64") >= 0) {
        alert('不正确的图片格式，不要从word、有道等软件中直接粘贴过来，建议使用ctl+shift+v');
        return false;
    }

    if (c.indexOf('yne-bulb-block') >= 0) {
        alert('不要从word、有道等软件中直接粘贴过来，使用ctl+shift+v')
        return false;
    }

    $('#feedbackContent').val( c );
    return true;
}
</script>
<?php $this->end()?>
