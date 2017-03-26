# GpxEdit owncloud/nextcloud application

Simple Owncloud/Nextcloud app to load, edit and save GPX files on an interactive map.
You can load/save files from your Owncloud/Nextcloud file storage.
GPX, KML, CSV (unicsv format) and geotagged JPG are supported for loading. JPG files are loaded as waypoints.
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

GpxEdit :
- allows you to add extra symbols in Nextcloud(\*) admin settings (section : additional)
- works with server-side encryption.
- works with shared files.
- loads GPX, KML, unicsv CSV, geotagged JPG files (requires Gpsbabel to convert files and import pictures)
- loads tracks, routes and waypoints
- saves tracks, routes and waypoints
- supports waypoint symbols
- uses [Leaflet.Draw](https://github.com/Leaflet/Leaflet.draw) amazing plugin
- uses many other Leaflet plugins like Minimap, Sidebar2, MeasureControl, MousePositionControl
- uses JQuery
- adds possibility to edit .gpx files directly from the "Files" app

Any feedback will be appreciated.

If you want to help to translate this app in your language, take the english=>french files in "l10n" directory as examples.

(\*) : If you run Owncloud, you still can add extra symbols by putting png files in /path/to/owncloud/data/gpxedit/symbols/ folder.

## Donation

I develop this app during my free time. You can make a donation to me on Paypal. [Click HERE to make a donation](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=66PALMY8SF5JE) (you don't need a paypal account)

## Installation

Check the [AdminDoc](https://gitlab.com/eneiluj/gpxedit-oc/wikis/admindoc) for installation details, integration in "Files" app and GpxEdit admin settings.

## Releases

[Here](https://gitlab.com/eneiluj/gpxedit-oc/wikis/home#releases) are the GpxEdit releases.

## Alternatives

If you look for more powerfull GPX editors, take a look at :
- [Viking](https://sourceforge.net/projects/viking/) which is the best IMHO
- [QLandKarteGT](https://bitbucket.org/kiozen/qlandkarte-gt)
- [QMapShack](https://bitbucket.org/maproom/qmapshack/wiki/Home)
- [JOSM](https://josm.openstreetmap.de/)
