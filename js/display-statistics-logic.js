/**
 * Created by MIC on 2015/8/22.
 */

(function () {
    var jqStatTable = $('#stat-table');
    var jqListContent = $('#list-content');
    var jqErrorReport = $('#top-message');
    var jqErrorTitle = $('#error-title');
    var jqErrorMessage = $('#error-message');

    var reportError = function (errorObject) {
        jqStatTable.hide();
        jqErrorTitle.text(errorObject.title);
        jqErrorMessage.text(errorObject.message);
        jqErrorReport.show();
    };

    var prepareToShowTable =function() {
        jqErrorReport.hide();
        jqStatTable.show();
    };

    var fun = function () {
        var t = nrutil.getCurrentTick();
        $.ajax({
            method: 'GET',
            url: nrconfig['siteroot'] + "logic/get-stat.php?time=" + t.toString() + '&m=global',
            timeout: 2000,
            error: function (xmlHttpRequest, textStatus, errorThrown) {
                reportError({
                    "title": textStatus,
                    "message": errorThrown
                });
            },
            success: function (dataText, textStatus) {
                prepareToShowTable();
                jqListContent[0].innerHTML = dataText;
            }
        });
    };
    fun();
    setInterval(fun, 3000);
})();
