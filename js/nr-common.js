/**
 * Created by MIC on 2015/8/18.
 */

var nrutil = {
    UNIX_TIMESTAMP_BASE: new Date(Date.parse('1970-01-01T00:00:00.000Z')),
    setInputNormal: function (element) {
        if (element.jquery) {
            if (!element.parent().attr('origClass')) {
                element.parent().attr('origClass', element.parent().attr('class'));
            }
            //element.parent().attr('class', 'has-feedback');
            element.parent().attr('class', element.parent().attr('origClass'));
            element.next().hide(); // OK sign
            element.next().next().hide(); // Error sign
        } else {
            if (!element.parentNode.getAttribute('origClass')) {
                element.parentNode.setAttribute('origClass', element.parentNode.getAttribute('class'));
            }
            //element.parentNode.className = 'form-group has-feedback';
            element.parentNode.className = element.parentNode.getAttribute('origClass');
            // 不用 nextSibling，因为那会指向下一个*实际*兄弟，即使那个兄弟是浏览器自动创建的节点如 #text（Chrome）
            element.nextElementSibling.style.display = 'none'; // OK sign
            element.nextElementSibling.nextElementSibling.style.display = 'none'; // Error sign
        }
    },
    dateDiff: function (objDate1, objDate2, interval) {
        // 引入两个 timezone（时区，单位为分钟）用于处理时区误差
        // 一般的调用会获取本地时间，而可能其他地方指定了 UTC 时间，导致混乱
        // 这里统一为 UTC 时间
        var i = {};
        var t1 = objDate1.getTime() + objDate1.getTimezoneOffset() * 60;
        var t2 = objDate2.getTime() + objDate2.getTimezoneOffset() * 60;
        i['y'] = objDate2.getFullYear() - objDate1.getFullYear();
        i['q'] = i['y'] * 4 + Math.floor(objDate2.getMonth() / 4) - Math.floor(objDate1.getMonth() / 4);
        i['m'] = i['y'] * 12 + objDate2.getMonth() - objDate1.getMonth();
        i['ms'] = objDate2.getTime() - objDate1.getTime();
        i['w'] = Math.floor((t2 + 345600000) / (604800000)) - Math.floor((t1 + 345600000) / (604800000));
        i['d'] = Math.floor(t2 / 86400000) - Math.floor(t1 / 86400000);
        i['h'] = Math.floor(t2 / 3600000) - Math.floor(t1 / 3600000);
        i['n'] = Math.floor(t2 / 60000) - Math.floor(t1 / 60000);
        i['s'] = Math.floor(t2 / 1000) - Math.floor(t1 / 1000);
        return i[interval];
    },
    setInputError: function (element) {
        if (element.jquery) {
            if (!element.parent().attr('origClass')) {
                element.parent().attr('origClass', element.parent().attr('class'));
            }
            element.parent().attr('class', element.parent().attr('origClass') + ' has-error has-feedback');
            element.next().hide(); // OK sign
            element.next().next().show(); // Error sign
        } else {
            if (!element.parentNode.getAttribute('origClass')) {
                element.parentNode.setAttribute('origClass', element.parentNode.getAttribute('class'));
            }
            element.parentNode.className = element.parentNode.getAttribute('origClass') + ' has-error has-feedback';
            // 不用 nextSibling，因为那会指向下一个*实际*兄弟，即使那个兄弟是浏览器自动创建的节点如 #text（Chrome）
            element.nextElementSibling.style.display = 'none'; // OK sign
            element.nextElementSibling.nextElementSibling.style.display = 'inline'; // Error sign
        }
    },
    setInputOK: function (element) {
        if (element.jquery) {
            if (!element.parent().attr('origClass')) {
                element.parent().attr('origClass', element.parent().attr('class'));
            }
            element.parent().attr('class', element.parent().attr('origClass') + ' has-success has-feedback');
            element.next().show(); // OK sign
            element.next().next().hide(); // Error sign
        } else {
            if (!element.parentNode.getAttribute('origClass')) {
                element.parentNode.setAttribute('origClass', element.parentNode.getAttribute('class'));
            }
            element.parentNode.className = element.parentNode.getAttribute('origClass') + ' has-success has-feedback';
            // 不用 nextSibling，因为那会指向下一个*实际*兄弟，即使那个兄弟是浏览器自动创建的节点如 #text（Chrome）
            element.nextElementSibling.style.display = 'inline'; // OK sign
            element.nextElementSibling.nextElementSibling.style.display = 'none'; // Error sign
        }
    },
    setInputOKOrError: function (element, successful) {
        successful && this.setInputOK(element);
        !successful && this.setInputError(element);
    },
    getRelativePathFromHref: function (href) {
        // 返回的结果包含'/'
        var lastIndex = href.lastIndexOf('/');
        return lastIndex < 0 ? href : href.substring(0, lastIndex + 1);
    },
    getUpperPath: function (path) {
        var p = this.getRelativePathFromHref(path);
        if (p.length <= 1) {
            return p;
        }
        var lastIndex = p.lastIndexOf('/', p.length - 2);
        return lastIndex < 0 ? p : p.substring(0, lastIndex + 1);
    },
    getFileName: function (fullName) {
        var lastIndex1 = fullName.lastIndexOf('/');
        var lastIndex2 = fullName.lastIndexOf('\\');
        var lastIndex = Math.max(lastIndex1, lastIndex2);
        return lastIndex < 0 ? fullName : (lastIndex < fullName.length ? fullName.substring(lastIndex + 1) : '#error');
    },
    getCurrentTick: function () {
        var now = new Date();
        var ticks = this.dateDiff(this.UNIX_TIMESTAMP_BASE, now, 's');
        return Math.round(ticks);
    }
};
