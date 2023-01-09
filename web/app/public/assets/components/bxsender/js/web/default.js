$(document).on('af_complete', function (event, response) {
    var form = response.form
    var bxAction = form.find('input[name=bx_action]')
    if (bxAction.length) {
        var state = form.find('select[name=state] option:selected')
        var status = 'unsubscribed'
        var Active = bxAction.val()
        if (state.length) {
            status = state.val()
        }
        switch (Active) {
            case 'manager':
                switch (status) {
                    case 'unsubscribed':
                        var segments = form.find('input.segments')
                        for (var i = 0; i < segments.length; i++) {
                            $(segments[i]).prop('checked', false)
                        }
                        break
                    default:
                        break
                }
                form[0].reset = function () {return false}
                break
            default:
                break
        }
    }
})
