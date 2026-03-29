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

addEventListener("DOMContentLoaded", (event) => {
    const fileInput = document.querySelector("#uploadExtraSymbol input[type='file']");

    htmx.on("#uploadExtraSymbol", "htmx:beforeRequest", (evt) => {
        if (fileInput.files.length === 0) {
            // Open filepicker to select file
            fileInput.click();
            evt.preventDefault();
        }
    });
    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            htmx.trigger("#uploadExtraSymbol", "submit");
        }
    })

    htmx.on("#uploadExtraSymbol", "htmx:afterRequest", (evt) => {
        try {
            var status = evt.detail.xhr && evt.detail.xhr.status;
            // Reset Icon
             $('label#uploadsymbol').addClass('icon-upload').removeClass('icon-loading-small');

            if (status >= 200 && status < 300) {
                // versuche JSON auszulesen und name zu verwenden
                var respText = evt.detail.xhr.response;
                try {
                    var obj = JSON.parse(respText);
                    if (obj && obj.data && obj.data.name) {
                         addLogoLine(obj.data.name);
                    }
                    OC.msg.finishedSaving('#extraSymbolsSettingsMsg', obj);
                } catch(err) {
                    OC.msg.finishedSaving('#extraSymbolsSettingsMsg', {data:{message: 'Upload finished'}});
                }
            } else {
                OC.msg.finishedError('#extraSymbolsSettingsMsg', 'Failed');
            }
        } catch(ignore){}
    });

    htmx.on("#mapboxApiKey", "htmx:afterRequest", (evt) => {
        const code = evt.detail.xhr.status;

        if (evt.detail.xhr && evt.detail.xhr.status == 403) {
            OC.PasswordConfirmation.requirePasswordConfirmation(() => {
                htmx.trigger("#mapboxApiKey", "submit");
            });
        } else {
            OC.Notification.show(evt.detail.xhr.response, { type: code === 200 ? 'success' : 'error' });
        }
    })
})

$(document).ready(function() {
    var url = OC.generateUrl('/apps/gpxedit/getExtraSymbol?');
    $('tr.extraSymbol img').each(function(){
        var filename = $(this).attr('src');
        var fullurl = url+'name='+encodeURI(filename);
        $(this).attr('src', fullurl);
    });

    $('body').on('click', 'button.delExtraSymbol', function(e) {
        deleteLogo($(this));
    });

});
