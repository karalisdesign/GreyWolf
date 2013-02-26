	<div class="control-group">
    <label class="control-label" for="id">ID:</label>
    <div class="controls">
		<input type="text" id="id" name="lingue[<?php echo $_REQUEST['value']; ?>][id]" placeholder="" class="input-block-level" value="<?php echo $_REQUEST['value']; ?>" />
    <p class="help-block">ex: per l'Italiano it_IT, per l'inglese en_EN</p>
    </div>
    </div>
	
	
	<div class="control-group">
    <label class="control-label" for="nome">Nome:</label>
    <div class="controls">
    <input type="text" id="nome" name="lingue[<?php echo $_REQUEST['value']; ?>][nome]" placeholder="" class="input-block-level" />
    <p class="help-block">Inserisci il nome della lingua</p>
    </div>
    </div>

    <div class="control-group">
    <label class="control-label" for="default">Default:</label>
    <div class="controls">
    <input type="checkbox" name="lingue[<?php echo $_REQUEST['value']; ?>][default]" id="default" />
    <p class="help-block">Imposta come lingua di default per questo sito</p>
    </div>
    </div>
	
	<div class="control-group">
    <label class="control-label" for="stato">Attivo:</label>
    <div class="controls">
    <input type="checkbox" name="lingue[<?php echo $_REQUEST['value']; ?>][stato]" id="stato" />
    <p class="help-block">Check attiva/ vuoto stand-by</p>
    </div>
    </div>