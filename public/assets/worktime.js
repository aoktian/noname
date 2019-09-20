var page = 1;

function initEditor( id ) {
    var $summernote = $('#'+id);
    $summernote.summernote({
        toolbar : [
            ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
            ['font', ['color', 'fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['hr', 'link', 'table', 'picture']]
        ],
        height: $summernote.attr("height"),             // set minimum height of editor
        lang : "zh-CN",
        callbacks: {
            onImageUpload: function(files) {
                var data = new FormData();
                data.append("file", files[0]);
                $.ajax({
                    data: data,
                    type: "POST",
                    url: "/task/upload",
                    cache: false,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    success: function( res ) {
                        if (!res.path) {
                        console.log( res.err );
                        return;
                        }

                        console.log( res.path );
                        // var img = document.createElement("img");
                        // img.src = url;
                        // $summernote.summernote('insertNode', img);
                        $summernote.summernote('insertImage', res.path);
                    }
                });
            }
        }
    });
}

function get_form_values(id, forme ) {
    var rtn = "";

    if (!forme) {
        forme = 'val';
    }

    var els = $("#" + id + " [itag='" + forme + "']");

    var dot = "";
    for (var i = 0; i < els.length; i++) {
        var el = $(els[i]);
        if ( el.prop('type') != "checkbox" || el.prop("checked")) {
            rtn += dot + el.prop("name") + "=" + encodeURIComponent(el.val());
            dot = "&";
        }
    }
    return rtn;
}

String.prototype.format = function (args) {
    var newStr = this;
    for (var key in args) {
        newStr = newStr.replace('[[' + key + ']]', args[key]);
    }
    return newStr;
};

function getUsers( id ) {
    var options = "";
    for (var i in users) {
        if (users[i].department == id) {
            options += "<option value='" + users[i].id + "'>" + users[i].name + "</option>";
        }
    }
    return options;
}

function onChangeDepartment( id, tar, first ) {
    var options = "";
    if (first) {
        options += "<option value='0'>"+first+"</option>";
    }

    $(tar).html( options + getUsers( id ) );
}


function getTags( id ) {
    var options = "";
    for (var i in tags) {
        if (tags[i].pro == id) {
        options += "<option value='" + tags[i].id + "'>" + tags[i].name + "</option>";
        }
    }
    return options;
}
function onChangePro( id, tar, first ) {
    var options = "";
    if (first) {
        options += "<option value='0'>"+first+"</option>";
    }

    $(tar).html( options + getTags( id ) );
}

function updateTaskOnchange( id ) {
    if ($("#update-leader").val() <= 0 ) {
        alert("没有选择负责人。");
        return;
    }

    if ($("#update-tag").val() <= 0 ) {
        alert("没有选择版本。");
        return;
    }

    // console.log( dom.attr('name')+'='+dom.val()); return;
    var s = get_form_values( "taskinfo" );
    console.log(s);

    $("#title").html("#" + id + " " + $("#task-title").val());

    ajax('/task/store', s + "&id=" + id)
}

function commitFeedback( ) {
    var c = $('#summernote').summernote( 'code' );

    if (c.indexOf("data:image/png;base64") >= 0) {
        alert('不正确的图片格式，不要从word、有道等软件中直接粘贴过来，建议使用ctl+shift+v');
        return false;
    }

    if (c.indexOf('yne-bulb-block') >= 0) {
        alert('不要从word、有道等软件中直接粘贴过来，使用ctl+shift+v')
        return false;
    }

    $('#formFeedbackContent').val( c );
    return true;
}

function onpublishcheck( ) {
    if ($("#task-title").val() === "") {
        alert('没有填写标题');
        return false;
    }
    if ($("#leaders").val() <= 0) {
        alert('没有选择部门或者负责人');
        return false;
    }

    if ($("#tags").val() <= 0) {
        alert('没有选择项目或者版本');
        return false;
    }

    return true;
}

function onchagecheckstate(id, iid, slt ) {
    var td = document.getElementById("index-" + iid);
    if (slt.value == 1) {
        td.className = "bg-green";
    } else {
        td.className = "bg-red";
    }

    $.ajax({
        data: "id=" + id + "&iid=" + iid + "&passk=" + slt.value,
        type: "POST",
        url: '/task/icheck',
        cache: false,
        success: function( ) {
            alert("修改成功...");
        }
    });
}

function checkall( id, name, b ) {
    var els = $("#" + id + " :checkbox");
    for (var i = 0; i < els.length; i++) {
        var el = $(els[i]);
        if (name == el.prop("name")) {
            if ("undefined" == typeof(b) ) {
                if (el.prop("checked")) {
                    el.prop("checked", false);
                } else {
                    el.prop("checked", true);
                }
            } else{
                el.prop("checked", b);
            }
        }
    }
}


function test() {
    cfm('ssssss', function( ) {alert('sfdsfsdf');});
}

function checklog( o, table, resid ) {
    var box = $(o).parent().parent().parent();
    var els = box.find("input:checked");

    if (els.length < 2) {
        return;
    }

    var oldid = $(els[0]).val();
    var newid = $(els[1]).val();
    $.ajax({
        url:"/task/diff/" + table + "/" + oldid + "/" + newid + "/1"
    }).done(function(ret){
        $("#" + resid).html(ret);
    });
}

function settaskcontent( id ) {
    $("#tasklogs").find("input:checked").prop("checked", false);

    $.ajax({
        url:"/task/content/" + id
    }).done(function(ret){
        $("#taskcontent").html(ret);
    });
}

function setfeedbackcontent( id ) {
    $("#feedbacklogs").find("input:checked").prop("checked", false);
    $.ajax({
        url:"/feedback/content/" + id
    }).done(function(ret){
        $("#feedback-" + id).html(ret);
    });
}

function diff( table, oldid, newid, resid ) {
    $.ajax({
        url:"/task/diff/" + table + "/" + oldid + "/" + newid
    }).done(function(ret){
        $("#" + resid).html(ret);
    });
}

function changeMore( ) {
    var s = get_form_values( "tasklist" );
    if ("" == s) {
        alert( "没有选择任务" );
        return;
    }

    var changed = false;
    var els = $("#changemoreform [itag='val']");
    for (var i = 0; i < els.length; i++) {
        var el = $(els[i]);
        var va = el.val();
        if (va > 0) {
            s += "&" + el.prop("name") + "=" + va;
            el.val( 0 );
            changed = true;
        }
    }
    if (!changed) {
        alert( "没有变化" );
        return;
    }

    s += "&title=" + $("#stitle").val();
    s += "&page=" + page + "&" + get_form_values( "taskfilter" );
    console.log( s );

    $.ajax({
        data: s,
        url:'/task/index'
    }).done(function(data){
        $("#tasklist").html( data );
    });
}
