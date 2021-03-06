<style>
	div.panel {padding: 20px 5px 10px 10px;}
	div.area {font-size:16px; text-align: center; line-height: 30px; width:500px; margin:auto}
	ul.li {padding:2px}
</style>

<div class="panel">

	<br/>
	<div class="area">
		<img src="<?php echo DUPLICATOR_PLUGIN_URL ?>assets/img/logo-dpro-300x50.png"  />
		<h2>
			<?php esc_html_e('Store your packages in multiple locations with', 'duplicator') ?><br/>
			<?php esc_html_e('Duplicator Pro', 'duplicator') ?>
		</h2>

		<div style='text-align: left; margin:auto; width:200px'>
			<ul>
				<li><i class="fa fa-amazon"></i> <?php esc_html_e('Amazon S3', 'duplicator'); ?></li>
				<li><i class="fa fa-dropbox"></i> <?php esc_html_e(' Dropbox', 'duplicator'); ?></li>
				<li><i class="fa fa-google"></i> <?php esc_html_e('Google Drive', 'duplicator'); ?></li>
				<li><i class="fa fa-upload"></i> <?php esc_html_e('FTP & SFTP', 'duplicator'); ?></li>
                <li><i class="fa fa-cloud"></i> <?php esc_html_e('OneDrive', 'duplicator'); ?></li>
				<li><i class="fa fa-folder-open-o"></i> <?php esc_html_e('Custom Directory', 'duplicator'); ?></li>
			</ul>
		</div>
		<?php
			 esc_html_e('Set up a one-time storage location and automatically', 'duplicator');
			 echo '<br/>';
			 esc_html_e('push the package to your destination.', 'duplicator');
		?>
	</div><br/>
	
	<p style="text-align:center">
		<a href="https://snapcreek.com/duplicator/?utm_source=duplicator_free&utm_medium=wordpress_plugin&utm_content=free_settings_storage&utm_campaign=duplicator_pro" target="_blank" class="button button-primary button-large dup-check-it-btn" >
			<?php esc_html_e('Learn More', 'duplicator') ?>
		</a>
	</p>
</div>
