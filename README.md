# GpxEdit owncloud/nextcloud application

Simple Owncloud/Nextcloud app to load, edit and save GPX files on an interactive map.
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
        - time (just preserves loaded values)
- waypoint
    - coordinates
    - name
    - comment
    - description
    - symbol
    - elevation (just preserves loaded value)
    - time (just preserves loaded values)

GpxEdit does load/save time data.
Elevation data is loaded and saved but every new waypoint/track/trackpoint added by user actions in GpxEdit will have neither elevation nor time data.

Tracks are saved with one segment (trkseg tag).

If you look for more powerfull GPX editors, take a look at :
- [Viking](https://sourceforge.net/projects/viking/) which is the best IMHO
- [QLandKarteGT](https://bitbucket.org/kiozen/qlandkarte-gt)
- [QMapShack](https://bitbucket.org/maproom/qmapshack/wiki/Home)
- [JOSM](https://josm.openstreetmap.de/)

GpxEdit :
- allows you to add extra symbols in Nextcloud(\*) admin settings (section : additional)
- works with server-side encryption.
- works with shared files.
- loads GPX, KML, unicsv CSV, geotagged JPG files
- loads tracks, routes and waypoints
- saves tracks, routes and waypoints
- supports waypoint symbols
- uses [Leaflet.Draw](https://github.com/Leaflet/Leaflet.draw) amazing plugin
- uses many other Leaflet plugins like Minimap, Sidebar2, MeasureControl, MousePositionControl
- uses JQuery

Any feedback will be appreciated.

If you want to help to translate this app in your language, take the english=>french files in "l10n" directory as examples.

(\*) : If you run Owncloud, you still can add extra symbols by putting png files in /path/to/owncloud/data/gpxedit/symbols/ folder.
