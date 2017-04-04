$(document).ready(function() {

    if (OCA.Files && OCA.Files.fileActions && !$('#sharingToken').val()) {

		var token = $('#sharingToken').val();

        // file action for directories
        if (!token) {
            OCA.Files.fileActions.registerAction({
                name: 'viewDirectoryGpxEdit',
                displayName: t('gpxedit','Load in GpxEdit'),
                mime: 'httpd/unix-directory',
                permissions: OC.PERMISSION_READ,
                icon: function () {return OC.imagePath('gpxedit', 'app_black');},
                actionHandler: function(file, data){
                    var dir;
                    if (data.dir === '/'){
                        dir = data.dir+file;
                    }
                    else{
                        dir = data.dir+'/'+file;
                    }
                    var url = OC.generateUrl('apps/gpxedit/?dir={dir}',{'dir': dir});
                    window.open(url, '_blank');
                }
            });
        }

        function openFile(file, data){
            var url = OC.generateUrl('apps/gpxedit/?file={filepath}',{'filepath': data.dir+'/'+file});
            window.open(url, '_blank');
        }

        OCA.Files.fileActions.registerAction({
            name: 'editFileGpxEdit',
            displayName: t('gpxedit', 'Edit in GpxEdit'),
            mime: 'application/gpx+xml',
            permissions: OC.PERMISSION_READ,
            icon: function () {return OC.imagePath('gpxedit', 'app_black');},
            actionHandler: openFile
        });
    }

});
