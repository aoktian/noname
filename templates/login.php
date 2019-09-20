<?php $this->layout('layout')?>
<?php $this->start('main')?>

<form method="POST" action="/index/login">
<div class="form-group">
<label>EMAIL</label>
<input name="email" type="email" class="form-control form-control-lg" placeholder="输入EMAIL">
</div>

<div class="form-group">
<label>密码</label>
<input name="password" type="password" class="form-control form-control-lg">
</div>

<button type="submit" class="btn btn-primary btn-lg btn-block">登 入</button>
</form>

<?php $this->end()?>
