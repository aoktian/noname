<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
<meta name="description" content="">
<meta name="author" content="aoktian">

<meta http-equiv="refresh" content="<?=$this->e($delay)?>;url=<?=$this->e($url)?>">

<title>管理后台</title>

</head>

<body>

<?php if ($delay > 0): ?>
<div class="container-fluid">

<div class="row">
<div class="col-md-4 col-md-offset-4">

<div class="page-header">
  <h1>管理后台</h1>
</div>

<div class="alert alert-danger">
    <p><strong>Whoops!</strong> Redirecting.</p>
    <p></p>
</div>

</div>
</div>

</div>
<?php endif?>
  </body>
</html>
