# GpxEdit owncloud/nextcloud application

If you want to help to translate this app in your language, take the english=>french files in "l10n" directory as examples.

Simple Owncloud/Nextcloud app to load, edit and save GPX file on an interactive map.
You can load/save file from your Owncloud/Nextcloud file storage.
There is a file explorer inside the app interface to select a file to load and a folder to save what is currently on the map.

This is not a perfect GPX editor.

**WARNING** GpxEdit does not save any elevation/time data, even if you loaded a gpx file which has elevation/time information. Keep that in mind if you overwrite a file.

Markers are saved as waypoints. Lines (polylines) are saved as tracks with one segment.
It is possible to set a name, description and comment for each marker and line in a popup.
These information are saved in the gpx file.

If you want more powerfull GPX editors, take a look at :
- [Viking](https://sourceforge.net/projects/viking/) which is the best IMHO
- [QLandKarteGT](https://bitbucket.org/kiozen/qlandkarte-gt)
- [QMapShack](https://bitbucket.org/maproom/qmapshack/wiki/Home)
- [JOSM](https://josm.openstreetmap.de/)

GpxEdit :
- works with server-side encryption.
- works with shared files.
- uses [Leaflet.Draw](https://github.com/Leaflet/Leaflet.draw) amazing plugin
- uses many other Leaflet plugins like Minimap, Sidebar2, MeasureControl, MousePositionControl
- uses JQuery
- uses [JQuery File Tree](https://github.com/jqueryfiletree/jqueryfiletree) amazing file tree library

Any feedback will be appreciated.
