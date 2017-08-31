# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
### Added
- display line total distance during drawing
  [#34](https://gitlab.com/eneiluj/gpxedit-oc/issues/34) @stevenhorner

### Changed
- update leaflet to 1.2.0

### Fixed
- automatically add .gpx extension to save name
  [#33](https://gitlab.com/eneiluj/gpxedit-oc/issues/33) @klakla2
- reduce DB table name size to pass NC check
- fix white square size when drawing a polyline under firefox 57

## 0.0.7 – 2017-06-27
### Added
- add option to approximate elevation of new point
  [#18](https://gitlab.com/eneiluj/gpxedit-oc/issues/18) @TheTiPi
- support for ownCloud admin settings
- dynamic URL and page title change after loading file or folder
- support for WMS tile and overlay servers. base and user servers
  [#22](https://gitlab.com/eneiluj/gpxedit-oc/issues/22) @a15n
- add new metadata fields : track name, link url, link text
  [#26](https://gitlab.com/eneiluj/gpxedit-oc/issues/26) @manudinovi
  [#30](https://gitlab.com/eneiluj/gpxedit-oc/issues/30) @manudinovi

### Changed
- change way to cut lines, hover the middle marker and press Del
  [#17](https://gitlab.com/eneiluj/gpxedit-oc/issues/17) @TheTiPi
- update moment timezone js
- no more GpsBabel dependency, conversion is now done in Php
  [#29](https://gitlab.com/eneiluj/gpxedit-oc/issues/29) @poVoq
- make custom tile server blocks foldable
  [#27](https://gitlab.com/eneiluj/gpxedit-oc/issues/27) @a15n

### Fixed
- modify cloneLatLng to avoid alt and time loss when L.draw makes layer backups
  [#16](https://gitlab.com/eneiluj/gpxedit-oc/issues/16) @TheTiPi

## 0.0.6 – 2017-05-16
### Added
- add checkTranslations.py script
- new feature : cut a line in two with click on middle marker
  [#14](https://gitlab.com/eneiluj/gpxedit-oc/issues/14) @klakla2
- add personal overlay server management

### Changed
- bring layer to front on hover
- use CSS grid
- use L.draw.plus
- put all base tile servers in a php file

### Fixed
- adapt CSS to Nextcloud 12, still works with Owncloud 9 and Nextcloud 11
- update french translations

## 0.0.5 – 2017-04-05
### Added
- add lat/lng fields in waypoint popup to view/edit coordinates with precise values
  [#10](https://gitlab.com/eneiluj/gpxedit-oc/issues/10) @eneiluj
- show animation during exporting and saving
- show saving/loading ajax percentage
- save/restore tilelayer
- animations when add/remove tile servers
- few french translations
- show exporting and saving animation
- progress percentage for load/save
- save/restore current tile layer
- integration in "Files" and "File sharing" context menu for .gpx files
  [#11](https://gitlab.com/eneiluj/gpxedit-oc/issues/11) @rugk
- add directory context menu option in files app : load in GpxEdit
- notifications on tile layer add/remove
- makefile signs the app code

### Changed
- numeric/alphabetic sort of layers by name in gpx file
- layer without name are written with empty name
- change favicon background color

### Fixed
- app is now compliant to occ check-code
- jshint and jslint in da place
- do not put @NoCSRFRequired everywhere in controllers
- replace decodeURI by decodeURIComponent
  [#12](https://gitlab.com/eneiluj/gpxedit-oc/issues/12) @gezgez
- remove escapeshellcmd to avoid errors with special char in file name

## 0.0.4 – 2017-01-17
### Added
- loading animation, interrupt current ajax if another one is launched
  [#9](https://gitlab.com/eneiluj/gpxedit-oc/issues/9) @eneiluj

### Changed
- display question mark if symbol is unknown
- remove search key shortcut
  [#8](https://gitlab.com/eneiluj/gpxedit-oc/issues/8) @eneiluj

## 0.0.3 – 2016-12-16
### Added
- file description field
- admin setting section to add extra symbols
- french translation

### Changed
- keep time information in loaded tracks/routes/waypoints, written in saved files
- improve load and save : use OC dialogs instead of jqueryfiletree

### Fixed
- send referrer, IGN tiles work now

## 0.0.2 – 2016-12-08
### Added
- ability to load kml and csv (unicsv format) files
  [#1](https://gitlab.com/eneiluj/gpxedit-oc/issues/1) @eneiluj
- ability to load jpg files as markers, conversion with gpsbabel like kml and csv
  [#3](https://gitlab.com/eneiluj/gpxedit-oc/issues/3) @eneiluj
- custom tile server management
  [#4](https://gitlab.com/eneiluj/gpxedit-oc/issues/4) @eneiluj
- option to change marker style and tooltip visibility
  [#2](https://gitlab.com/eneiluj/gpxedit-oc/issues/2) @eneiluj
- automatic save/restore options values
  [#7](https://gitlab.com/eneiluj/gpxedit-oc/issues/7) @eneiluj
- load/save/edit symbol value (sym)
  [#6](https://gitlab.com/eneiluj/gpxedit-oc/issues/6) @eneiluj
- symbol selects include icons
  [#6](https://gitlab.com/eneiluj/gpxedit-oc/issues/6) @eneiluj
- gpx route parsing, save it as route
- GET param to load file on page load

### Changed
- detach marker and tooltip styling option
- every symbol can be default marker style choices
  [#6](https://gitlab.com/eneiluj/gpxedit-oc/issues/6) @eneiluj
- line style, control text

### Fixed
- remove $.parseXML, apparently useless and producing errors
  [#5](https://gitlab.com/eneiluj/gpxedit-oc/issues/5) @eneiluj
