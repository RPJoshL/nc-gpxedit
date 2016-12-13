<?php
OCP\Util::addscript('gpxedit', 'admin');
OCP\Util::addstyle('gpxedit', 'admin');
?>

    <div class="section" id="gpxedit">
        <h2><?php p($l->t('GpxEdit additional settings')); ?></h2>
        <label><?php p($l->t( 'Extra symbols' )); ?> </label>
        <span id="extraSymbolsSettingsMsg" class="msg"></span>
        <br />
		<div id="extraSymbols">
		<?php
		foreach($_['extraSymbolList'] as $symbol){
            echo '<p class="extraSymbol" id="';
            p($symbol['smallname']);
            echo '">';
            echo '<img src="';
            p($symbol['name']);
            echo '"/>';
            echo '<label> ';
            p($symbol['smallname']);
            echo ' </label>';
            echo '<button class="delExtraSymbol icon-delete icon" name="';
            p($symbol['name']);
            echo '" title="Remove"></button>';
			echo '</p>';
		}
		?>
		</div>
		<!--button id="addExtraSymbol" class="inlineblock button icon-upload" title="Upload new symbol image"></button-->

		<form class="uploadButton" method="post" action="<?php p($_['uploadPath']) ?>">
            <label for="addExtraSymbolName">New symbol name :</label>
            <input type="text" name="addExtraSymbolName" id="addExtraSymbolName"></input>

			<input id="uploadsymbol" class="upload-symbol-field" name="uploadsymbol" type="file">
			<label for="uploadsymbol" class="button icon-upload svg" id="uploadsymbol" title="<?php p($l->t('Upload new symbol image')) ?>"></label>
		</form>
    </div>
