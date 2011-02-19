<?php
/**
 * P4A - PHP For Applications.
 *
 * The code within this file is public domain.
 *
 * @author Fabrizio Balliano <fabrizio@fabrizioballiano.it>
 * @author Andrea Giardina <andrea.giardina@crealabs.it>
 * @package p4a_helpers
 */

function P4A_Field_loadSelectByArray($field, $params)
{
	list($array) = $params;

	$field->build('p4a_array_source', 'source');
	$field->source->load($array);
	
	$field->setType('select');
	$field->setSource($field->source);
}
