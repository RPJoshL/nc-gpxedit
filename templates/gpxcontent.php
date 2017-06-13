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
            <option value="all"><?php p($l->t('all files'));?></option>
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
echo '<ul id="basetileservers" style="display:none">';
foreach($_['basetileservers'] as $ts){
    echo '<li';
    foreach (Array('name', 'type', 'url', 'layers', 'version', 'format', 'opacity', 'transparent', 'minzoom', 'maxzoom', 'attribution') as $field) {
        if (array_key_exists($field, $ts)) {
            echo ' '.$field.'="';
            p($ts[$field]);
            echo '"';
        }
    }
    echo '></li>';
}
echo '</ul>'."\n";

?>
</div>
<div class="sidebar-pane" id="gpxeditsettings">
<h1 class="sectiontitle"><?php p($l->t('Options')); ?></h1>
<hr/>
<div id="optiontop">
    <label for="markerstyleselect" title="<?php p($l->t('Default symbol for waypoints when value is not set'));?>">
        <?php p($l->t('Waypoint style'));?>:
    </label>
    <select id="markerstyleselect">
    </select>
    <label for="tooltipstyleselect"><?php p($l->t('Tooltip'));?>:</label>
    <select id="tooltipstyleselect">
        <option value="h"><?php p($l->t('on hover')); ?></option>
        <option value="p"><?php p($l->t('permanent')); ?></option>
    </select>
    <label title="<?php p($l->t('Use defined symbols instead of default symbol')); ?>"
    for="symboloverwrite"><?php p($l->t('Use defined symbols')); ?></label>
    <input title="<?php p($l->t('Use defined symbols instead of default symbol')); ?>"
    id="symboloverwrite" type="checkbox" checked></input>
    <label for="clearbeforeload"><?php p($l->t('Clear map before loading'));?></label>
    <input type="checkbox" id="clearbeforeload" checked></input>
    <label for="approximateele"><?php p($l->t('Approximate new points elevations'));?></label>
    <input type="checkbox" id="approximateele"></input>
</div>
<hr/><br/>
    <h3 class="sectiontitle"><?php p($l->t('Custom tile servers')); ?></h3>
    <div id="tileserveradd">
        <p><?php p($l->t('Server name')); ?> :</p>
        <input type="text" id="tileservername" title="<?php p($l->t('For example : my custom server')); ?>"/>
        <p><?php p($l->t('Server url')); ?> :</p>
        <input type="text" id="tileserverurl" title="<?php p($l->t('For example : http://tile.server.org/cycle/{z}/{x}/{y}.png')); ?>"/>
        <p><?php p($l->t('Min zoom (1-20)')); ?> :</p>
        <input type="text" id="tileminzoom" value="1"/>
        <p><?php p($l->t('Max zoom (1-20)')); ?> :</p>
        <input type="text" id="tilemaxzoom" value="18"/>
        <button id="addtileserver"><i class="fa fa-plus-circle" aria-hidden="true" style="color:green;"></i> <?php p($l->t('Add')); ?></button>
    </div>
    <div id="tileserverlist">
        <h3><?php p($l->t('Your tile servers')); ?></h3>
        <ul class="disclist">
<?php
if (count($_['usertileservers']) > 0){
    foreach($_['usertileservers'] as $ts){
        echo '<li title="'.$ts['url'].'"';
        foreach (Array('servername', 'type', 'url', 'layers', 'version', 'format', 'opacity', 'transparent', 'minzoom', 'maxzoom', 'attribution') as $field) {
            if (array_key_exists($field, $ts)) {
                echo ' '.$field.'="';
                p($ts[$field]);
                echo '"';
            }
        }
        echo '>';
        p($ts['servername']);
        echo '&nbsp <button><i class="fa fa-trash" aria-hidden="true" style="color:red;"></i> ';
        p($l->t('Delete'));
        echo '</button></li>';
    }
}
?>
        </ul>
    </div>
