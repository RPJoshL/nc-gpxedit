<div id="sidebar" class="sidebar">
<!-- Nav tabs -->
<ul class="sidebar-tabs" role="tablist">
<li class="active" title="<?php p($l->t('Folder and tracks selection')); ?>"><a href="#ho" role="tab"><i class="fa fa-bars"></i></a></li>
<li title="<?php p($l->t('Settings')); ?>"><a href="#settings" role="tab"><i class="fa fa-gear"></i></a></li>
<li title="<?php p($l->t('About GpxEdit')); ?>"><a href="#help" role="tab"><i class="fa fa-question"></i></a></li>
</ul>
<!-- Tab panes -->
<div class="sidebar-content active">
<div class="sidebar-pane active" id="ho">
    <form name="choosedir" method="get" action="?">
    <div id="logofolder">
        <div id="logo">
            <!--p align="center"><img src="gpxpod.png"/></p-->
            <div>
            <p>v
<?php
p($_['gpxedit_version']);
?>
            </p>
            </div>
        </div>
        <div id="folderdiv">
        <label for="subfolderselect"><?php p($l->t('Folder')); ?> :</label>
            <select name="subfolder" id="subfolderselect">
            <option style="color:red; font-weight:bold"><?php p($l->t('Choose a folder')); ?></option>
<?php

// populate select options
if (count($_['dirs']) > 0){
    foreach($_['dirs'] as $dir){
        echo '<option>';
        p($dir);
        echo '</option>'."\n";
    }
}

?>
            </select>
        </div>
<?php

if (count($_['dirs']) === 0){
    echo '<p id="nofolder">';
    p($l->t('No gpx file found'));
    echo '</p><p id="nofoldertext">';
    p($l->t('You should have at least one gpx/kml/tcx file in your files'));
    echo '.</p>';
}

?>
    </div>
    <div style="clear:both"></div>
    </form>
    <hr/>
    <button id="saveButton">Save</button>
    <div style="clear:both"></div>
    <hr/>
<?php

echo '<p id="username" style="display:none">';
p($_['username']);
echo '</p>'."\n";

?>
</div>
<div class="sidebar-pane" id="settings">
<h1 class="sectiontitle"><?php p($l->t('Settings and extra actions')); ?></h1>
<hr/>
<br/>
<div id="filtertabtitle">
    <h3 class="sectiontitle"><?php p($l->t('Filters')); ?></h3>
    <button id="clearfilter" class="filterbutton">
        <i class="fa fa-trash" aria-hidden="true" style="color:red;"></i>
        <?php p($l->t('Clear')); ?>
    </button>
    <button id="applyfilter" class="filterbutton">
        <i class="fa fa-check" aria-hidden="true" style="color:green;"></i>
        <?php p($l->t('Apply')); ?>
    </button>
</div>
<br/>
<br/>
<ul id="filterlist" class="disclist">
    <li>
        <b><?php p($l->t('Date')); ?></b><br/>
        <?php p($l->t('min')); ?> : <input type="text" id="datemin"><br/>
        <?php p($l->t('max')); ?> : <input type="text" id="datemax">
    </li>
    <li>
        <b><?php p($l->t('Distance (m)'));?></b><br/>
        <?php p($l->t('min')); ?> : <input id="distmin"><br/>
        <?php p($l->t('max')); ?> : <input id="distmax">
    </li>
    <li>
        <b><?php p($l->t('Cumulative elevation gain (m)')); ?></b><br/>
        <?php p($l->t('min')); ?> : <input id="cegmin"><br/>
        <?php p($l->t('max')); ?> : <input id="cegmax">
    </li>
</ul>
<br/>
<hr/>
<br/>
    <h3 class="sectiontitle"><?php p($l->t('Custom tile servers')); ?></h3>
    <br/>
    <div id="tileserveradd">
        <?php p($l->t('Server name (for example \'my custom server\')')); ?> :
        <input type="text" id="tileservername"><br/>
        <?php p($l->t('Server url (\'http://tile.server.org/cycle/{z}/{x}/{y}.png\')')); ?> :
        <input type="text" id="tileserverurl"><br/>
        <button id="addtileserver"><i class="fa fa-plus-circle" aria-hidden="true" style="color:green;"></i> <?php p($l->t('Add')); ?></button>
    </div>
    <br/>
    <div id="tileserverlist">
        <h2><?php p($l->t('Your servers')); ?></h2>
        <ul class="disclist">
<?php
if (count($_['tileservers']) > 0){
    foreach($_['tileservers'] as $name=>$url){
        echo '<li name="';
        p($name);
        echo '" title="';
        p($url);
        echo '">';
        p($name);
        echo '<button><i class="fa fa-trash" aria-hidden="true" style="color:red;"></i> ';
        p($l->t('Delete'));
        echo '</button></li>';
    }
}
?>
        </ul>
    </div>

    <br/>
    <hr/>
    <br/>
    <h3 class="sectiontitle"><?php p($l->t('Python output')); ?></h3>
    <p id="python_output" ></p>
    <br/>
    <hr/>
    <br/>
    <h3 class="sectiontitle"><?php p($l->t('Clean files')); ?></h3>
    <button id="cleanall"><i class="fa fa-trash" aria-hidden="true" style="color:red;"></i> <?php p($l->t('Delete all markers and geojson files')); ?></button>
    <button id="clean"><i class="fa fa-trash" aria-hidden="true" style="color:red;"></i> <?php p($l->t('Delete markers and geojson files for existing gpx')); ?></button>
    <div id="clean_results"></div>
    <div id="deleting"><p>
        <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
        <?php p($l->t('deleting')); ?></p>
    </div>
    <div id="linkdialog" style="display:none;" title="Public link">
        <label id="linklabel" for="linkinput"></label>
        <br/>
        <input id="linkinput" type="text"></input>
    </div>

