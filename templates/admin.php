<?php
OCP\Util::addscript('gpxedit', 'admin');
OCP\Util::addscript('gpxedit', 'htmx');
OCP\Util::addstyle('gpxedit', 'admin');
?>

    <div class="section" id="gpxedit">
        <h2><?php p($l->t('GpxEdit')); ?></h2>
        <h3><?php p($l->t('Extra symbols')); ?> </h3>
        <label><?php p($l->t('Those symbols will be available in GpxEdit.')); ?></label><br/>
        <label><?php p($l->t('Keep in mind that only symbol names are saved in gpx files. Other programs will display default symbol if they do not know a symbol name.')); ?></label><br/>
        <br />
        <div id="extraSymbols">
        <table id="extraSymbolsTable">
<?php
        foreach($_['extraSymbolList'] as $symbol){
            echo '<tr class="extraSymbol" id="';
            p($symbol['smallname']);
            echo '"><td>';
            echo '<img src="';
            p($symbol['name']);
            echo '"/></td><td>';
            echo '<label> ';
            p($symbol['smallname']);
            echo ' </label></td><td>';
            echo '<button class="delExtraSymbol icon-delete icon" name="';
            p($symbol['name']);
            echo '" title="'.$l->t('Delete').'"></button>';
            echo '</td></tr>';
        }
?>
        </table>
        </div>

        <label><?php p($l->t('Recommended image ratio : 1:1')); ?></label><br/>
        <label><?php p($l->t('Recommended image resolution : between 24x24 and 50x50')); ?></label><br/>
        <label><?php p($l->t('Accepted image format : png')); ?></label><br/>
        <form
            id="uploadExtraSymbol"
            class="uploadButton" 
            hx-encoding='multipart/form-data'
            hx-post="<?php p($_['uploadPath']) ?>"
            hx-swap="none"
        >
            <label for="addExtraSymbolName"><?php p($l->t('New symbol name')); ?> :</label>
            <input type="text" name="addExtraSymbolName" id="addExtraSymbolName"></input>

            <input id="uploadsymbol" class="upload-symbol-field" name="uploadsymbol" type="file"></input>
            <input type="hidden" name="requesttoken" value="<?= $_['requesttoken'] ?>">

            <button class="icon-upload svg" id="uploadsymbol" title="<?php p($l->t('Upload new symbol image')) ?>"></button>
            <span id="extraSymbolsSettingsMsg" class="msg"></span>
        </form>

        <form 
            id="mapboxApiKey" 
            hx-post="<?php p($_['saveMapboxApiKeyPath']) ?>"
            hx-swap="none"
        >
            <label for="mapboxApiKey">Mapbox API key:</label>
            <input placeholder="<?php p($_['adminMapboxApiKey']) ?>" type="password" name="mapboxApiKey"></input>

            <input type="hidden" name="requesttoken" value="<?= $_['requesttoken'] ?>">

            <button class="icon-checkmark svg" title="Save Mapbox API key"></button>
        </form>
    </div>
