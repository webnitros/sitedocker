<form action="" method="post" class="ajax_form af_example">
    <input type="hidden" name="bx_action" value="restore">

    <div class="form-group ">
        <label class="control-label" for="af_email">E-mail адрес для восстановления доступа к подписке</label>
        <div class="controls row col-md-6">
            <input type="email" id="af_email" name="email" value="[[+fi.email]]" placeholder="info@site.ru" class="form-control"/>
            <span class="error_email">[[+fi.error.email]]</span>
        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">Восстановить доступ</button>
    </div>

    [[+fi.success:is=`1`:then=`
    <div class="alert alert-success">[[+fi.successMessage]]</div>
    `]]
    [[+fi.validation_error:is=`1`:then=`
    <div class="alert alert-danger">[[+fi.validation_error_message]]</div>
    `]]
</form>