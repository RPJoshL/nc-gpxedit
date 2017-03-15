$(document).ready(function() {

    if (OCA.Files && OCA.Files.fileActions) {

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
