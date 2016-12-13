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

function addLogoLine(name){
    var url = OC.generateUrl('/apps/gpxedit/getExtraSymbol?');
    var fullurl = url+'name='+encodeURI(name);
    var nameWe = name.replace(/\.png$/, '');
    $('div#extraSymbols').append('<p class="extraSymbol" id="'+nameWe+'">'+
            '<img src="'+fullurl+'"><label> '+nameWe+' </label>'+
            '<button class="delExtraSymbol icon-delete icon" name="'+name+'" title="Remove"></button></p>');
}

function deleteLogo(button){
    button.removeClass('icon-delete').addClass('icon-loading-small');
    var name = button.attr('name');
    var req = {
        name : name,
    }
    var url = OC.generateUrl('/apps/gpxedit/deleteExtraSymbol');
    $.post(url, req).done(function (response) {
        button.parent().remove();
        OC.msg.finishedSuccess('#extraSymbolsSettingsMsg', response.data.message);
    }).fail(function(){
        OC.msg.finishedError('#extraSymbolsSettingsMsg', 'Failed');
        button.addClass('icon-delete').removeClass('icon-loading-small');
    });
}

$(document).ready(function() {
    OCA.GpxEdit.Admin.initialize();
    var url = OC.generateUrl('/apps/gpxedit/getExtraSymbol?');
    $('p.extraSymbol img').each(function(){
        var filename = $(this).attr('src');
        var fullurl = url+'name='+encodeURI(filename);
        $(this).attr('src', fullurl);
    });

	var uploadParamsSymbol = {
        pasteZone: null,
        dropZone: null,
        done: function (e, response) {
            //preview('logoMime', response.result.data.name);
            addLogoLine(response.result.data.name);
            OC.msg.finishedSaving('#extraSymbolsSettingsMsg', response.result);
            $('label#uploadsymbol').addClass('icon-upload').removeClass('icon-loading-small');
        },
        submit: function(e, response) {
            OC.msg.startSaving('#extraSymbolsSettingsMsg');
            $('label#uploadsymbol').removeClass('icon-upload').addClass('icon-loading-small');
        },
        fail: function (e, response){
            OC.msg.finishedError('#extraSymbolsSettingsMsg', response.data.message);
            $('label#uploadsymbol').addClass('icon-upload').removeClass('icon-loading-small');
        }
    };

    $('#uploadsymbol').fileupload(uploadParamsSymbol);
    $('body').on('click', 'button.delExtraSymbol', function(e) {
        deleteLogo($(this));
    });

});
