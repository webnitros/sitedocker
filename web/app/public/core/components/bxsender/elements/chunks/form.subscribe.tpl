<form action="" method="post" class="ajax_form af_example">
    <input type="hidden" name="bx_action" value="subscribe">
    <br>
    <div class="form-group  row">
        <label class="control-label col-sm-3" for="af_fullname">Ваше имя</label>
        <div class="controls col-md-6">
            <input type="text" id="af_fullname" name="fullname" value="[[+fi.fullname]]" placeholder=""
                   class="form-control"/>
            <span class="error_fullname">[[+fi.error.fullname]]</span>
        </div>
    </div>

    <div class="form-group  row">
        <label class="control-label col-sm-3" for="af_email">E-mail</label>
        <div class="controls col-md-6">
            <input type="email" id="af_email" name="email" value="[[+fi.email]]" placeholder="" class="form-control"/>
            <span class="error_email">[[+fi.error.email]]</span>
        </div>
    </div>

    <div class="form-group row">
        <label class="control-label col-sm-3" for="af_segments">Рассылки</label>
        <div class="col-md-8">
            [[!bxSubscribeSegments]]
            <span class="error_segments">[[+fi.error.segments]]</span>
        </div>
    </div>

    <div class="form-group row">
        <div class="offset-md-3 col-sm-8">
            <button type="submit" class="btn btn-primary">Подписаться на рассыку</button>
        </div>
    </div>

    [[+fi.success:is=`1`:then=`
    <div class="alert alert-success">[[+fi.successMessage]]</div>
    `]]
    [[+fi.validation_error:is=`1`:then=`
    <div class="alert alert-danger">[[+fi.validation_error_message]]</div>
    `]]
</form>