<hr/><br/>
    <h3 class="sectiontitle"><?php p($l->t('Custom overlay tile servers')); ?></h3>
    <div id="overlayserveradd">
        <p><?php p($l->t('Server name')); ?> :</p>
        <input type="text" id="overlayservername" title="<?php p($l->t('For example : my custom server')); ?>"/>
        <p><?php p($l->t('Server url')); ?> :</p>
        <input type="text" id="overlayserverurl" title="<?php p($l->t('For example : http://overlay.server.org/cycle/{z}/{x}/{y}.png')); ?>"/>
        <p><?php p($l->t('Min zoom (1-20)')); ?> :</p>
        <input type="text" id="overlayminzoom" value="1"/>
        <p><?php p($l->t('Max zoom (1-20)')); ?> :</p>
        <input type="text" id="overlaymaxzoom" value="18"/>
        <label for="overlaytransparent"><?php p($l->t('Transparent')); ?> :</label>
        <input type="checkbox" id="overlaytransparent" checked/>
        <p><?php p($l->t('Opacity (0.0-1.0)')); ?> :</p>
        <input type="text" id="overlayopacity" value="0.4"/>
        <button id="addoverlayserver"><i class="fa fa-plus-circle" aria-hidden="true" style="color:green;"></i> <?php p($l->t('Add')); ?></button>
    </div>
    <div id="overlayserverlist">
        <h3><?php p($l->t('Your overlay tile servers')); ?></h3>
        <ul class="disclist">
<?php
if (count($_['useroverlayservers']) > 0){
    foreach($_['useroverlayservers'] as $ts){
        echo '<li title="'.$ts['url'].'"';
        foreach (Array('servername', 'type', 'url', 'layers', 'version', 'format', 'opacity', 'transparent', 'minzoom', 'maxzoom', 'attribution') as $field) {
            if (array_key_exists($field, $ts)) {
                echo ' '.$field.'="';
                p($ts[$field]);
                echo '"';
            }
        }
        echo '>';
        p($ts['servername']);
        echo '&nbsp <button><i class="fa fa-trash" aria-hidden="true" style="color:red;"></i> ';
        p($l->t('Delete'));
        echo '</button></li>';
    }
}
?>
        </ul>
    </div>
<hr/><br/>
    <h3 class="sectiontitle"><?php p($l->t('Custom WMS tile servers')); ?></h3>
    <div id="tilewmsserveradd">
        <p><?php p($l->t('Server name')); ?> :</p>
        <input type="text" id="tilewmsservername" title="<?php p($l->t('For example : my custom server')); ?>"/>
        <p><?php p($l->t('Server url')); ?> :</p>
        <input type="text" id="tilewmsserverurl" title="<?php p($l->t('For example : http://tile.server.org/cycle/{z}/{x}/{y}.png')); ?>"/>
        <p><?php p($l->t('Min zoom (1-20)')); ?> :</p>
        <input type="text" id="tilewmsminzoom" value="1"/>
        <p><?php p($l->t('Max zoom (1-20)')); ?> :</p>
        <input type="text" id="tilewmsmaxzoom" value="18"/>
        <p><?php p($l->t('Format')); ?> :</p>
        <input type="text" id="tilewmsformat" value="image/jpeg"/>
        <p><?php p($l->t('WMS version')); ?> :</p>
        <input type="text" id="tilewmsversion" value="1.1.1"/>
        <p><?php p($l->t('Layers to display')); ?> :</p>
        <input type="text" id="tilewmslayers" value=""/>
        <button id="addtileserverwms"><i class="fa fa-plus-circle" aria-hidden="true" style="color:green;"></i> <?php p($l->t('Add')); ?></button>
    </div>
    <div id="tilewmsserverlist">
        <h3><?php p($l->t('Your WMS tile servers')); ?></h3>
        <ul class="disclist">
<?php
if (count($_['usertileserverswms']) > 0){
    foreach($_['usertileserverswms'] as $ts){
        echo '<li title="'.$ts['url'].'"';
        foreach (Array('servername', 'type', 'url', 'layers', 'version', 'format', 'opacity', 'transparent', 'minzoom', 'maxzoom', 'attribution') as $field) {
            if (array_key_exists($field, $ts)) {
                echo ' '.$field.'="';
                p($ts[$field]);
                echo '"';
            }
        }
        echo '>';
        p($ts['servername']);
        echo '&nbsp <button><i class="fa fa-trash" aria-hidden="true" style="color:red;"></i> ';
        p($l->t('Delete'));
        echo '</button></li>';
    }
}
?>
        </ul>
    </div>
