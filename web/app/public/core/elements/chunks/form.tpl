<form name="feedback" action="" method="post" class="forma_unit ajax_form" >
    <div class="forma_headline">
        Feedback
    </div>
    <div class="forma_subtitle">
        If you have any questions, you can ask us by filling out the feedback form
    </div>
    <div class="contacts_forma">
        <div class="contacts_controll">
            <input type="text" value="[[+fi.name]]" validate="required" name="name">
            <span class="lab">Name <i>*</i></span>
            <div class="error_name contacts_controll_error_info">
                [[+fi.error.name]]
            </div>
        </div>
        <div class="contacts_controll ">
            <input type="text" value="[[+fi.email]]" validate="required,email" name="email">
            <span class="lab">E-mail <i>*</i></span>
            <div class="error_email contacts_controll_error_info">
                [[+fi.error.email]]
            </div>
        </div>
        <div class="contacts_controll">
            <input type="phone" value="[[+fi.phone]]" validate="required,phone" name="phone">
            <span class="lab">phone <i>*</i></span>
            <div class="error_phone contacts_controll_error_info">
                [[+fi.error.phone]]
            </div>
        </div>
        <div class="contacts_controll ">
            <textarea value="[[+fi.message]]" validate="required" name="message"></textarea>
            <span class="lab">your message <i>*</i></span>
            <div class="error_phone contacts_controll_error_info">
                [[+fi.error.message]]
            </div>
        </div>
        <div class="contacts_forma_info">
            <i>*</i>These fields are required
        </div>
        <button type="submit" class='but btn_white'>SEND</button>
        <div class="checkwrap">
            <input type='checkbox' validate="required" required class='checkbox' name="elua" id='c33'/>
            <label for='c33'>
                I confirm the agreement to process my personal data in accordance with the Terms
            </label>
        </div>
        <div class="captcha_wrap">
            [[!rcv3_html? &action=`[[+rcv3Action:default=``]]`]]
        </div>
    </div>
</form>