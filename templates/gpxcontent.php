<div id="sidebar" class="sidebar">
<!-- Nav tabs -->
<ul class="sidebar-tabs" role="tablist">
<li class="active" title="<?php p($l->t('Load and save files')); ?>"><a href="#ho" role="tab"><i class="fa fa-bars"></i></a></li>
<li title="<?php p($l->t('Options')); ?>"><a href="#gpxeditsettings" role="tab"><i class="fa fa-gear"></i></a></li>
<li title="<?php p($l->t('About GpxEdit')); ?>"><a href="#help" role="tab"><i class="fa fa-question"></i></a></li>
</ul>
<!-- Tab panes -->
<div class="sidebar-content active">
<div class="sidebar-pane active" id="ho">
    <div id="loaddiv">
        <div id="logofolder">
            <div id="logo">
                <!--p align="center"><img src="gpxedit.png"/></p-->
                <div>
                <p id="versionnumber">v
    <?php
    p($_['gpxedit_version']);
    ?>
                </p>
                </div>
            </div>
        </div>
        <button id="loadButton"><i class="fa fa-file-o"></i> <?php p($l->t('Load file'));?></button>
        <button id="loadFolderButton"><i class="fa fa-folder-open-o"></i> <?php p($l->t('Load directory'));?></button>
        <select id="loadtypeselect">
            <option value="all">all</option>
            <option value=".jpg">jpg</option>
            <option value=".gpx">gpx</option>
            <option value=".kml">kml</option>
            <option value=".csv">csv</option>
        </select>
    </div>
    <div style="clear:both"></div>
    <hr/>
    <h2 id="savetitle"><?php p($l->t('Save'));?></h2>
    <div>
        <label id="saveNameLabel"><?php p($l->t('File name'));?> :</label><br/>
        <input id="saveName" type="text"></input>
    </div>
    <div style="clear:both"></div>
    <label><?php p($l->t('Description (optional)'));?> :</label><br/>
    <textarea id="desctext"></textarea>
    <button id="saveButton"><i class="fa fa-save"></i> <?php p($l->t('Choose directory and save'));?></button>
    <div style="clear:both"></div>
    <hr/>
    <button id="clearButton"><i class="fa fa-bomb"></i> <?php p($l->t('Clear map'));?></button>
    <div id="saved"><p>
        <i class="fa fa-save fa-spin fa-3x fa-fw"></i>
        <b id="content"></b></p>
    </div>
    <div id="failed"><p>
        <i class="fa fa-save fa-spin fa-3x fa-fw"></i>
        <b id="content"></b></p>
    </div>
	<div id="loading"><p>
		<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
		<?php p($l->t('loading file')); ?>&nbsp;(<i id="loadingpc"></i> %)</p>
	</div>
	<div id="exporting"><p>
		<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
		<?php p($l->t('exporting file to gpx')); ?>&nbsp;</p>
	</div>
	<div id="saving"><p>
		<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
		<?php p($l->t('saving file')); ?>&nbsp;(<i id="savingpc"></i> %)</p>
	</div>
<?php

echo '<p id="username" style="display:none">';
p($_['username']);
echo '</p>'."\n";
echo '<ul id="extrasymbols" style="display:none">';
foreach($_['extrasymbols'] as $symbol){
    echo '<li name="';
    p($symbol['name']);
    echo '">';
    p($symbol['smallname']);
    echo '</li>';
}
echo '</ul>'."\n";

?>
</div>
<div class="sidebar-pane" id="gpxeditsettings">
<h1 class="sectiontitle"><?php p($l->t('Options')); ?></h1>
<hr/>
<br/>
<div title="<?php p($l->t('Default symbol for waypoints when value is not set'));?>">
    <label><?php p($l->t('Waypoint style'));?>:</label>
    <select id="markerstyleselect">
    </select>
</div>
<div>
    <label><?php p($l->t('Tooltip'));?>:</label>
    <select id="tooltipstyleselect">
        <option value="h"><?php p($l->t('on hover')); ?></option>
        <option value="p"><?php p($l->t('permanent')); ?></option>
    </select>
</div>
<div title="<?php p($l->t('Use defined symbols instead of default symbol')); ?>">
    <input id="symboloverwrite" type="checkbox" checked></input>
    <label for="symboloverwrite"><?php p($l->t('Use defined symbols')); ?></label>
</div>
<div>
    <input type="checkbox" id="clearbeforeload" checked></input>
    <label for="clearbeforeload"><?php p($l->t('Clear map before loading'));?></label>
</div>
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

</div>
<div class="sidebar-pane" id="help">
    <h1 class="sectiontitle"><?php p($l->t('About GpxEdit')); ?></h1>
    <hr/><br/>
    <h3 class="sectiontitle"><?php p($l->t('Shortcuts')); ?> :</h3>
    <ul class="disclist">
        <li><b>&lt;</b> : <?php p($l->t('toggle sidebar')); ?></li>
        <li><b>!</b> : <?php p($l->t('toggle minimap')); ?></li>
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

