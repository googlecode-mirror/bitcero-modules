<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

define('AH_LOCATION','references');
include '../../mainfile.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id<=0) die(_MS_AH_NOID);

$ref = new AHReference($id);
if ($ref->isNew()) die(_MS_AH_NOEXISTS);

echo $ref->reference();
