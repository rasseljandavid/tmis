<fieldset class="reference_fieldset current">
	<legend>Reference #<?php echo $_POST['counter']; ?></legend>
	<input type="hidden" name="references[type][]" value="reference" />
	<input placeholder="*&nbsp;Name" type="text" name="references[name][]" id="references[name][]" class="input-block-level" required />
	<input placeholder="*&nbsp;Address &nbsp;(Lot & blk no. street barangay, city, zipcode)" type="text" name="references[address][]" id="references[address][]" class="input-block-level" required />
	<input placeholder="*&nbsp;Contact" type="text" name="references[contact][]" id="references[contact][]" class="input-block-level" required />
</fieldset>