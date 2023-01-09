Ext.namespace('bxSender.utils')

bxSender.utils.renderBoolean = function (value, props, row) {

    return value
        ? String.format('<span class="green">{0}</span>', _('yes'))
        : String.format('<span class="red">{0}</span>', _('no'))
}

bxSender.utils.openPageMailingId = function (mailing_id) {

    var str = mailing_id ? '&mailing_id=' + mailing_id : ''
    document.location.href = bxSender.config.controller_mailing + str
    return true
}

bxSender.utils.openMailingBrowse = function (mailing_id) {
    var str = mailing_id ? '?mailing_id=' + mailing_id : ''
    document.location.href = '..' + bxSender.config.openbrowserUrl + str
    return true
}

bxSender.utils.renderEmail = function (value, props, row) {
    if (row.data.user_id == 0) {
        return value
    } else {
        return '<a target="_blank" href="/manager/?a=security/user/update&id=' + row.data.user_id + '" style="color:#428bca;">' + value + '</a>'
    }
}

bxSender.utils._renderBoolean = function (val, cell, row) {
    return val == '' || val == 0
        ? '<span style="color:red">' + _('no') + '<span>'
        : '<span style="color:green">' + _('yes') + '<span>'
}

bxSender.utils._renderTemplate = function (val, cell, row) {
    if (!val) {return ''}
    else if (row.data['templatename']) {
        val = '<sup>(' + val + ')</sup> ' + row.data['templatename']
    }
    return val
}

bxSender.utils.renderLinkTender = function (value, props, row) {

    // event_object
    if (row.data.user_id == 0) {
        return value
    } else {
        return '<a target="_blank" href="../tenders/' + value + '" style="color:#428bca;">' + value + '</a>'
    }
}

