<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

	$style = 'zendefault';
	$name = $vars['name'];
	$splparams = $vars['splparams'];
	$datas = $vars['datas'];
	$cols = $vars['cols'];
	$rowcls = isset($vars['row-fluid']) && $vars['row-fluid'] ? 'row-fluid':'row';

	?>
	<!-- <?php echo $name ?> -->
	<div class="row-fluid">
			<?php
			foreach ($splparams as $i => $splparam):
				$param = (object)$splparam;
			?>
				<div class="<?php echo $splparam->default ?> <?php echo ($i == 0) ? 'item-first' : (($i == $cols - 1) ? 'item-last' : '') ?>"<?php echo $datas[$i] ?>>
					<?php if ($this->countModules($param->position)) : ?>
					<div id="<?php echo $param->position ?>">
						<jdoc:include type="modules" name="<?php echo $param->position ?>" style="<?php echo $style ?>"/>
					</div>
					<?php else: ?>
					&nbsp;
					<?php endif ?>
				</div>
			<?php endforeach ?>
	</div>
<!-- <?php echo $name ?> -->