# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
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
- notifications on tile layer add/remove

### Changed

### Fixed
- app is now compliant to occ check-code
- jshint and jslint in da place

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
