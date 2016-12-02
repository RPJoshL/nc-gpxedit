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
    </div>
    <div style="clear:both"></div>
    </form>
    <hr/>
    <h2 id="loadtitle">Load <b id="loadoptiontoggle"><i class="fa fa-expand"></i></b></h2>
    <div id="loaddiv" style="display:none;">
        <p>Select a gpx file to load it on the map</p><br/>
        <div id="loadtree"></div>
    </div>
    <hr/>
    <h2 id="savetitle">Save <b id="saveoptiontoggle"><i class="fa fa-expand"></i></b></h2>
    <div id="savediv" style="display:none;">
        <p>Select a folder, set a name and click "Save" button</p><br/>
        <div id="savetree"></div>
        <input id="saveName"></input>
        <button id="saveButton">Save</button>
    </div>
    <div style="clear:both"></div>
    <hr/>
    <button id="clearButton">Clear map</button>
    <div id="saved"><p>
        <i class="fa fa-save fa-spin fa-3x fa-fw"></i>
        <b id="content"><?php p($l->t('File saved')); ?></b></p>
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

