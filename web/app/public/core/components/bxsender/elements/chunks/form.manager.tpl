<form action="" method="post" class="ajax_form af_example">
    <input type="hidden" name="bx_action" value="manager">
    <input type="hidden" name="token" value="[[+fi.token]]">
    <input type="hidden" name="email" value="[[+fi.email]]">

    <div class="alert alert-primary" role="alert">
        <strong>[[+fi.email]]</strong> <br>
        <small>Хотите сменить адрес электронной почты? Отпишитесь здесь, потом подпишитесь заново.</small>
    </div>
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
        <label class="control-label col-sm-3" for="af_segments">Наши рассылки</label>
        <div class="col-md-8">
            [[!bxSubscribeSegments? &checkeds=`[[+fi.segments]]`]]
            <span class="error_segments">[[+fi.error.segments]]</span>
        </div>
    </div>

    <div class="form-group  row">
        <label class="control-label col-sm-3" for="af_state">Статус подписки</label>
        <div class="col-md-6">
            <select name="state" class="form-control" id="af_state">
                <option value="subscribe" [[+fi.state:is=`subscribe`:then=`selected`]]>Подписан</option>
                <option value="unsubscribed" [[+fi.state:is=`unsubscribed`:then=`selected`]]>Отписан</option>
            </select>
            <small>Выберите статус <b>Отписан</b> чтобы отписаться от всех рассылок</small>
        </div>
    </div>



[[+modx.user.id:is=`0`:then=``:else=`
        [[+fi.user_id:is=`0`:then=`
            <div class="form-group row">
                <div class="offset-md-3 col-md-6">
                    <input type="checkbox" id="af_subscription" name="bind_user_subscription" value="1"
                           [[+fi.user_id:ne=`0`:then=`checked`]]>
                    <label class="control-label" for="af_subscription">Привязать пользователя к подписке</label>
                </div>
            </div>
        `]]
    `]]

    <div class="form-group row">
        <div class="offset-md-3 col-sm-8">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </div>

    [[+fi.success:is=`1`:then=`
    <div class="alert alert-success">[[+fi.successMessage]]</div>
    `]]
    [[+fi.validation_error:is=`1`:then=`
    <div class="alert alert-danger">[[+fi.validation_error_message]]</div>
    `]]
</form>