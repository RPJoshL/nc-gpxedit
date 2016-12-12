(function() {
    if (!OCA.GpxEdit) {
        OCA.GpxEdit = {};
    }

    OCA.GpxEdit.Admin = {
        initialize: function() {
            $('#submitMaxUpload').on('click', _.bind(this._onClickSubmitMaxUpload, this));
        },

        _onClickSubmitMaxUpload: function () {
            OC.msg.startSaving('#maxUploadSizeSettingsMsg');

            var request = $.ajax({
                url: OC.generateUrl('/apps/files/settings/maxUpload'),
                type: 'POST',
                data: {
                    maxUploadSize: $('#maxUploadSize').val()
                }
            });

            request.done(function (data) {
                $('#maxUploadSize').val(data.maxUploadSize);
                OC.msg.finishedSuccess('#maxUploadSizeSettingsMsg', 'Saved');
            });

            request.fail(function () {
                OC.msg.finishedError('#maxUploadSizeSettingsMsg', 'Error');
            });
        }
    }
})();


$(document).ready(function() {
    OCA.GpxEdit.Admin.initialize();
    var url = OC.generateUrl('/apps/gpxedit/getExtraSymbol?');
    $('p.extraSymbol img').each(function(){
        var filename = $(this).attr('src');
        var fullurl = url+'name='+encodeURI(filename);
        $(this).attr('src', fullurl);
    });

});
