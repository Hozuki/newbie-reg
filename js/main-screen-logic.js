/**
 * Created by MIC on 2015/8/22.
 */

(function () {
    var students = [];
    var currentIndex = -1;
    var lastUpdatedTick = 0;
    var lastStudentsCount = 0;
    var jqErrorContainer = $('#error-message-container');
    var jqErrorTitle = $('#error-title');
    var jqErrorMessage = $('#error-message');
    var jqWelcomeContainer = $('#welcome-message-container');
    var jqWelcomeMessage = $('#welcome-message');
    var jqTopMessage = $('#top-message');
    var jqBtnPrev = $('#btn-prev');
    var jqBtnNext = $('#btn-next');

    var getWelcomeHtml = function (studentName) {
        return '欢迎<strong class="text-success">' + studentName + '</strong>同学来到机械学院！';
    };

    var showWelcomeMessage = function (index) {
        try {
            index = parseInt(index);
        } catch (ex) {
            index = -1;
        }
        if (index >= 0 && index < students.length) {
            jqWelcomeMessage[0].innerHTML = getWelcomeHtml(students[index]['sname']);
        } else {
            reportError({
                "title": "索引错误",
                "message": "showWelcomeMessage() 中的 index 参数错误。"
            });
        }
    };

    var reportError = function (errorObject) {
        jqWelcomeContainer.hide();
        jqTopMessage.removeClass('bg-info').removeClass('text-info');
        jqTopMessage.addClass('bg-danger').addClass('text-danger');
        jqErrorTitle.text(errorObject.title);
        jqErrorMessage.text(errorObject.message);
        jqErrorContainer.show();
    };

    var reportWelcome = function () {
        jqErrorContainer.hide();
        jqTopMessage.removeClass('bg-danger').removeClass('text-danger');
        jqTopMessage.addClass('bg-info').addClass('text-info');
        jqWelcomeContainer.show();
    };

    var updateButtonStates = function () {
        if (students.length <= 0 || currentIndex <= 0) {
            jqBtnPrev.attr('disabled', 'true');
        } else {
            jqBtnPrev.removeAttr('disabled');
        }
        if (students.length <= 0 || currentIndex >= students.length - 1) {
            jqBtnNext.attr('disabled', 'true');
        } else {
            jqBtnNext.removeAttr('disabled');
        }
    };

    jqBtnPrev.on('click', function () {
        if (currentIndex > 0) {
            currentIndex--;
            showWelcomeMessage(currentIndex);
            updateButtonStates();
        }
    });

    jqBtnNext.on('click', function () {
        if (currentIndex < students.length - 1) {
            currentIndex++;
            showWelcomeMessage(currentIndex);
            updateButtonStates();
        }
    });

    var fun = function () {
        var url = nrconfig['siteroot'] + "logic/get-stat.php?time=" + lastUpdatedTick.toString() + '&m=new';
        lastUpdatedTick = nrutil.getCurrentTick();
        $.ajax({
            method: 'GET',
            url: url,
            timeout: 2000,
            error: function (xmlHttpRequest, textStatus, errorThrown) {
                reportError({
                    "title": textStatus,
                    "message": errorThrown
                });
            },
            success: function (dataText, textStatus) {
                var jsonObject = [];

                try {
                    jsonObject = JSON.parse(dataText);
                } finally {
                }

                students = students.concat(jsonObject);
                reportWelcome();
                if (students.length > 0 && currentIndex < 0) {
                    currentIndex = 0;
                    showWelcomeMessage(currentIndex);
                }
                if (students.length > 0 && lastStudentsCount < students.length && currentIndex == lastStudentsCount - 1) {
                    // 如果发生了更新，而且当前正在浏览更新前的最后一位，则自动跳转到更新后的最后一位
                    currentIndex = students.length - 1;
                    showWelcomeMessage(currentIndex);
                }
                updateButtonStates();
                lastStudentsCount = students.length;
            }
        });
    };

    fun(0);
    setInterval(fun, 3000);
})();
