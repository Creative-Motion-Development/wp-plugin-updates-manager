<?php

	/**
	 * The page Settings.
	 *
	 * @since 1.0.0
	 */
	class WbcrUpm_PluginsPage extends FactoryPages000_ImpressiveThemplate {

		/**
		 * The id of the page in the admin menu.
		 *
		 * Mainly used to navigate between pages.
		 * @see FactoryPages000_AdminPage
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $id = "plugins";

		public $type = "page";

		public $page_parent_page = 'updates';

		public $page_menu_dashicon = 'dashicons-cloud';

		private $is_disable_updates;
		private $is_auto_updates;
		private $plugins_update_filters = array();

		public function __construct(Factory000_Plugin $plugin)
		{
			$this->menuTitle = __('Plugins', 'webcraftic-updates-manager');

			parent::__construct($plugin);

			$updates_mode = $this->getOption('plugin_updates');

			$this->is_disable_updates = $updates_mode == 'disable_plugin_updates';
			$this->is_auto_updates = $updates_mode == 'enable_plugin_auto_updates';
			$this->plugins_update_filters = $this->getOption('plugins_update_filters');
		}

		public function warningNotice()
		{
			parent::warningNotice();

			$concat = __('На этой странице вы можете индвидуально отключить обновления и автообновления плагинов.', 'webcraftic-updates-manager') . '<br>';

			if( $this->is_disable_updates ) {
				$concat .= __('- Чтобы индивидуально отключить обновления, выберите опцию ручные или автоматические обновления плагинов, сохраните настройки и вернитесь на эту страницу.', 'webcraftic-updates-manager') . '<br>';
			}

			if( !$this->is_auto_updates ) {
				$concat .= __('- Чтобы индивидуально настроить автоматические обновления плагинов, выберите опцию автоматические обновления, сохраните настройки и вернитесь на эту страницу.', 'webcraftic-updates-manager');
			}

			$this->printWarningNotice($concat);
		}

		/**
		 * Requests assets (js and css) for the page.
		 *
		 * @see FactoryPages000_AdminPage
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function assets($scripts, $styles)
		{

			parent::assets($scripts, $styles);
			$this->styles->add(WBCR_UPM_PLUGIN_URL . '/admin/assets/css/general.css');
		}

		public function savePluginsUpdateFilters()
		{
			update_option($this->plugin->pluginName . '_plugins_update_filters', $this->plugins_update_filters);
		}

		public function disablePluginUpdatesAction()
		{
			if( !$this->is_disable_updates ) {
				$plugin_slug = $this->request->get('plugin_slug', null, true);

				if( !empty($plugin_slug) ) {
					if( isset($this->plugins_update_filters['disable_updates']) && !isset($this->plugins_update_filters['disable_updates'][$plugin_slug]) ) {
						$this->plugins_update_filters['disable_updates'][$plugin_slug] = true;
					}

					$this->savePluginsUpdateFilters();
				}
			}

			$this->redirectToAction('index');
		}

		public function enablePluginUpdatesAction()
		{
			if( !$this->is_disable_updates ) {
				$plugin_slug = $this->request->get('plugin_slug', null, true);

				if( !empty($plugin_slug) ) {
					if( isset($this->plugins_update_filters['disable_updates']) && isset($this->plugins_update_filters['disable_updates'][$plugin_slug]) ) {
						unset($this->plugins_update_filters['disable_updates'][$plugin_slug]);
						$this->savePluginsUpdateFilters();
					}
				}
			}

			$this->redirectToAction('index');
		}

		public function disablePluginAutoupdatesAction()
		{
			if( $this->is_auto_updates ) {
				$plugin_slug = $this->request->get('plugin_slug', null, true);

				if( !empty($plugin_slug) ) {
					if( isset($this->plugins_update_filters['disable_auto_updates']) && !isset($this->plugins_update_filters['disable_auto_updates'][$plugin_slug]) ) {
						$this->plugins_update_filters['disable_auto_updates'][$plugin_slug] = true;
						$this->savePluginsUpdateFilters();
					}
				}
			}
			$this->redirectToAction('index');
		}

		public function enablePluginAutoupdatesAction()
		{
			if( $this->is_auto_updates ) {
				$plugin_slug = $this->request->get('plugin_slug', null, true);

				if( !empty($plugin_slug) ) {
					if( isset($this->plugins_update_filters['disable_auto_updates']) && isset($this->plugins_update_filters['disable_auto_updates'][$plugin_slug]) ) {
						unset($this->plugins_update_filters['disable_auto_updates'][$plugin_slug]);
						$this->savePluginsUpdateFilters();
					}
				}
			}
			$this->redirectToAction('index');
		}

		public function showPageContent()
		{
			if( isset($_POST['wbcr_upm_apply']) ) {

				$nonce = $this->request->post('_wpnonce', null, true);
				$bulk_action = $this->request->post('wbcr_upm_bulk_actions', null, true);
				$plugin_slugs = $this->request->post('plugin_slugs', array(), true);

				if( !wp_verify_nonce($nonce, $this->getResultId() . '_form') ) {
					wp_die('You do not have permission to edit this page.');
				}

				if( !$this->is_disable_updates ) {
					if( !empty($bulk_action) && !empty($plugin_slugs) && is_array($plugin_slugs) ) {

						foreach((array)$plugin_slugs as $slug) {
							if( $this->is_auto_updates ) {
								if( $bulk_action == 'enable_updates' && isset($this->plugins_update_filters['disable_updates']) && isset($this->plugins_update_filters['disable_updates'][$slug]) ) {
									unset($this->plugins_update_filters['disable_updates'][$slug]);
								}
							}
							if( $bulk_action == 'enable_auto_updates' || $bulk_action == 'disable_updates' ) {
								if( $this->is_auto_updates ) {
									if( isset($this->plugins_update_filters['disable_auto_updates']) && isset($this->plugins_update_filters['disable_auto_updates'][$slug]) ) {
										unset($this->plugins_update_filters['disable_auto_updates'][$slug]);
									}
								}
								if( $bulk_action == 'disable_updates' ) {
									$this->plugins_update_filters[$bulk_action][$slug] = true;
								}
							} else {
								$this->plugins_update_filters[$bulk_action][$slug] = true;
							}
						}

						$this->savePluginsUpdateFilters();
					}
				}
			}

			?>
			<style>
				#the-list tr.inactive .check-column {
					border-left: 3px solid #D54E21;
				}

				#the-list tr.inactive {
					background: #FEF7F1;
				}
			</style>

			<?php $this->warningNotice(); ?>
			<form method="post">
				<?php wp_nonce_field($this->getResultId() . '_form') ?>
				<p>
					<select name="wbcr_upm_bulk_actions" id="wbcr_upm_bulk_actions">
						<option value="0"><?php _e('Bulk actions', 'webcraftic-updates-manager'); ?></option>
						<option value="disable_updates"><?php _e('Disable updates', 'webcraftic-updates-manager'); ?></option>
						<option value="enable_updates"><?php _e('Enable updates', 'webcraftic-updates-manager'); ?></option>
						<option value="enable_auto_updates"><?php _e('Enable auto-updates', 'webcraftic-updates-manager'); ?></option>
						<option value="disable_auto_updates"><?php _e('Disable auto-updates', 'webcraftic-updates-manager'); ?></option>
					</select>
					<input type="submit" name="wbcr_upm_apply" id="wbcr_upm_apply" class='button button-alt' value='<?php _e("Apply", "webcraftic-updates-manager"); ?>'>
				</p>
				<table class="wp-list-table widefat autoupdate striped plugins">
					<thead>
					<tr>
						<td id='cb' class='manage-column column-cb check-column'>&nbsp;</td>
						<th id='name' class='manage-column column-name column-primary'>
							<strong><?php _e('Plugin', 'webcraftic-updates-manager'); ?></strong></th>
						<th id='description' class='manage-column column-description'>
							<strong><?php _e('Description', 'webcraftic-updates-manager'); ?></strong></th>
					</tr>
					</thead>
					<tbody id="the-list">
					<?php

						foreach(get_plugins() as $key => $value):

							$slug = $key;
							$slug_parts = explode('/', $slug);
							$actual_slug = array_shift($slug_parts);
							$slug_hash = md5($slug[0]);
							$description = $name = 'Empty';

							foreach((array)$value as $k => $v) {

								if( $k == "Name" ) {
									$name = $v;
								}
								if( $k == "Description" ) {
									$description = $v;
								}
							}

							$class = 'active';
							$is_disable_updates = false;
							$is_auto_updates = true;

							if( !empty($this->plugins_update_filters) ) {

								if( isset($this->plugins_update_filters['disable_auto_updates']) && isset($this->plugins_update_filters['disable_auto_updates'][$actual_slug]) ) {
									$is_auto_updates = false;
								}
								if( (isset($this->plugins_update_filters['disable_updates']) && isset($this->plugins_update_filters['disable_updates'][$actual_slug])) ) {
									$class = 'inactive';
									$is_disable_updates = true;
								}
							}

							if( $this->is_disable_updates ) {
								$class = 'inactive';
								$is_disable_updates = true;
							}

							?>
							<tr id="post-<?= esc_attr($slug_hash) ?>" class="<?= $class ?>">
								<th scope="row" class="check-column">
									<label class="screen-reader-text" for="cb-select-<?= esc_attr($slug_hash) ?>"><?php _e('Select', 'webcraftic-updates-manager') ?><?= esc_html($name) ?></label>
									<input id="cb-select-<?= esc_attr($slug_hash) ?>" type="checkbox" name="plugin_slugs[]" value="<?= esc_attr($actual_slug) ?>">
									<label></label>

									<div class="locked-indicator"></div>
								</th>
								<td class="plugin-title column-primary">
									<strong class="plugin-name">
										<?= esc_html($name) ?>
									</strong>

									<div class="row-actions visible status">
										<?php if( !$this->is_disable_updates ): ?>
											<?php if( !$is_disable_updates ): ?>
												<span class="trash"><a href="<?= $this->getActionUrl('disable-plugin-updates', array('plugin_slug' => $actual_slug)) ?>"><?php _e('Disable updates', 'webcraftic-updates-manager') ?></a></span>
											<?php else: ?>
												<span><a href="<?= $this->getActionUrl('enable-plugin-updates', array('plugin_slug' => $actual_slug)) ?>"><?php _e('Enable updates', 'webcraftic-updates-manager') ?></a></span>
											<?php endif; ?>
										<?php else: ?>
											<span style="text-decoration: underline;"><?php _e('Disable updates', 'webcraftic-updates-manager') ?></span>
										<?php endif; ?>
										|
										<?php if( $this->is_auto_updates && !$is_disable_updates ): ?>
											<?php if( $is_auto_updates ): ?>
												<span><a href="<?= $this->getActionUrl('disable-plugin-autoupdates', array('plugin_slug' => $actual_slug)) ?>"><?php _e('Disable auto-updates', 'webcraftic-updates-manager') ?></a></span>
											<?php else: ?>
												<span><a href="<?= $this->getActionUrl('enable-plugin-autoupdates', array('plugin_slug' => $actual_slug)) ?>"><?php _e('Enable auto-updates', 'webcraftic-updates-manager') ?></a></span>
											<?php endif; ?>
										<?php else: ?>
											<?php if( $is_auto_updates ): ?>
												<span style="text-decoration: underline;"><?php _e('Disable auto-updates', 'webcraftic-updates-manager') ?></span>
											<?php else: ?>
												<span style="text-decoration: underline;"><?php _e('Enable auto-updates', 'webcraftic-updates-manager') ?></span>
											<?php endif; ?>
										<?php endif; ?>
									</div>
								</td>
								<td class="column-description desc">
									<div class="plugin-description">
										<p><?= esc_html($description) ?></p>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</form>
		<?php
		}
	}

	FactoryPages000::register($wbcr_update_services_plugin, 'WbcrUpm_PluginsPage');