<hr/><br/>
    <h3 class="sectiontitle"><?php p($l->t('Custom WMS overlay servers')); ?></h3>
    <div id="overlaywmsserveradd">
        <p><?php p($l->t('Server name')); ?> :</p>
        <input type="text" id="overlaywmsservername" title="<?php p($l->t('For example : my custom server')); ?>"/>
        <p><?php p($l->t('Server url')); ?> :</p>
        <input type="text" id="overlaywmsserverurl" title="<?php p($l->t('For example : http://overlay.server.org/cycle/{z}/{x}/{y}.png')); ?>"/>
        <p><?php p($l->t('Min zoom (1-20)')); ?> :</p>
        <input type="text" id="overlaywmsminzoom" value="1"/>
        <p><?php p($l->t('Max zoom (1-20)')); ?> :</p>
        <input type="text" id="overlaywmsmaxzoom" value="18"/>
        <label for="overlaywmstransparent"><?php p($l->t('Transparent')); ?> :</label>
        <input type="checkbox" id="overlaywmstransparent" checked/>
        <p><?php p($l->t('Opacity (0.0-1.0)')); ?> :</p>
        <input type="text" id="overlaywmsopacity" value="0.4"/>
        <p><?php p($l->t('Format')); ?> :</p>
        <input type="text" id="overlaywmsformat" value="image/jpeg"/>
        <p><?php p($l->t('WMS version')); ?> :</p>
        <input type="text" id="overlaywmsversion" value="1.1.1"/>
        <p><?php p($l->t('Layers to display')); ?> :</p>
        <input type="text" id="overlaywmslayers" value=""/>
        <button id="addoverlayserverwms"><i class="fa fa-plus-circle" aria-hidden="true" style="color:green;"></i> <?php p($l->t('Add')); ?></button>
    </div>
    <div id="overlaywmsserverlist">
        <h3><?php p($l->t('Your WMS overlay tile servers')); ?></h3>
        <ul class="disclist">
<?php
if (count($_['useroverlayserverswms']) > 0){
    foreach($_['useroverlayserverswms'] as $ts){
        echo '<li title="'.$ts['url'].'"';
        foreach (Array('servername', 'type', 'url', 'layers', 'version', 'format', 'opacity', 'transparent', 'minzoom', 'maxzoom', 'attribution') as $field) {
            if (array_key_exists($field, $ts)) {
                echo ' '.$field.'="';
                p($ts[$field]);
                echo '"';
            }
        }
        echo '>';
        p($ts['servername']);
        echo '&nbsp <button><i class="fa fa-trash" aria-hidden="true" style="color:red;"></i> ';
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
    <h3 class="sectiontitle"><?php p($l->t('Features overview')); ?> :</h3>
<?php
    p($l->t('Draw, edition and deletion buttons are in the map\'s bottom-left corner.'));
    p(' ');
    p($l->t('You can draw a line or add a marker.'));
    p(' ');
    p($l->t('If you click on a line or a marker, a popup pops and let you set the object properties.'));
    p(' ');
?>
    <br/>
 <?php
    p($l->t('After a click on "edition" button, in edition mode, you can'));
?> :
    <ul class="disclist">
        <li><?php p($l->t('move markers')); ?></li>
        <li><?php p($l->t('move line points')); ?></li>
        <li><?php p($l->t('click on a line point to remove it')); ?></li>
        <li><?php p($l->t('hover a "middle marker" (between two line points) and press "Del" to cut the line in two (this action cannot be canceled)')); ?></li>
    </ul>
    <br/><hr/><br/>
    <h3 class="sectiontitle"><?php p($l->t('Shortcuts')); ?> :</h3>
    <ul class="disclist">
        <li><b>&lt;</b> : <?php p($l->t('toggle sidebar')); ?></li>
        <li><b>!</b> : <?php p($l->t('toggle minimap')); ?></li>
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