// https://code.google.com/p/strftime-js/
Date.ext = {}
Date.ext.util = {}
Date.ext.util.xPad = function (x, pad, r) {
    if (typeof (r) == 'undefined') {r = 10}
    for (; parseInt(x, 10) < r && r > 1; r /= 10) {x = pad.toString() + x}
    return x.toString()
}
Date.prototype.locale = 'en-GB'
if (document.getElementsByTagName('html') && document.getElementsByTagName('html')[0].lang) {Date.prototype.locale = document.getElementsByTagName('html')[0].lang}
Date.ext.locales = {}
Date.ext.locales.en = {
    a: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
    A: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
    b: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    B: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    c: '%a %d %b %Y %T %Z',
    p: ['AM', 'PM'],
    P: ['am', 'pm'],
    x: '%d/%m/%y',
    X: '%T'
}
Date.ext.locales['en-US'] = Date.ext.locales.en
Date.ext.locales['en-US'].c = '%a %d %b %Y %r %Z'
Date.ext.locales['en-US'].x = '%D'
Date.ext.locales['en-US'].X = '%r'
Date.ext.locales['en-GB'] = Date.ext.locales.en
Date.ext.locales['en-AU'] = Date.ext.locales['en-GB']
Date.ext.formats = {
    a: function (d) {return Date.ext.locales[d.locale].a[d.getDay()]},
    A: function (d) {return Date.ext.locales[d.locale].A[d.getDay()]},
    b: function (d) {return Date.ext.locales[d.locale].b[d.getMonth()]},
    B: function (d) {return Date.ext.locales[d.locale].B[d.getMonth()]},
    c: 'toLocaleString',
    C: function (d) {return Date.ext.util.xPad(parseInt(d.getFullYear() / 100, 10), 0)},
    d: ['getDate', '0'],
    e: ['getDate', ' '],
    g: function (d) {return Date.ext.util.xPad(parseInt(Date.ext.util.G(d) / 100, 10), 0)},
    G: function (d) {
        var y = d.getFullYear()
        var V = parseInt(Date.ext.formats.V(d), 10)
        var W = parseInt(Date.ext.formats.W(d), 10)
        if (W > V) {y++} else {if (W === 0 && V >= 52) {y--}}
        return y
    },
    H: ['getHours', '0'],
    I: function (d) {
        var I = d.getHours() % 12
        return Date.ext.util.xPad(I === 0 ? 12 : I, 0)
    },
    j: function (d) {
        var ms = d - new Date('' + d.getFullYear() + '/1/1 GMT')
        ms += d.getTimezoneOffset() * 60000
        var doy = parseInt(ms / 60000 / 60 / 24, 10) + 1
        return Date.ext.util.xPad(doy, 0, 100)
    },
    m: function (d) {return Date.ext.util.xPad(d.getMonth() + 1, 0)},
    M: ['getMinutes', '0'],
    p: function (d) {return Date.ext.locales[d.locale].p[d.getHours() >= 12 ? 1 : 0]},
    P: function (d) {return Date.ext.locales[d.locale].P[d.getHours() >= 12 ? 1 : 0]},
    S: ['getSeconds', '0'],
    u: function (d) {
        var dow = d.getDay()
        return dow === 0 ? 7 : dow
    },
    U: function (d) {
        var doy = parseInt(Date.ext.formats.j(d), 10)
        var rdow = 6 - d.getDay()
        var woy = parseInt((doy + rdow) / 7, 10)
        return Date.ext.util.xPad(woy, 0)
    },
    V: function (d) {
        var woy = parseInt(Date.ext.formats.W(d), 10)
        var dow1_1 = (new Date('' + d.getFullYear() + '/1/1')).getDay()
        var idow = woy + (dow1_1 > 4 || dow1_1 <= 1 ? 0 : 1)
        if (idow == 53 && (new Date('' + d.getFullYear() + '/12/31')).getDay() < 4) {idow = 1} else {if (idow === 0) {idow = Date.ext.formats.V(new Date('' + (d.getFullYear() - 1) + '/12/31'))}}
        return Date.ext.util.xPad(idow, 0)
    },
    w: 'getDay',
    W: function (d) {
        var doy = parseInt(Date.ext.formats.j(d), 10)
        var rdow = 7 - Date.ext.formats.u(d)
        var woy = parseInt((doy + rdow) / 7, 10)
        return Date.ext.util.xPad(woy, 0, 10)
    },
    y: function (d) {return Date.ext.util.xPad(d.getFullYear() % 100, 0)},
    Y: 'getFullYear',
    z: function (d) {
        var o = d.getTimezoneOffset()
        var H = Date.ext.util.xPad(parseInt(Math.abs(o / 60), 10), 0)
        var M = Date.ext.util.xPad(o % 60, 0)
        return (o > 0 ? '-' : '+') + H + M
    },
    Z: function (d) {return d.toString().replace(/^.*\(([^)]+)\)$/, '$1')},
    '%': function (d) {return '%'}
}
Date.ext.aggregates = {
    c: 'locale',
    D: '%m/%d/%y',
    h: '%b',
    n: '\n',
    r: '%I:%M:%S %p',
    R: '%H:%M',
    t: '\t',
    T: '%H:%M:%S',
    x: 'locale',
    X: 'locale'
}
Date.ext.aggregates.z = Date.ext.formats.z(new Date())
Date.ext.aggregates.Z = Date.ext.formats.Z(new Date())
Date.ext.unsupported = {}
Date.prototype.strftime = function (fmt) {
    if (!(this.locale in Date.ext.locales)) {if (this.locale.replace(/-[a-zA-Z]+$/, '') in Date.ext.locales) {this.locale = this.locale.replace(/-[a-zA-Z]+$/, '')} else {this.locale = 'en-GB'}}
    var d = this
    while (fmt.match(/%[cDhnrRtTxXzZ]/)) {
        fmt = fmt.replace(/%([cDhnrRtTxXzZ])/g, function (m0, m1) {
            var f = Date.ext.aggregates[m1]
            return (f == 'locale' ? Date.ext.locales[d.locale][m1] : f)
        })
    }
    var str = fmt.replace(/%([aAbBCdegGHIjmMpPSuUVwWyY%])/g, function (m0, m1) {
        var f = Date.ext.formats[m1]
        if (typeof (f) == 'string') {return d[f]()} else {if (typeof (f) == 'function') {return f.call(d, d)} else {if (typeof (f) == 'object' && typeof (f[0]) == 'string') {return Date.ext.util.xPad(d[f[0]](), f[1])} else {return m1}}}
    })
    d = null
    return str
}

