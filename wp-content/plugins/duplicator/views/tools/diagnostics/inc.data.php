<?php

	$sql = "SELECT * FROM `{$wpdb->prefix}options` WHERE  `option_name` LIKE  '%duplicator_%' AND  `option_name` NOT LIKE '%duplicator_pro%' ORDER BY option_name";

?>

<!-- ==============================
OPTIONS DATA -->
<div class="dup-box">
	<div class="dup-box-title">
		<i class="fa fa-th-list"></i>
		<?php _e("Stored Data", 'duplicator'); ?>
		<div class="dup-box-arrow"></div>
	</div>
	<div class="dup-box-panel" id="dup-settings-diag-opts-panel" style="<?php echo esc_attr($ui_css_opts_panel); ?>">
		<div style="padding-left:10px">
			<h3 class="title"><?php _e('Data Cleanup', 'duplicator') ?></h3>
				<table class="dup-reset-opts">
					<tr style="vertical-align:text-top">
						<td>
							<button id="dup-remove-installer-files-btn" type="button" class="button button-small dup-fixed-btn" onclick="Duplicator.Tools.deleteInstallerFiles();">
								<?php _e("Remove Installation Files", 'duplicator'); ?>
							</button>
						</td>
						<td>
							<?php _e("Removes all reserved installer files.", 'duplicator'); ?>
							<a href="javascript:void(0)" onclick="jQuery('#dup-tools-delete-moreinfo').toggle()">[<?php _e("more info", 'duplicator'); ?>]</a><br/>

							<div id="dup-tools-delete-moreinfo">
								<?php
								_e("Clicking on the 'Remove Installation Files' button will attempt to remove the installer files used by Duplicator.  These files should not "
									. "be left on production systems for security reasons. Below are the files that should be removed.", 'duplicator');
								echo "<br/><br/>";
								$installer_files = array_keys($installer_files);
								$installer_files = array_map('esc_html', $installer_files);
								array_push($installer_files, '[HASH]_archive.zip');
								echo '<i>' . implode('<br/>', $installer_files) . '</i>';
								echo "<br/><br/>";
								?>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<button type="button" class="button button-small dup-fixed-btn" onclick="Duplicator.Tools.ConfirmClearBuildCache()">
								<?php _e("Clear Build Cache", 'duplicator'); ?>
							</button>
						</td>
						<td><?php _e("Removes all build data from:", 'duplicator'); ?> [<?php echo DUPLICATOR_SSDIR_PATH_TMP ?>].</td>
					</tr>
				</table>
		</div>
		<div style="padding:0px 20px 0px 25px">
			<h3 class="title" style="margin-left:-15px"><?php _e("Options Values", 'duplicator') ?> </h3>	
			<table class="widefat" cellspacing="0">
				<thead>
					<tr>
						<th>Key</th>
						<th>Value</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					foreach( $wpdb->get_results("{$sql}") as $key => $row) { ?>	
					<tr>
						<td>
							<?php 
								 echo (in_array($row->option_name, $GLOBALS['DUPLICATOR_OPTS_DELETE']))
									? "<a href='javascript:void(0)' onclick='Duplicator.Settings.ConfirmDeleteOption(this)'>".esc_js($row->option_name)."</a>"
									: esc_textarea($row->option_name);
							?>
						</td>
						<td><textarea class="dup-opts-read" readonly="readonly"><?php echo esc_textarea($row->option_value); ?></textarea></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>

	</div> 
</div> 
<br/>

<!-- ==========================================
THICK-BOX DIALOGS: -->
<?php
	$confirm1 = new DUP_UI_Dialog();
	$confirm1->title			= __('Delete Option?', 'duplicator');
	$confirm1->message			= __('Delete the option value just selected?', 'duplicator');
	$confirm1->progressText	= __('Removing Option, Please Wait...', 'duplicator');
	$confirm1->jscallback		= 'Duplicator.Settings.DeleteOption()';
	$confirm1->initConfirm();

	$confirm2 = new DUP_UI_Dialog();
	$confirm2->title			= __('Clear Build Cache?', 'duplicator');
	$confirm2->message			= __('This process will remove all build cache files.  Be sure no packages are currently building or else they will be cancelled.', 'duplicator');
	$confirm2->jscallback		= 'Duplicator.Tools.ClearBuildCache()';
	$confirm2->initConfirm();
?>

<script>	
jQuery(document).ready(function($) 
{
	Duplicator.Settings.ConfirmDeleteOption = function (anchor) 
	{
		var key = $(anchor).text();
		var msg_id = '<?php echo esc_js($confirm1->getMessageID()); ?>';
		var msg    = '<?php _e('Delete the option value', 'duplicator');?>' + ' [' + key + '] ?';
		jQuery('#dup-settings-form-action').val(key);
		jQuery('#' + msg_id).html(msg)
		<?php $confirm1->showConfirm(); ?>
	}
	
	
	Duplicator.Settings.DeleteOption = function () 
	{
		jQuery('#dup-settings-form').submit();
	}

	Duplicator.Tools.ConfirmClearBuildCache = function ()
	{
		 <?php $confirm2->showConfirm(); ?>
	}

	Duplicator.Tools.ClearBuildCache = function ()
	{
		window.location = "<?php echo esc_js(esc_url('?page=duplicator-tools&tab=diagnostics&action=tmp-cache&_wpnonce='.$nonce));?>";
	}
});


Duplicator.Tools.deleteInstallerFiles = function()
{
	<?php
	$url = "?page=duplicator-tools&tab=diagnostics&action=installer&_wpnonce={$nonce}&package=".esc_js($package_name);
	echo "window.location = '".$url."';";
	?>
}
</script>
