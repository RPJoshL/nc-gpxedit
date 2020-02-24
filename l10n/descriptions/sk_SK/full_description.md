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

GpxEdit načítava/ukladá časové údaje. Údaje nadmorskej výšky sú načítavané a ukladané, ale každý nový bod trasy/trasa pridaná akciami používateľa v GpxEdit nebude mať ani nadmorskú výšku ani časové údaje. K dispozícii je voľba "priemerovania" pre nastavenie nadmorskej výšky novým bodom, ak okolité body majú údaje o nadmorskej výške.

Trasy sú ukladané v jednom segmente (trkseg tag).

GpxEdit :
- vám umožní pridať vlastné znaky v nastaveniach administrácie (sekcia : doplnkové);
- pracuje so šifrovaním na strane servera;
- pracuje so zdieľanými súbormi;
- otvára GPX, KML, unicsv CSV, lokalizované JPG súbory (už bez závislosti na Gpsbabel);
- otvára trasy, cesty, body trasy;
- ukladá trasy, cesty, body trasy;
- podporuje symboly bodov trasy;
- používa skvelé zásuvné moduly [Leaflet.Draw](https://github.com/Leaflet/Leaflet.draw) a [Leaflet.draw.plus](https://github.com/Dominique92/Leaflet.draw.plus);
- používa množstvo ďalších Leaflet zásuvných modulov ako Minimap, Sidebar2, MeasureControl, MousePositionControl;
- pridáva možnosť upraviť .gpx súbory priamo z aplikácie "Súbory";
- dokáže rozdeliť čiary na dve.

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

