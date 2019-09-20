<script type="text/javascript">

function up(dom, url) {
    var onthis = $(dom).parents('tr:first')
    var getup = onthis.prev();
    if (!getup.hasClass('rlist')) {
        return alert('已经是顶级元素')
    }
    $(onthis).after(getup)
    ajax(url)
}

function down(dom, url) {
    var onthis = $(dom).parents('tr:first')
    var getdown = onthis.next();
    if (!getdown.hasClass('rlist')) {
        return alert('已经是底部元素')
    }
    $(getdown).after(onthis)
    ajax(url)
}

</script>
