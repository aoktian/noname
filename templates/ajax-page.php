<?php
if (isset($totalnum) && $totalnum > $perpage) {
    $page   = 10;
    $offset = 2;

    $pages = @ceil($totalnum / $perpage);

    if ($page > $pages) {
        $from = 1;
        $to   = $pages;
    } else {
        $from = $curpage - $offset;
        $to   = $from + $page - 1;
        if ($from < 1) {
            $to   = $curpage + 1 - $from;
            $from = 1;
            if ($to - $from < $page) {
                $to = $page;
            }
        } elseif ($to > $pages) {
            $from = $pages - $page + 1;
            $to   = $pages;
        }
    }
    ?>
<nav>
  <ul class="pagination">
<?php if ($curpage - $offset > 1 && $pages > $page): ?>
<li class="page-item"><a class="page-link" href="javascript:topage(1);">首页</a></li>
<?php endif?>
<?php if ($curpage > 1): ?>
<li class="page-item"><a class="page-link" href="javascript:topage(<?=$curpage - 1?>);">&laquo;</a></li>
<?php endif?>
<?php for ($i = $from; $i <= $to; $i++) {?>
    <?php if ($i == $curpage): ?>
<li class="page-item active"><a class="page-link" href="javascript:;"><?=$i?> <span class="sr-only">(current)</span></a></li>
    <?php else: ?>
<li class="page-item"><a class="page-link" href="javascript:topage(<?=$i?>);"><?=$i?></a></li>
    <?php endif?>
<?php }?>

<?php if ($to < $pages): ?>
<li class="page-item"><a class="page-link" href="javascript:topage(<?=$pages?>);">尾页</a></li>
<?php endif?>
<?php if ($pages > 1): ?>
<li class="page-item disabled"><em class="page-link"><?=$totalnum?></em></li>
<?php endif?>

</ul></nav>
<?php
}
?>