</div>
<div class="sidebar-pane" id="help">
    <h1 class="sectiontitle"><?php p($l->t('About GpxPod')); ?></h1>
    <hr/><br/>
    <h3 class="sectiontitle"><?php p($l->t('Shortcuts')); ?> :</h3>
    <ul class="disclist">
        <li><b>&lt;</b> : <?php p($l->t('toggle sidebar')); ?></li>
        <li><b>!</b> : <?php p($l->t('toggle minimap')); ?></li>
        <li><b>œ</b> or <b>²</b> : <?php p($l->t('toggle search')); ?></li>
    </ul>
    <br/><hr/><br/>
    <h3 class="sectiontitle"><?php p($l->t('Features')); ?> :</h3>
    <ul class="disclist">
        <li>View :
        <ul class="circlist">
        <li>Click on marker cluster to zoom in.</li>
        <li>Click on track line or track marker to show popup with track stats
        and a link to draw track elevation profile.</li>
        <li>In main sidebar tab, the table lists all track that fits into
        current map bounds. This table is kept up to date when you zoom or move.</li>
        <li>Sidebar table columns are sortable.</li>
        <li>In sidebar table and track popup, click on track links to download
        the GPX file.</li>
        <li>"Transparency" option : enable sidebar transparency when hover on
        table rows to display track overviews.</li>
        <li>"Display markers" option : hide all map markers. Sidebar table still
        lists available tracks in current map bounds.</li>
        <li>Auto popup : toggle popup opening when drawing a track</li>
        <li>Auto zoom : toggle zoom when changing folder or drawing a track</li>
        <li>Dynamic table : Always show all tracks if disabled. Otherwise
        , update the table when zooming or moving the map view.</li>
        <li>Track coloration : color each track segment depending on elevation or speed or slope.</li>
        <li>Browser timezone detection.</li>
        <li>Manual timezone setting.</li>
        <li>Several criterias to list tracks in sidebar table</li>
        <li>Filter visible tracks by length, date, cumulative elevation gain.</li>
        <li>Add personnal custom tile servers.</li>
        </ul>
        </li>

        <li>Share :
        <ul class="circlist">
        <li>Share track : In sidebar table, [p] link near the track name is a public link which
        works only if the track (or one of its parent directories) is shared in
        "Files" app with public without password.</li>
        <li>Share folder : Near the selected folder, the [p] link is a public link to currently selected folder.
        This link will work only if the folder is shared in "Files" app with public without password.</li>
        </ul>
        </li>

        <li>Other :
        <ul class="circlist">
        <li>Ability to clean old files produced by old GpxPod versions.</li>
        <li>Pre-process tracks with SRTM.py (if installed and found
        on server's system) to correct elevations.
        This can be done on a single track (with a link in track popup) or on a whole folder (with scan type).</li>
        <li>Convert KML and TCX files to gpx if GpsBabel is found on server's system.</li>
        </ul>
        </li>

        <li>Many leaflet plugins are active :
            <ul class="circlist">
                <li>Markercluster</li>
                <li>Elevation (modified to display time when hover on graph)</li>
                <li>Sidebar-v2</li>
                <li>Control Geocoder (search in nominatim DB)</li>
                <li>Minimap (bottom-left corner of map)</li>
                <li>MousePosition</li>
            </ul>
        </li>
    </ul>

    <br/><hr/><br/>
    <h3 class="sectiontitle"><?php p($l->t('Documentation')); ?></h3>
    <a class="toplink" target="_blank" href="https://gitlab.com/eneiluj/gpxpod-oc/wikis/home">
    <i class="fa fa-gitlab" aria-hidden="true"></i>
    Project wiki
    </a>
    <br/>

    <br/><hr/><br/>
    <h3 class="sectiontitle"><?php p($l->t('Source management')); ?></h3>
    <ul class="disclist">
        <li><a class="toplink" target="_blank" href="https://gitlab.com/eneiluj/gpxpod-oc">
        <i class="fa fa-gitlab" aria-hidden="true"></i>
        Gitlab project main page</a></li>
        <li><a class="toplink" target="_blank" href="https://gitlab.com/eneiluj/gpxpod-oc/issues">
        <i class="fa fa-gitlab" aria-hidden="true"></i>
        Gitlab project issue tracker</a></li>
    </ul>

    <br/><hr/><br/>
    <h3 class="sectiontitle"><?php p($l->t('Authors')); ?> :</h3>
    <ul class="disclist">
        <li>Julien Veyssier</li>
        <li>Fritz Kleinschroth (german translation)</li>
    </ul>

</div>
</div>
</div>
<!-- ============= MAP DIV =============== -->
<div id="map" class="sidebar-map"></div>

