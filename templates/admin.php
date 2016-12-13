<?php
OCP\Util::addscript('gpxedit', 'admin');
OCP\Util::addstyle('gpxedit', 'admin');
?>

    <div class="section" id="gpxedit">
        <h2><?php p($l->t('GpxEdit additional settings')); ?></h2>
        <label><?php p($l->t( 'Extra symbols' )); ?> </label>
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
		<!--button id="addExtraSymbol" class="inlineblock button icon-upload" title="Upload new symbol image"></button-->

		<form class="uploadButton" method="post" action="<?php p($_['uploadPath']) ?>">
            <label for="addExtraSymbolName">New symbol name :</label>
            <input type="text" name="addExtraSymbolName" id="addExtraSymbolName"></input>

			<input id="uploadsymbol" class="upload-symbol-field" name="uploadsymbol" type="file"></input>
			<label for="uploadsymbol" class="button icon-upload svg" id="uploadsymbol" title="<?php p($l->t('Upload new symbol image')) ?>"></label>
            <span id="extraSymbolsSettingsMsg" class="msg"></span>
		</form>
    </div>
