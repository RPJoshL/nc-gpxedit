# Application Nextcloud GpxEdit

Application Nextcloud simple pour charger, éditer et enregistrer des fichiers GPX sur une carte interactive. Vous pouvez charger/enregistrer des fichiers depuis votre stockage de fichiers Nextcloud. GPX, KML, CSV (format unicsv) et JPG géotaggué sont pris en charge pour le chargement. Les fichiers JPG sont chargés en tant que waypoints. Les fichiers peuvent être chargés dans l'interface GpxEdit ou dans l'application Fichiers.

Allez sur [le projet GpxEdit sur Crowdin](https://crowdin.com/project/gpxedit) si vous voulez aider à traduire cette application dans votre langue.

Ce n'est pas un éditeur GPX parfait.

Ce qui est sauvegardé :
- métadonnées
    - nom
    - url du lien
    - texte du lien
- traces
    - nom
    - commentaire
    - description
    - points
        - coordonnées
        - altitude (conserve seulement les valeurs chargées)
        - temps (conserve seulement les valeurs chargées)
- waypoint
    - coordonnées
    - nom
    - commentaire
    - description
    - symbole
    - altitude (conserve seulement les valeurs chargées)
    - temps (conserve seulement les valeurs chargées)

GpxEdit charge/sauve les données temporelles. Les données d'altitude sont chargées et enregistrées, mais chaque nouveau waypoint/track/trackpoint ajouté par les actions de l'utilisateur dans GpxEdit n'aura ni altitude ni données temporelles. Il y a une option « approximative » pour définir l'altitude à de nouveaux points si les points environnants ont des données d'altitude.

Les traces sont enregistrées avec un segment (balise trkseg).

GpxEdit :
- vous permet d'ajouter des symboles supplémentaires dans les paramètres d'administration (section : paramètres additionnels)
- fonctionne avec le chiffrement côté serveur.
- fonctionne avec des fichiers partagés.
- charge des fichiers GPX, KML, unicsv CSV, JPG géotaggés (il n'y a plus de dépendance à Gpsbabel)
- charge les traces, les routes et les waypoints
- enregistre les traces, les routes et les waypoints
- prend en charge les symboles de waypoint
- utilise les remarquables plugins Leaflet [Leaflet.Draw](https://github.com/Leaflet/Leaflet.draw) et [Leaflet.draw.plus](https://github.com/Dominique92/Leaflet.draw.plus)
- utilise beaucoup d'autres plugins Leaflet comme Minimap, Sidebar2, MeasureControl, MousePositionControl
- ajoute la possibilité d'éditer les fichiers .gpx directement depuis l'application "Fichiers"
- est capable de couper des lignes en deux

Cette application est testée sur Nextcloud 14 avec Firefox et Chromium.

Tout retour sera apprécié.

## Installation

Voir l' [AdminDoc](https://gitlab.com/eneiluj/gpxedit-oc/wikis/admindoc) pour les détails sur l'installation.

## Alternatives

Si vous cherchez des éditeurs GPX plus puissants, jetez un coup d'œil à :
- [Viking](https://sourceforge.net/projects/viking/) qui est le meilleur à mon avis
- [QLandKarteGT](https://bitbucket.org/kiozen/qlandkarte-gt)
- [QMapShack](https://bitbucket.org/maproom/qmapshack/wiki/Home)
- [JOSM](https://josm.openstreetmap.de/)

