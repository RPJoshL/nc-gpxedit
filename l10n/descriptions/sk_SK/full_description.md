# Aplikácia GpxEdit pre Nextcloud

Jednoduchá Nextcloud aplikácia pre otváranie, úpravu a ukladanie GPX súborov na interaktívnej mape. Môžete otvárať/ukladať súbory z vášho Nextcloud úložiska súborov. Podporované sú súbory GPX, KML, CSV (unicsv formát) a geograficky označené JPG. JPG súbory sú načítavané ako body trasy. Súbory môžu byť otvorené v rozhraní GpxEdit alebo v aplikácii Súbory.

Ak chcete pomôcť s prekladom aplikácie do vášho jazyka navštívte [GpxEdit Crowdin project](https://crowdin.com/project/gpxedit).

Nie je to skvelý GPX editor.

Čo sa ukladá:
- metadáta
    - názov
    - url odkaz
    - text odkazu
- trasy
    - názov
    - komentár
    - popis
    - body
        - súradnice
        - nadmorská výška (zachovajú sa načítané hodnoty)
        - čas (zachovajú sa načítané hodnoty)
- bod trasy
    - súradnice
    - názov
    - komentár
    - popis
    - znak
    - nadmorská výška (zachovajú sa načítané hodnoty)
    - čas (zachovajú sa načítané hodnoty)

GpxEdit does load/save time data. Elevation data is loaded and saved but every new waypoint/track/trackpoint added by user actions in GpxEdit will have neither elevation nor time data. There is an "approximate" option to set elevation to new points if surrounding points have elevation data.

Tracks are saved with one segment (trkseg tag).

GpxEdit :
- vám umožní pridať vlastné znaky v nastaveniach administrácie (sekcia : doplnkové)
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

Táto aplikácia je testovaná pre Nextcloud 14 a Firefox a Chromium.

Ocením akúkoľvek spätnú väzbu.

## Inštalácia

Pozrite si [AdminDoc](https://gitlab.com/eneiluj/gpxedit-oc/wikis/admindoc) pre podrobnosti o inštalácii, integrácii do aplikácie "Súbory" a nastavenia administrácie GpxEdit.

## Alternatívy

Ak hľadáte výkonnejšie editory GPX súborov, pozrite sa na:
- [Viking](https://sourceforge.net/projects/viking/), ktorý je podľa mňa najlepší
- [QLandKarteGT](https://bitbucket.org/kiozen/qlandkarte-gt)
- [QMapShack](https://bitbucket.org/maproom/qmapshack/wiki/Home)
- [JOSM](https://josm.openstreetmap.de/)