bxSender.utils.formatDate = function (string) {
    if (string && string != '0000-00-00 00:00:00' && string != 0) {
        var date = /^[0-9]+$/.test(string)
            ? new Date(string * 1000)
            : new Date(string.replace(/(\d+)-(\d+)-(\d+)/, '$2/$3/$1'))

        return date.strftime('%d.%m.%Y %H:%M')
    }
    else {
        return '&nbsp;'
    }
}

bxSender.utils.formatDate2 = function (string) {
    if (string && string != '0000-00-00 00:00:00' && string != 0) {
        var date = /^[0-9]+$/.test(string)
            ? new Date(string * 1000)
            : new Date(string.replace(/(\d+)-(\d+)-(\d+)/, '$2/$3/$1'))

        return date.strftime('%d.%m.%Y')
    }
    else {
        return '&nbsp;'
    }
}

bxSender.utils.renderActions = function (value, props, row) {
    var res = []
    var cls, icon, title, action, item
    for (var i in row.data.actions) {
        if (!row.data.actions.hasOwnProperty(i)) {
            continue
        }
        var a = row.data.actions[i]
        if (!a['button']) {
            continue
        }

        icon = a['icon'] ? a['icon'] : ''
        if (typeof(a['cls']) == 'object') {
            if (typeof(a['cls']['button']) != 'undefined') {
                icon += ' ' + a['cls']['button']
            }
        }
        else {
            cls = a['cls'] ? a['cls'] : ''
        }
        action = a['action'] ? a['action'] : ''
        title = a['title'] ? a['title'] : ''

        item = String.format(
            '<li class="{0}"><button class="btn btn-default {1}" action="{2}" title="{3}"></button></li>',
            cls, icon, action, title
        )

        res.push(item)
    }

    return String.format(
        '<ul class="bxsender-row-actions">{0}</ul>',
        res.join('')
    )
}

bxSender.utils.getMenu = function (actions, grid, selected) {
    var menu = []
    var cls, icon, title, action = ''

    var has_delete = false
    for (var i in actions) {
        if (!actions.hasOwnProperty(i)) {
            continue
        }

        var a = actions[i]
        if (!a['menu']) {
            if (a == '-') {
                menu.push('-')
            }
            continue
        }
        else if (menu.length > 0 && !has_delete && (/^remove/i.test(a['action']) || /^delete/i.test(a['action']))) {
            menu.push('-')
            has_delete = true
        }

        if (selected.length > 1) {
            if (!a['multiple']) {
                continue
            }
            else if (typeof(a['multiple']) == 'string') {
                a['title'] = a['multiple']
            }
        }

        icon = a['icon'] ? a['icon'] : ''
        if (typeof(a['cls']) == 'object') {
            if (typeof(a['cls']['menu']) != 'undefined') {
                icon += ' ' + a['cls']['menu']
            }
        }
        else {
            cls = a['cls'] ? a['cls'] : ''
        }
        title = a['title'] ? a['title'] : a['title']
        action = a['action'] ? grid[a['action']] : ''

        menu.push({
            handler: action,
            text: String.format(
                '<span class="{0}"><i class="x-menu-item-icon {1}"></i>{2}</span>',
                cls, icon, title
            ),
            scope: grid
        })
    }

    return menu
}

bxSender.utils.onAjax = function (el) {
    Ext.Ajax.el = el
    Ext.Ajax.on('beforerequest', bxSender.utils.beforerequest)
    Ext.Ajax.on('requestcomplete', bxSender.utils.requestcomplete)
}

bxSender.utils.beforerequest = function () {Ext.Ajax.el.mask(_('loading'), 'x-mask-loading')}
bxSender.utils.requestcomplete = function () {
    Ext.Ajax.el.unmask()
    Ext.Ajax.el = null
    Ext.Ajax.un('beforerequest', bxSender.utils.beforerequest)
    Ext.Ajax.un('requestcomplete', bxSender.utils.requestcomplete)
}


bxSender.Description = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        cls: 'panel-desc'
        ,itemId: 'description'
    });
    bxSender.Description.superclass.constructor.call(this, config);
};
Ext.extend(bxSender.Description, Ext.BoxComponent, {});
Ext.reg('bxsender-description', bxSender.Description);