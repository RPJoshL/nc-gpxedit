# GpxEdit owncloud/nextcloud application

If you want to help to translate this app in your language, take the english=>french files in "l10n" directory as examples.

Simple Owncloud/Nextcloud app to load, edit and save GPX file on an interactive map.
You can load/save files from your Owncloud/Nextcloud file storage.
GPX, KML, CSV (unicsv format) and geotagged JPG are supported for loading. JPG files will be loaded as waypoints.
There is a file explorer inside the app interface to select a file to load and a folder to save what is currently on the map.

This is not a perfect GPX editor.

What's saved :
- tracks
    - name
    - comment
    - description
    - points
        - coordinates
        - elevation (just preserves loaded values)
- waypoint
    - coordinates
    - name
    - comment
    - description
    - symbol
    - elevation (just preserves loaded value)

**WARNING** GpxEdit does not load/save any time data, even if you loaded a gpx file which has time information. Keep that in mind if you overwrite a file.
Elevation data is loaded and saved but every new data added by user actions in GpxEdit will have neither elevation nor time data.

Tracks are saved with one segment.

If you want more powerfull GPX editors, take a look at :
- [Viking](https://sourceforge.net/projects/viking/) which is the best IMHO
- [QLandKarteGT](https://bitbucket.org/kiozen/qlandkarte-gt)
- [QMapShack](https://bitbucket.org/maproom/qmapshack/wiki/Home)
- [JOSM](https://josm.openstreetmap.de/)

GpxEdit :
- works with server-side encryption.
- works with shared files.
- loads GPX, KML, unicsv CSV, geotagged JPG files
- loads tracks, routes and waypoints
- saves tracks and waypoints
- supports waypoint symbols
- uses [Leaflet.Draw](https://github.com/Leaflet/Leaflet.draw) amazing plugin
- uses many other Leaflet plugins like Minimap, Sidebar2, MeasureControl, MousePositionControl
- uses JQuery
- uses [JQuery File Tree](https://github.com/jqueryfiletree/jqueryfiletree) amazing file tree library

Any feedback will be appreciated.
