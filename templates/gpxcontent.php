<div id="sidebar" class="sidebar">
<!-- Nav tabs -->
<ul class="sidebar-tabs" role="tablist">
<li class="active" title="<?php p($l->t('Folder and tracks selection')); ?>"><a href="#ho" role="tab"><i class="fa fa-bars"></i></a></li>
<li title="<?php p($l->t('About GpxEdit')); ?>"><a href="#help" role="tab"><i class="fa fa-question"></i></a></li>
</ul>
<!-- Tab panes -->
<div class="sidebar-content active">
<div class="sidebar-pane active" id="ho">
    <form name="choosedir" method="get" action="?">
    <div id="logofolder">
        <div id="logo">
            <!--p align="center"><img src="gpxedit.png"/></p-->
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
    <input id="getPath"></input>
    <button id="getButton">Load</button>
    <hr/>
    <input id="savePath"></input>
    <button id="saveButton">Save</button>
    <div style="clear:both"></div>
    <hr/>
    <button id="clearButton">Clear map</button>
    <div id="saved"><p>
        <i class="fa fa-save fa-spin fa-3x fa-fw"></i>
        <?php p($l->t('File saved')); ?>&nbsp;</p>
    </div>
<?php

echo '<p id="username" style="display:none">';
p($_['username']);
echo '</p>'."\n";

?>
</div>
<div class="sidebar-pane" id="help">
    <h1 class="sectiontitle"><?php p($l->t('About GpxEdit')); ?></h1>
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
        <li>Many leaflet plugins are active :
            <ul class="circlist">
                <li>Sidebar-v2</li>
                <li>Control Geocoder (search in nominatim DB)</li>
                <li>Minimap (bottom-left corner of map)</li>
                <li>MousePosition</li>
                <li>Draw</li>
                <li>MeasureControl</li>
            </ul>
        </li>
    </ul>

    <br/><hr/><br/>
    <h3 class="sectiontitle"><?php p($l->t('Documentation')); ?></h3>
    <a class="toplink" target="_blank" href="https://gitlab.com/eneiluj/gpxedit-oc/wikis/home">
    <i class="fa fa-gitlab" aria-hidden="true"></i>
    Project wiki
    </a>
    <br/>

    <br/><hr/><br/>
    <h3 class="sectiontitle"><?php p($l->t('Source management')); ?></h3>
    <ul class="disclist">
        <li><a class="toplink" target="_blank" href="https://gitlab.com/eneiluj/gpxedit-oc">
        <i class="fa fa-gitlab" aria-hidden="true"></i>
        Gitlab project main page</a></li>
        <li><a class="toplink" target="_blank" href="https://gitlab.com/eneiluj/gpxedit-oc/issues">
        <i class="fa fa-gitlab" aria-hidden="true"></i>
        Gitlab project issue tracker</a></li>
    </ul>

    <br/><hr/><br/>
    <h3 class="sectiontitle"><?php p($l->t('Authors')); ?> :</h3>
    <ul class="disclist">
        <li>Julien Veyssier</li>
    </ul>

</div>
</div>
</div>
<!-- ============= MAP DIV =============== -->
<div id="map" class="sidebar-map"></div>

