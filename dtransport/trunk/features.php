<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Módulo para la administración de descargas
// http://www.redmexico.com.mx
// http://www.exmsystem.com
// --------------------------------------------
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License as
// published by the Free Software Foundation; either version 2 of
// the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public
// License along with this program; if not, write to the Free
// Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
// MA 02111-1307 USA
// --------------------------------------------------------------
// @copyright: 2007 - 2008 Red México

include '../../mainfile.php';

$xoopsOption['module_subpage'] = 'features';
$xoopsLogger->renderingEnabled = false;
$xoopsLogger->activated = false;


include 'header.php';

if($id<=0 && $mc['permalinks'])
    $dtfunc->error_404();
elseif($id<=0)
    redirect_header(DT_URL);

$feat = new DTFeature($id);

if($feat->isNew())
    $dtfunc->error_404();

include $tpl->get_template("dtrans_feature.php", 'module', 'dtransport');