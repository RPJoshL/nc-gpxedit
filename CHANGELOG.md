# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
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

### Changed
- detach marker and tooltip styling option

### Fixed
- remove $.parseXML, apparently useless and producing errors
  [#5](https://gitlab.com/eneiluj/gpxedit-oc/issues/5) @eneiluj
