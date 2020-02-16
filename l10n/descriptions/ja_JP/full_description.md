# GpxEdit Nextcloud application

Simple Nextcloud app to load, edit and save GPX files on an interactive map. You can load/save files from your Nextcloud file storage. GPX, KML, CSV (unicsv format) and geotagged JPG are supported for loading. JPG files are loaded as waypoints. Files can be loaded in GpxEdit interface or in Files app.

Go to [GpxEdit Crowdin project](https://crowdin.com/project/gpxedit) if you want to help to translate this app in your language.

This is not a perfect GPX editor.

What's saved :
- metadata
    - name
    - link url
    - link text
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

GpxEdit does load/save time data. Elevation data is loaded and saved but every new waypoint/track/trackpoint added by user actions in GpxEdit will have neither elevation nor time data. There is an "approximate" option to set elevation to new points if surrounding points have elevation data.

Tracks are saved with one segment (trkseg tag).

GpxEdit :
- allows you to add extra symbols in admin settings (section : additional)
- works with server-side encryption.
- works with shared files.
- loads GPX, KML, unicsv CSV, geotagged JPG files (no more Gpsbabel dependency)
- loads tracks, routes and waypoints
- saves tracks, routes and waypoints
- supports waypoint symbols
- uses [Leaflet.Draw](https://github.com/Leaflet/Leaflet.draw) and [Leaflet.draw.plus](https://github.com/Dominique92/Leaflet.draw.plus) amazing plugins
- uses many other Leaflet plugins like Minimap, Sidebar2, MeasureControl, MousePositionControl
- adds possibility to edit .gpx files directly from the "Files" app
- is able to cut lines in two

This app is tested on Nextcloud 14 with Firefox and Chromium.

Any feedback will be appreciated.

## Installation

Check the [AdminDoc](https://gitlab.com/eneiluj/gpxedit-oc/wikis/admindoc) for installation details, integration in "Files" app and GpxEdit admin settings.

## Alternatives

If you look for more powerfull GPX editors, take a look at :
- [Viking](https://sourceforge.net/projects/viking/) which is the best IMHO
- [QLandKarteGT](https://bitbucket.org/kiozen/qlandkarte-gt)
- [QMapShack](https://bitbucket.org/maproom/qmapshack/wiki/Home)
- [JOSM](https://josm.openstreetmap.de/)

