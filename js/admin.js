(function() {
    if (!OCA.GpxEdit) {
        OCA.GpxEdit = {};
    }
})();

function addLogoLine(name){
    var url = OC.generateUrl('/apps/gpxedit/getExtraSymbol?');
    var fullurl = url+'name='+encodeURI(name);
    var nameWe = name.replace(/\.png$/, '');
    $('div#extraSymbols table').append('<tr class="extraSymbol" id="'+nameWe+'">'+
            '<td><img src="'+fullurl+'"></td><td><label> '+nameWe+' </label></td>'+
            '<td><button class="delExtraSymbol icon-delete icon" name="'+name+
            '" title="' +
            t('gpxedit','Delete') +
            '"></button></td></tr>');
}

function deleteLogo(button){
    button.removeClass('icon-delete').addClass('icon-loading-small');
    var name = button.attr('name');
    var req = {
        name : name,
    }
    var url = OC.generateUrl('/apps/gpxedit/deleteExtraSymbol');
    $.post(url, req).done(function (response) {
        button.parent().parent().remove();
        OC.msg.finishedSuccess('#extraSymbolsSettingsMsg', response.data.message);
    }).fail(function(){
        OC.msg.finishedError('#extraSymbolsSettingsMsg', 'Failed');
        button.addClass('icon-delete').removeClass('icon-loading-small');
    });
}

$(document).ready(function() {
    var url = OC.generateUrl('/apps/gpxedit/getExtraSymbol?');
    $('tr.extraSymbol img').each(function(){
        var filename = $(this).attr('src');
        var fullurl = url+'name='+encodeURI(filename);
        $(this).attr('src', fullurl);
    });

	var uploadParamsSymbol = {
        pasteZone: null,
        dropZone: null,
        done: function (e, response) {
            addLogoLine(response.result.data.name);
            OC.msg.finishedSaving('#extraSymbolsSettingsMsg', response.result);
            $('label#uploadsymbol').addClass('icon-upload').removeClass('icon-loading-small');
        },
        submit: function(e, response) {
            OC.msg.startSaving('#extraSymbolsSettingsMsg');
            $('label#uploadsymbol').removeClass('icon-upload').addClass('icon-loading-small');
            if ($('input#addExtraSymbolName').val() === ''){
                OC.msg.finishedError('#extraSymbolsSettingsMsg', 'Empty symbol name');
                e.preventDefault();
                $('label#uploadsymbol').addClass('icon-upload').removeClass('icon-loading-small');
            }
        },
        fail: function (e, response){
            OC.msg.finishedError('#extraSymbolsSettingsMsg', response._response.jqXHR.responseJSON.data.message);
            $('label#uploadsymbol').addClass('icon-upload').removeClass('icon-loading-small');
        }
    };

    $('#uploadsymbol').fileupload(uploadParamsSymbol);
    $('body').on('click', 'button.delExtraSymbol', function(e) {
        deleteLogo($(this));
    });

});
