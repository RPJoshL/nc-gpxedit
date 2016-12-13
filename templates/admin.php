<?php
OCP\Util::addscript('gpxedit', 'admin');
OCP\Util::addstyle('gpxedit', 'admin');
?>

    <div class="section" id="gpxedit">
        <h2><?php p($l->t('GpxEdit admin settings')); ?></h2>
        <h3><?php p($l->t('Extra symbols')); ?> </h3>
        <label><?php p($l->t('Those symbols will be available in GpxEdit.')); ?></label><br/>
        <label><?php p($l->t('Keep in mind that only symbol names are saved in the gpx files. Other programs will display default symbol if they do not know a symbol name.')); ?></label><br/>
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
            echo '" title="Remove"></button>';
			echo '</td></tr>';
		}
		?>
        </table>
		</div>

        <label><?php p($l->t('Recommended image ratio : 1:1')); ?></label><br/>
        <label><?php p($l->t('Recommended image resolution : between 24x24 and 50x50')); ?></label><br/>
        <label><?php p($l->t('Image format : png')); ?></label><br/>
		<form class="uploadButton" method="post" action="<?php p($_['uploadPath']) ?>">
            <label for="addExtraSymbolName">New symbol name :</label>
            <input type="text" name="addExtraSymbolName" id="addExtraSymbolName"></input>

			<input id="uploadsymbol" class="upload-symbol-field" name="uploadsymbol" type="file"></input>
			<label for="uploadsymbol" class="button icon-upload svg" id="uploadsymbol" title="<?php p($l->t('Upload new symbol image')) ?>"></label>
            <span id="extraSymbolsSettingsMsg" class="msg"></span>
		</form>
    </div>
