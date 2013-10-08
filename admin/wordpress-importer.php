<?php
if ( ! defined( 'WP_LOAD_IMPORTERS' ) )
	define( 'WP_LOAD_IMPORTERS', true );

// Load Importer API
include_once ABSPATH . 'wp-admin/includes/import.php';

if ( ! class_exists( 'WP_Importer' ) ) {
	$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
	if ( file_exists( $class_wp_importer ) )
		require $class_wp_importer;
}

// include WXR file parsers
include_once dirname( __FILE__ ) . '/parsers.php';

/**
 * WordPress Importer class for managing the import process of a WXR file
 *
 * @package WordPress
 * @subpackage Importer
 */
if ( class_exists( 'WP_Importer' ) ) {
		class MY_Import extends WP_Importer {
			var $max_wxr_version = 1.2; // max. supported WXR version

			var $id; // WXR attachment ID

			// information to import from WXR file
			var $version;
			var $authors = array();
			var $posts = array();
			var $terms = array();
			var $categories = array();
			var $tags = array();
			var $base_url = '';

			// mappings from old information to new
			var $processed_authors = array();
			var $author_mapping = array();
			var $processed_terms = array();
			var $processed_posts = array();
			var $post_orphans = array();
			var $processed_menu_items = array();
			var $menu_item_orphans = array();
			var $missing_menu_items = array();

			var $fetch_attachments = false;
			var $url_remap = array();
			var $featured_images = array();

			var $step1 = 'admin.php?page=options-framework-import&amp;step=1'; // start import data
			var $step2 = 'admin.php?page=options-framework-import&amp;step=2'; // importing data
			var $step3 = 'admin.php?page=options-framework-import&amp;step=3'; // start import widget
			var $step4 = 'admin.php?page=options-framework-import&amp;step=4'; // importing widget

			function WP_Import() {}

			/**
			 * Registered callback function for the WordPress Importer
			 *
			 * Manages the three separate stages of the WXR import process
			 */
			function dispatch() {
				$cherry_widget_data = new myWidget_Data();
				$step = empty( $_GET['step'] ) ? 1 : (int) $_GET['step'];
				switch ( $step ) {
					case 1:
						$this->header($step);
						$this->greet();
						$this->footer();
						break;
					case 2:
						$this->header($step);
						check_admin_referer( 'import-upload' );
						if ( $this->handle_upload() )
							$this->import_options();
						$this->footer();
						break;
					case 3:
						// check_admin_referer( 'import-wordpress' );
						$this->fetch_attachments = ( ! empty( $_POST['fetch_attachments'] ) && $this->allow_fetch_attachments() );
						if ( array_key_exists('import_id', $_POST) ) {
							$this->id = (int) $_POST['import_id'];
							$file = get_attached_file( $this->id );
							set_time_limit(0);
							$this->import( $file );
							$this->settings();
						}
						// call widget settings import 
						$cherry_widget_data->import_settings_page();
						break;
					case 4:
						// call widget settings import 
						$cherry_widget_data->import_settings_page();
						break;
					default:
						break;
				}
			}

			/**
			 * The main controller for the actual import stage.
			 *
			 * @param string $file Path to the WXR file for importing
			 */
			function import( $file ) {
				add_filter( 'import_post_meta_key', array( $this, 'is_valid_meta_key' ) );
				add_filter( 'http_request_timeout', array( &$this, 'bump_request_timeout' ) );

				$this->import_start( $file );
				$this->get_author_mapping();

				wp_suspend_cache_invalidation( true );
				$this->process_categories();
				$this->process_tags();
				$this->process_terms();
				$this->process_posts();
				wp_suspend_cache_invalidation( false );

				// update incorrect/missing information in the DB
				$this->backfill_parents();
				$this->backfill_attachment_urls();
				$this->remap_featured_images();

				$this->import_end();
			}

			/**
			 * Parses the WXR file and prepares us for the task of processing parsed data
			 *
			 * @param string $file Path to the WXR file for importing
			 */
			function import_start( $file ) {
				if ( ! is_file($file) ) {
					echo '<p class="text-style"><strong>'.theme_locals('sorry').'</strong></p>';
					echo theme_locals('not_exist');
					echo ' <a class="btn-link" href="'.$this->step1.'">'.theme_locals('try_again').'</a>.';
					$this->log(date('Y-m-d H:i:s'));
					$this->log(theme_locals('not_exist') . theme_locals('try_again') . PHP_EOL);
					$this->footer();
					die();
				}

				$import_data = $this->parse( $file );

				if ( is_wp_error( $import_data ) ) {
					echo '<p class="text-style"><strong>'.theme_locals('sorry').'</strong></p>';
					echo esc_html( $import_data->get_error_message() );
					echo '<a class="btn-link" href="'.$this->step1.'">'.theme_locals('try_again').'</a>.';
					$this->log(date('Y-m-d H:i:s'));
					$this->log(esc_html( $import_data->get_error_message() . PHP_EOL));
					$this->footer();
					die();
				}

				$this->version = $import_data['version'];
				$this->get_authors_from_import( $import_data );
				$this->posts = $import_data['posts'];
				$this->terms = $import_data['terms'];
				$this->categories = $import_data['categories'];
				$this->tags = $import_data['tags'];
				$this->base_url = esc_url( $import_data['base_url'] );

				wp_defer_term_counting( true );
				wp_defer_comment_counting( true );

				do_action( 'import_start' );
				$this->log(date('Y-m-d H:i:s'));
				$this->log('Import start' . PHP_EOL);
			}

			/**
			 * Performs post-import cleanup of files and the cache
			 */
			function import_end() {
				wp_import_cleanup( $this->id );

				wp_cache_flush();
				foreach ( get_taxonomies() as $tax ) {
					delete_option( "{$tax}_children" );
					_get_term_hierarchy( $tax );
				}

				wp_defer_term_counting( false);
				wp_defer_comment_counting( false );

				update_option('cherry_sample_data', 1);

				do_action( 'import_end' );
				$this->log(date('Y-m-d H:i:s'));
				$this->log(PHP_EOL . 'Import end' . PHP_EOL);
			}

			/**
			 * Write to log file
			 */
			function log($message) {
				$log_file = CHILD_DIR . '/install.log';
				if (is_writable(CHILD_DIR)) {
					file_put_contents($log_file,  $message . PHP_EOL, FILE_APPEND);
				}
			} // End log()

			/**
			 * Handles the WXR upload and initial parsing of the file to prepare for
			 * displaying author import options
			 *
			 * @return bool False if error uploading or invalid file, true otherwise
			 */
			function handle_upload() {
				$file = wp_import_handle_upload();

				if ( isset( $file['error'] ) ) {
					echo '<p class="text-style"><strong>' . theme_locals('sorry') . '</strong><br />';
					echo esc_html( $file['error'] ) . '</p>';
					echo '<a class="btn-link" href="'.$this->step1.'">'.theme_locals('try_again').'</a>.</p>';
					$this->log(date('Y-m-d H:i:s'));
					$this->log(esc_html($file['error']) . theme_locals('try_again') . PHP_EOL);
					return false;
				} else if ( ! file_exists( $file['file'] ) ) {
					echo '<p><strong>' . theme_locals('sorry') . '</strong><br />';
					printf( theme_locals('export_file'), esc_html( $file['file'] ) );
					echo '</p>';
					$this->log(date('Y-m-d H:i:s'));
					$this->log('The export file could not be found at <code>'.esc_html($file['file']).'</code>. It is likely that this was caused by a permissions problem' . PHP_EOL);
					return false;
				}

				$this->id = (int) $file['id'];
				$import_data = $this->parse( $file['file'] );
				if ( is_wp_error( $import_data ) ) {
					echo '<p class="text-style"><strong>' . theme_locals('sorry') . '</strong><br/>';
					echo esc_html( $import_data->get_error_message() );
					echo '. '.theme_locals('please').', <a class="btn-link" href="'.$this->step1.'">'.theme_locals('try_again').'</a>.</p>';
					$this->log(date('Y-m-d H:i:s'));
					$this->log(esc_html($import_data->get_error_message()) . '. ' . theme_locals('try_again') . PHP_EOL);
					return false;
				}

				$this->version = $import_data['version'];
				if ( $this->version > $this->max_wxr_version ) {
					echo '<div class="error"><p><strong>';
					printf(theme_locals('WXR_file'), esc_html($import_data['version']) );
					echo '</strong></p></div>';
					$this->log(date('Y-m-d H:i:s'));
					$this->log('This WXR file (version '.esc_html($import_data["version"]).') may not be supported by this version of the importer. Please consider updating' . PHP_EOL);
				}

				$this->get_authors_from_import( $import_data );

				return true;
			}

			/**
			 * Retrieve authors from parsed WXR data
			 *
			 * Uses the provided author information from WXR 1.1 files
			 * or extracts info from each post for WXR 1.0 files
			 *
			 * @param array $import_data Data returned by a WXR parser
			 */
			function get_authors_from_import( $import_data ) {
				if ( ! empty( $import_data['authors'] ) ) {
					$this->authors = $import_data['authors'];
				// no author information, grab it from the posts
				} else {
					foreach ( $import_data['posts'] as $post ) {
						$login = sanitize_user( $post['post_author'], true );
						if ( empty( $login ) ) {
							// printf( theme_locals('import_author'), esc_html( $post['post_author'] ) );
							// echo '<br />';
							$this->log('Failed to import author '.esc_html($post["post_author"]).'. Their posts will be attributed to the current user');
							continue;
						}

						if ( ! isset($this->authors[$login]) )
							$this->authors[$login] = array(
								'author_login' => $login,
								'author_display_name' => $post['post_author']
							);
					}
				}
			}

			/**
			 * Display pre-import options, author importing/mapping and option to
			 * fetch attachments
			 */
			function import_options() {
				if ( ini_get('output_buffering') != 1 )
					echo "<div class='note'><strong>" . theme_locals('note') . ": </strong>" . theme_locals('settings_output_buffering') . "</div>";
				$j = 0;
				?>
				<form action="<?php echo admin_url( $this->step3 ); ?>" method="post" id="dataForm" class="clearfix">
					<?php wp_nonce_field( 'import-wordpress' ); ?>
					<input type="hidden" name="import_id" value="<?php echo $this->id; ?>" />

				<?php if ( ! empty( $this->authors ) ) : ?>
					<!--h4><?php echo theme_locals('Assign Authors'); ?></h4-->
					<p><?php echo theme_locals('To make it easier'); ?></p>
				<!--<?php if ( $this->allow_create_users() ) : ?>
					<p><?php printf(theme_locals('If a new user is'), esc_html( get_option('default_role') ) ); ?></p>
				<?php endif; ?> -->
					<ol id="authors">
				<?php foreach ( $this->authors as $author ) : ?>
						<li class="clearfix"><?php $this->author_select( $j++, $author ); ?></li>
				<?php endforeach; ?>
					</ol>
				<?php endif; ?>

				<?php if ( $this->allow_fetch_attachments() ) : ?>
					<div>
						<input type="checkbox" value="1" name="fetch_attachments" id="import-attachments" checked="checked" />
						<!--label for="import-attachments"><?php echo theme_locals('Download and import'); ?></label-->
					</div>
				<?php endif; ?>

					<input type="submit" class="button-primary" value="<?php echo theme_locals('install_next'); ?>" disabled="disabled" />
				</form>
				<form action="<?php echo $this->step3; ?>" method="post" id="skip-import-data" class="clearfix">
					<p class="submit"><input type="submit" class="btn-link" value="<?php echo theme_locals("skip"); ?>"></p>
				</form>
				<?php
			}

			/**
			 * Display import options for an individual author. That is, either create
			 * a new user based on import info or map to an existing user
			 *
			 * @param int $n Index for each author in the form
			 * @param array $author Author information, e.g. login, display name, email
			 */
			function author_select( $n, $author ) {
				echo theme_locals('import_author_2');
				echo ' <strong>' . esc_html( $author['author_display_name'] );
				if ( $this->version != '1.0' ) echo ' (' . esc_html( $author['author_login'] ) . ')';
				echo '</strong>';

				if ( $this->version != '1.0' )
					echo '<div id="user-wrap" style="margin-left:15px">';

				$create_users = $this->allow_create_users();

				if ( ! $create_users && $this->version == '1.0' )
					echo theme_locals('existing_user');
				else
				wp_dropdown_users( array( 'name' => "user_map[$n]", 'multi' => true, 'show_option_all' => theme_locals('select') ) );
				echo '<input type="hidden" name="imported_authors['.$n.']" value="' . esc_attr( $author['author_login'] ) . '" />';

				if ( $this->version != '1.0' )
					echo '</div>';
			}

			/**
			 * Map old author logins to local user IDs based on decisions made
			 * in import options form. Can map to an existing user, create a new user
			 * or falls back to the current user in case of error with either of the previous
			 */
			function get_author_mapping() {
				if ( ! isset( $_POST['imported_authors'] ) )
					return;

				$create_users = $this->allow_create_users();

				foreach ( (array) $_POST['imported_authors'] as $i => $old_login ) {
					// Multisite adds strtolower to sanitize_user. Need to sanitize here to stop breakage in process_posts.
					$santized_old_login = sanitize_user( $old_login, true );
					$old_id = isset( $this->authors[$old_login]['author_id'] ) ? intval($this->authors[$old_login]['author_id']) : false;

					if ( ! empty( $_POST['user_map'][$i] ) ) {
						$user = get_userdata( intval($_POST['user_map'][$i]) );
						if ( isset( $user->ID ) ) {
							if ( $old_id )
								$this->processed_authors[$old_id] = $user->ID;
							$this->author_mapping[$santized_old_login] = $user->ID;
						}
					} else if ( $create_users ) {
						if ( ! empty($_POST['user_new'][$i]) ) {
							$user_id = wp_create_user( $_POST['user_new'][$i], wp_generate_password() );
						} else if ( $this->version != '1.0' ) {
							$user_data = array(
								'user_login' => $old_login,
								'user_pass' => wp_generate_password(),
								'user_email' => isset( $this->authors[$old_login]['author_email'] ) ? $this->authors[$old_login]['author_email'] : '',
								'display_name' => $this->authors[$old_login]['author_display_name'],
								'first_name' => isset( $this->authors[$old_login]['author_first_name'] ) ? $this->authors[$old_login]['author_first_name'] : '',
								'last_name' => isset( $this->authors[$old_login]['author_last_name'] ) ? $this->authors[$old_login]['author_last_name'] : '',
							);
							$user_id = wp_insert_user( $user_data );
						}

						if ( ! is_wp_error( $user_id ) ) {
							if ( $old_id )
								$this->processed_authors[$old_id] = $user_id;
							$this->author_mapping[$santized_old_login] = $user_id;
						} else {
							// printf(theme_locals('create_new_user'), esc_html($this->authors[$old_login]['author_display_name']) );
							// echo '<br />';
							$this->log($user_id->get_error_message());
							$this->log('Failed to create new user for '.esc_html($this->authors[$old_login]["author_display_name"]).'. Their posts will be attributed to the current user');
						}
					}

					// failsafe: if the user_id was invalid, default to the current user
					if ( ! isset( $this->author_mapping[$santized_old_login] ) ) {
						if ( $old_id )
							$this->processed_authors[$old_id] = (int) get_current_user_id();
						$this->author_mapping[$santized_old_login] = (int) get_current_user_id();
					}
				}
			}

			/**
			 * Create new categories based on import information
			 *
			 * Doesn't create a new category if its slug already exists
			 */
			function process_categories() {
				$this->categories = apply_filters( 'wp_import_categories', $this->categories );

				if ( empty( $this->categories ) )
					return;

				foreach ( $this->categories as $cat ) {
					// if the category already exists leave it alone
					$term_id = term_exists( $cat['category_nicename'], 'category' );
					if ( $term_id ) {
						if ( is_array($term_id) ) $term_id = $term_id['term_id'];
						if ( isset($cat['term_id']) )
							$this->processed_terms[intval($cat['term_id'])] = (int) $term_id;
						continue;
					}

					$category_parent = empty( $cat['category_parent'] ) ? 0 : category_exists( $cat['category_parent'] );
					$category_description = isset( $cat['category_description'] ) ? $cat['category_description'] : '';
					$catarr = array(
						'category_nicename' => $cat['category_nicename'],
						'category_parent' => $category_parent,
						'cat_name' => $cat['cat_name'],
						'category_description' => $category_description
					);

					$id = wp_insert_category( $catarr );
					if ( ! is_wp_error( $id ) ) {
						if ( isset($cat['term_id']) )
							$this->processed_terms[intval($cat['term_id'])] = $id;
					} else {
						// printf( theme_locals('import_category'), esc_html($cat['category_nicename']) );
						// echo '<br />';|
						$this->log($id->get_error_message());
						$this->log('Failed to import category '.esc_html($cat["category_nicename"]));
						continue;
					}
				}

				unset( $this->categories );
			}

			/**
			 * Create new post tags based on import information
			 *
			 * Doesn't create a tag if its slug already exists
			 */
			function process_tags() {
				$this->tags = apply_filters( 'wp_import_tags', $this->tags );

				if ( empty( $this->tags ) )
					return;

				foreach ( $this->tags as $tag ) {
					// if the tag already exists leave it alone
					$term_id = term_exists( $tag['tag_slug'], 'post_tag' );
					if ( $term_id ) {
						if ( is_array($term_id) ) $term_id = $term_id['term_id'];
						if ( isset($tag['term_id']) )
							$this->processed_terms[intval($tag['term_id'])] = (int) $term_id;
						continue;
					}

					$tag_desc = isset( $tag['tag_description'] ) ? $tag['tag_description'] : '';
					$tagarr = array( 'slug' => $tag['tag_slug'], 'description' => $tag_desc );

					$id = wp_insert_term( $tag['tag_name'], 'post_tag', $tagarr );
					if ( ! is_wp_error( $id ) ) {
						if ( isset($tag['term_id']) )
							$this->processed_terms[intval($tag['term_id'])] = $id['term_id'];
					} else {
						// printf( theme_locals('import_post_tag'), esc_html($tag['tag_name']) );
						// echo '<br />';
						$this->log($id->get_error_message());
						$this->log('Failed to import post tag '.esc_html($tag["tag_name"]));
						continue;
					}
				}

				unset( $this->tags );
			}

			/**
			 * Create new terms based on import information
			 *
			 * Doesn't create a term its slug already exists
			 */
			function process_terms() {
				$this->terms = apply_filters( 'wp_import_terms', $this->terms );

				if ( empty( $this->terms ) )
					return;

				foreach ( $this->terms as $term ) {
					// if the term already exists in the correct taxonomy leave it alone
					$term_id = term_exists( $term['slug'], $term['term_taxonomy'] );
					if ( $term_id ) {
						if ( is_array($term_id) ) $term_id = $term_id['term_id'];
						if ( isset($term['term_id']) )
							$this->processed_terms[intval($term['term_id'])] = (int) $term_id;
						continue;
					}

					if ( empty( $term['term_parent'] ) ) {
						$parent = 0;
					} else {
						$parent = term_exists( $term['term_parent'], $term['term_taxonomy'] );
						if ( is_array( $parent ) ) $parent = $parent['term_id'];
					}
					$description = isset( $term['term_description'] ) ? $term['term_description'] : '';
					$termarr = array( 'slug' => $term['slug'], 'description' => $description, 'parent' => intval($parent) );

					$id = wp_insert_term( $term['term_name'], $term['term_taxonomy'], $termarr );
					if ( ! is_wp_error( $id ) ) {
						if ( isset($term['term_id']) )
							$this->processed_terms[intval($term['term_id'])] = $id['term_id'];
					} else {
						// printf( theme_locals('failed_to_import'), esc_html($term['term_taxonomy']), esc_html($term['term_name']) );
						// echo '<br />';
						$this->log($id->get_error_message());
						$this->log('Failed to import '.esc_html($term["term_taxonomy"]).' '.esc_html($term["term_name"]));
						continue;
					}
				}

				unset( $this->terms );
			}

			/**
			 * Create new posts based on import information
			 *
			 * Posts marked as having a parent which doesn't exist will become top level items.
			 * Doesn't create a new post if: the post type doesn't exist, the given post ID
			 * is already noted as imported or a post with the same title and date already exists.
			 * Note that new/updated terms, comments and meta are imported for the last of the above.
			 */
			function process_posts() {
				$this->posts = apply_filters( 'wp_import_posts', $this->posts );

				foreach ( $this->posts as $post ) {
					$post = apply_filters( 'wp_import_post_data_raw', $post );

					if ( ! post_type_exists( $post['post_type'] ) ) {
						// printf( theme_locals('failed_to_import_2'), 
						// 	esc_html($post['post_title']), esc_html($post['post_type']) );
						// echo '<br />';
						$this->log('Failed to import "' . esc_html($post["post_title"]) . '": Invalid post type ' . esc_html($post["post_type"]));
						do_action( 'wp_import_post_exists', $post );
						continue;
					}

					if ( isset( $this->processed_posts[$post['post_id']] ) && ! empty( $post['post_id'] ) )
						continue;

					if ( $post['status'] == 'auto-draft' )
						continue;

					if ( 'nav_menu_item' == $post['post_type'] ) {
						$this->process_menu_item( $post );
						continue;
					}

					$post_type_object = get_post_type_object( $post['post_type'] );

					$post_exists = post_exists( $post['post_title'], '', $post['post_date'] );
					if ( $post_exists && get_post_type( $post_exists ) == $post['post_type'] ) {
						// printf( theme_locals('already_exists'), $post_type_object->labels->singular_name, esc_html($post['post_title']) );
						// echo '<br />';
						$this->log($post_type_object->labels->singular_name . ' "'. esc_html($post['post_title']) .'" already exists');
						$comment_post_ID = $post_id = $post_exists;
					} else {
						$post_parent = (int) $post['post_parent'];
						if ( $post_parent ) {
							// if we already know the parent, map it to the new local ID
							if ( isset( $this->processed_posts[$post_parent] ) ) {
								$post_parent = $this->processed_posts[$post_parent];
							// otherwise record the parent for later
							} else {
								$this->post_orphans[intval($post['post_id'])] = $post_parent;
								$post_parent = 0;
							}
						}

						// map the post author
						$author = sanitize_user( $post['post_author'], true );
						if ( isset( $this->author_mapping[$author] ) )
							$author = $this->author_mapping[$author];
						else
							$author = (int) get_current_user_id();

						$postdata = array(
							'import_id' => $post['post_id'], 'post_author' => $author, 'post_date' => $post['post_date'],
							'post_date_gmt' => $post['post_date_gmt'], 'post_content' => $post['post_content'],
							'post_excerpt' => $post['post_excerpt'], 'post_title' => $post['post_title'],
							'post_status' => $post['status'], 'post_name' => $post['post_name'],
							'comment_status' => $post['comment_status'], 'ping_status' => $post['ping_status'],
							'guid' => $post['guid'], 'post_parent' => $post_parent, 'menu_order' => $post['menu_order'],
							'post_type' => $post['post_type'], 'post_password' => $post['post_password']
						);

						$original_post_ID = $post['post_id'];
						$postdata = apply_filters( 'wp_import_post_data_processed', $postdata, $post );

						if ( 'attachment' == $postdata['post_type'] ) {
							$remote_url = ! empty($post['attachment_url']) ? $post['attachment_url'] : $post['guid'];

							// try to use _wp_attached file for upload folder placement to ensure the same location as the export site
							// e.g. location is 2003/05/image.jpg but the attachment post_date is 2010/09, see media_handle_upload()
							$postdata['upload_date'] = $post['post_date'];
							if ( isset( $post['postmeta'] ) ) {
								foreach( $post['postmeta'] as $meta ) {
									if ( $meta['key'] == '_wp_attached_file' ) {
										if ( preg_match( '%^[0-9]{4}/[0-9]{2}%', $meta['value'], $matches ) )
											$postdata['upload_date'] = $matches[0];
										break;
									}
								}
							}

							$comment_post_ID = $post_id = $this->process_attachment( $postdata, $remote_url );
						} else {
							$comment_post_ID = $post_id = wp_insert_post( $postdata, true );
							do_action( 'wp_import_insert_post', $post_id, $original_post_ID, $postdata, $post );
						}

						if ( is_wp_error( $post_id ) ) {
							// printf(theme_locals('failed_to_import_3'),
							// 	$post_type_object->labels->singular_name, esc_html($post['post_title']) );
							// echo '<br />';
							$this->log($post_id->get_error_message());
							$this->log('Failed to import '.$post_type_object->labels->singular_name.' "'.esc_html($post["post_title"]).'"');
							continue;
						}

						if ( $post['is_sticky'] == 1 )
							stick_post( $post_id );
					}

					// map pre-import ID to local ID
					$this->processed_posts[intval($post['post_id'])] = (int) $post_id;

					if ( ! isset( $post['terms'] ) )
						$post['terms'] = array();

					$post['terms'] = apply_filters( 'wp_import_post_terms', $post['terms'], $post_id, $post );

					// add categories, tags and other terms
					if ( ! empty( $post['terms'] ) ) {
						$terms_to_set = array();
						foreach ( $post['terms'] as $term ) {
							// back compat with WXR 1.0 map 'tag' to 'post_tag'
							$taxonomy = ( 'tag' == $term['domain'] ) ? 'post_tag' : $term['domain'];
							$term_exists = term_exists( $term['slug'], $taxonomy );
							$term_id = is_array( $term_exists ) ? $term_exists['term_id'] : $term_exists;
							if ( ! $term_id ) {
								$t = wp_insert_term( $term['name'], $taxonomy, array( 'slug' => $term['slug'] ) );
								if ( ! is_wp_error( $t ) ) {
									$term_id = $t['term_id'];
									do_action( 'wp_import_insert_term', $t, $term, $post_id, $post );
								} else {
									// printf( theme_locals('failed_to_import'), esc_html($taxonomy), esc_html($term['name']) );
									// echo '<br />';
									$this->log($t->get_error_message());
									$this->log('Failed to import '.esc_html($taxonomy).' '.esc_html($term['name']));
									do_action( 'wp_import_insert_term_failed', $t, $term, $post_id, $post );
									continue;
								}
							}
							$terms_to_set[$taxonomy][] = intval( $term_id );
						}

						foreach ( $terms_to_set as $tax => $ids ) {
							$tt_ids = wp_set_post_terms( $post_id, $ids, $tax );
							do_action( 'wp_import_set_post_terms', $tt_ids, $ids, $tax, $post_id, $post );
						}
						unset( $post['terms'], $terms_to_set );
					}

					if ( ! isset( $post['comments'] ) )
						$post['comments'] = array();

					$post['comments'] = apply_filters( 'wp_import_post_comments', $post['comments'], $post_id, $post );

					// add/update comments
					if ( ! empty( $post['comments'] ) ) {
						$num_comments = 0;
						$inserted_comments = array();
						foreach ( $post['comments'] as $comment ) {
							$comment_id	= $comment['comment_id'];
							$newcomments[$comment_id]['comment_post_ID']      = $comment_post_ID;
							$newcomments[$comment_id]['comment_author']       = $comment['comment_author'];
							$newcomments[$comment_id]['comment_author_email'] = $comment['comment_author_email'];
							$newcomments[$comment_id]['comment_author_IP']    = $comment['comment_author_IP'];
							$newcomments[$comment_id]['comment_author_url']   = $comment['comment_author_url'];
							$newcomments[$comment_id]['comment_date']         = $comment['comment_date'];
							$newcomments[$comment_id]['comment_date_gmt']     = $comment['comment_date_gmt'];
							$newcomments[$comment_id]['comment_content']      = $comment['comment_content'];
							$newcomments[$comment_id]['comment_approved']     = $comment['comment_approved'];
							$newcomments[$comment_id]['comment_type']         = $comment['comment_type'];
							$newcomments[$comment_id]['comment_parent'] 	  = $comment['comment_parent'];
							$newcomments[$comment_id]['commentmeta']          = isset( $comment['commentmeta'] ) ? $comment['commentmeta'] : array();
							if ( isset( $this->processed_authors[$comment['comment_user_id']] ) )
								$newcomments[$comment_id]['user_id'] = $this->processed_authors[$comment['comment_user_id']];
						}
						ksort( $newcomments );

						foreach ( $newcomments as $key => $comment ) {
							// if this is a new post we can skip the comment_exists() check
							if ( ! $post_exists || ! comment_exists( $comment['comment_author'], $comment['comment_date'] ) ) {
								if ( isset( $inserted_comments[$comment['comment_parent']] ) )
									$comment['comment_parent'] = $inserted_comments[$comment['comment_parent']];
								$comment = wp_filter_comment( $comment );
								$inserted_comments[$key] = wp_insert_comment( $comment );
								do_action( 'wp_import_insert_comment', $inserted_comments[$key], $comment, $comment_post_ID, $post );

								foreach( $comment['commentmeta'] as $meta ) {
									$value = maybe_unserialize( $meta['value'] );
									add_comment_meta( $inserted_comments[$key], $meta['key'], $value );
								}

								$num_comments++;
							}
						}
						unset( $newcomments, $inserted_comments, $post['comments'] );
					}

					if ( ! isset( $post['postmeta'] ) )
						$post['postmeta'] = array();

					$post['postmeta'] = apply_filters( 'wp_import_post_meta', $post['postmeta'], $post_id, $post );

					// add/update post meta
					if ( isset( $post['postmeta'] ) ) {
						foreach ( $post['postmeta'] as $meta ) {
							$key = apply_filters( 'import_post_meta_key', $meta['key'] );
							$value = false;

							if ( '_edit_last' == $key ) {
								if ( isset( $this->processed_authors[intval($meta['value'])] ) )
									$value = $this->processed_authors[intval($meta['value'])];
								else
									$key = false;
							}

							if ( $key ) {
								// export gets meta straight from the DB so could have a serialized string
								if ( ! $value )
									$value = maybe_unserialize( $meta['value'] );

								add_post_meta( $post_id, $key, $value );
								do_action( 'import_post_meta', $post_id, $key, $value );

								// if the post has a featured image, take note of this in case of remap
								if ( '_thumbnail_id' == $key )
									$this->featured_images[$post_id] = (int) $value;
							}
						}
					}
				}

				unset( $this->posts );
			}

			/**
			 * Attempt to create a new menu item from import data
			 *
			 * Fails for draft, orphaned menu items and those without an associated nav_menu
			 * or an invalid nav_menu term. If the post type or term object which the menu item
			 * represents doesn't exist then the menu item will not be imported (waits until the
			 * end of the import to retry again before discarding).
			 *
			 * @param array $item Menu item details from WXR file
			 */
			function process_menu_item( $item ) {
				$menus_array = get_terms('nav_menu');
				$save_array = array();
				foreach($menus_array as $menu){
					if($menu->name == 'Header Menu'){
						$save_array['header_menu'] = $menu->term_id;
					}else if($menu->name == 'Footer Menu'){
						$save_array['footer_menu'] = $menu->term_id;
					}
				}

				// skip draft, orphaned menu items
				if ( 'draft' == $item['status'] )
					return;

				$menu_slug = false;
				if ( isset($item['terms']) ) {
					// loop through terms, assume first nav_menu term is correct menu
					foreach ( $item['terms'] as $term ) {
						if ( 'nav_menu' == $term['domain'] ) {
							$menu_slug = $term['slug'];
							break;
						}
					}
				}

				// no nav_menu term associated with this menu item
				if ( ! $menu_slug ) {
					// echo theme_locals('menu_item');
					// echo '<br />';
					$this->log(theme_locals('menu_item'));
					return;
				}

				$menu_id = term_exists( $menu_slug, 'nav_menu' );
				if ( ! $menu_id ) {
					// printf( theme_locals('menu_item_2'), esc_html( $menu_slug ) );
					// echo '<br />';
					$this->log('Menu item skipped due to invalid menu slug: '.esc_html($menu_slug));
					return;
				} else {
					$menu_id = is_array( $menu_id ) ? $menu_id['term_id'] : $menu_id;
				}

				foreach ( $item['postmeta'] as $meta )
					$$meta['key'] = $meta['value'];

				if ( 'taxonomy' == $_menu_item_type && isset( $this->processed_terms[intval($_menu_item_object_id)] ) ) {
					$_menu_item_object_id = $this->processed_terms[intval($_menu_item_object_id)];
				} else if ( 'post_type' == $_menu_item_type && isset( $this->processed_posts[intval($_menu_item_object_id)] ) ) {
					$_menu_item_object_id = $this->processed_posts[intval($_menu_item_object_id)];
				} else if ( 'custom' != $_menu_item_type ) {
					// associated object is missing or not imported yet, we'll retry later
					$this->missing_menu_items[] = $item;
					return;
				}

				if ( isset( $this->processed_menu_items[intval($_menu_item_menu_item_parent)] ) ) {
					$_menu_item_menu_item_parent = $this->processed_menu_items[intval($_menu_item_menu_item_parent)];
				} else if ( $_menu_item_menu_item_parent ) {
					$this->menu_item_orphans[intval($item['post_id'])] = (int) $_menu_item_menu_item_parent;
					$_menu_item_menu_item_parent = 0;
				}

				// wp_update_nav_menu_item expects CSS classes as a space separated string
				$_menu_item_classes = maybe_unserialize( $_menu_item_classes );
				if ( is_array( $_menu_item_classes ) )
					$_menu_item_classes = implode( ' ', $_menu_item_classes );

				$args = array(
					'menu-item-object-id'   => $_menu_item_object_id,
					'menu-item-object'      => $_menu_item_object,
					'menu-item-parent-id'   => $_menu_item_menu_item_parent,
					'menu-item-position'    => intval( $item['menu_order'] ),
					'menu-item-type'        => $_menu_item_type,
					'menu-item-title'       => $item['post_title'],
					'menu-item-url'         => $_menu_item_url,
					'menu-item-description' => $item['post_content'],
					'menu-item-attr-title'  => $item['post_excerpt'],
					'menu-item-target'      => $_menu_item_target,
					'menu-item-classes'     => $_menu_item_classes,
					'menu-item-xfn'         => $_menu_item_xfn,
					'menu-item-status'      => $item['status']
				);

				if (!empty($save_array)) {
					if ( isset($save_array['header_menu']) ) {
						$header_menu_items = wp_get_nav_menu_items($save_array['header_menu']);
					}
					if ( isset($save_array['footer_menu']) ) {
						$footer_menu_items = wp_get_nav_menu_items($save_array['footer_menu']);
					}
					switch ($menu_id) {
						case $save_array['header_menu']:
							$page = get_page ($_menu_item_object_id);
							$temp1 = $page->post_title;
							$temp2 = array();
							foreach ( (array) $header_menu_items as $key => $header_menu_item) {
								$temp2[] = $header_menu_item->title;
							}
							if (!(in_array($temp1, $temp2))) {
								$id = wp_update_nav_menu_item( $menu_id, 0, $args );
								if ( $id && ! is_wp_error( $id ) )
									$this->processed_menu_items[intval($item['post_id'])] = (int) $id;
							}
							break;
						case $save_array['footer_menu']:
							$page = get_page ($_menu_item_object_id);
							$temp1 = $page->post_title;
							$temp2 = array();
							foreach ( (array) $footer_menu_items as $key => $footer_menu_item) {
								$temp2[] = $footer_menu_item->title;
							}
							if (!(in_array($temp1, $temp2))) {
								$id = wp_update_nav_menu_item( $menu_id, 0, $args );
								if ( $id && ! is_wp_error( $id ) )
									$this->processed_menu_items[intval($item['post_id'])] = (int) $id;
							}
							break;
						default:
							break;
					}
				} else {
					$id = wp_update_nav_menu_item( $menu_id, 0, $args );
					if ( $id && ! is_wp_error( $id ) )
						$this->processed_menu_items[intval($item['post_id'])] = (int) $id;
				}
			}

			/**
			 * If fetching attachments is enabled then attempt to create a new attachment
			 *
			 * @param array $post Attachment post details from WXR
			 * @param string $url URL to fetch attachment from
			 * @return int|WP_Error Post ID on success, WP_Error otherwise
			 */
			function process_attachment( $post, $url ) {
				if ( ! $this->fetch_attachments )
					return new WP_Error( 'attachment_processing_error', theme_locals('attachments'));

				// if the URL is absolute, but does not contain address, then upload it assuming base_site_url
				if ( preg_match( '|^/[\w\W]+$|', $url ) )
					$url = rtrim( $this->base_url, '/' ) . $url;

				$upload = $this->fetch_remote_file( $url, $post );
				if ( is_wp_error( $upload ) )
					return $upload;

				if ( $info = wp_check_filetype( $upload['file'] ) )
					$post['post_mime_type'] = $info['type'];
				else
					return new WP_Error( 'attachment_processing_error', theme_locals('Invalid file type'));

				$post['guid'] = $upload['url'];

				// as per wp-admin/includes/upload.php
				$post_id = wp_insert_attachment( $post, $upload['file'] );
				wp_update_attachment_metadata( $post_id, wp_generate_attachment_metadata( $post_id, $upload['file'] ) );

				// remap resized image URLs, works by stripping the extension and remapping the URL stub.
				if ( preg_match( '!^image/!', $info['type'] ) ) {
					$parts = pathinfo( $url );
					$name = basename( $parts['basename'], ".{$parts['extension']}" ); // PATHINFO_FILENAME in PHP 5.2

					$parts_new = pathinfo( $upload['url'] );
					$name_new = basename( $parts_new['basename'], ".{$parts_new['extension']}" );

					$this->url_remap[$parts['dirname'] . '/' . $name] = $parts_new['dirname'] . '/' . $name_new;
				}

				return $post_id;
			}

			/**
			 * Attempt to download a remote file attachment
			 *
			 * @param string $url URL of item to fetch
			 * @param array $post Attachment details
			 * @return array|WP_Error Local file location details on success, WP_Error otherwise
			 */
			function fetch_remote_file( $url, $post ) {
				// extract the file name and extension from the url
				$file_name = basename( $url );

				// get placeholder file in the upload dir with a unique, sanitized filename
				$upload = wp_upload_bits( $file_name, 0, '', $post['upload_date'] );
				if ( $upload['error'] )
					return new WP_Error( 'upload_dir_error', $upload['error'] );

				// fetch the remote url and write it to the placeholder file
				$headers = wp_get_http( $url, $upload['file'] );

				// request failed
				if ( ! $headers ) {
					@unlink( $upload['file'] );
					return new WP_Error( 'import_file_error', theme_locals("remote") );
				}

				// make sure the fetch was successful
				if ( $headers['response'] != '200' ) {
					@unlink( $upload['file'] );
					$this->log(theme_locals("remote_2") . esc_html($headers['response']) . get_status_header_desc($headers['response']));
					return new WP_Error( 'import_file_error', sprintf( theme_locals("remote_2"), esc_html($headers['response']), get_status_header_desc($headers['response']) ) );
				}

				$filesize = filesize( $upload['file'] );

				if ( isset( $headers['content-length'] ) && $filesize != $headers['content-length'] ) {
					@unlink( $upload['file'] );
					$this->log(theme_locals("remote_3"));
					return new WP_Error( 'import_file_error', theme_locals("remote_3"));
				}

				if ( 0 == $filesize ) {
					@unlink( $upload['file'] );
					$this->log(theme_locals("zero_size"));
					return new WP_Error( 'import_file_error', theme_locals("zero_size"));
				}

				$max_size = (int) $this->max_attachment_size();
				if ( ! empty( $max_size ) && $filesize > $max_size ) {
					@unlink( $upload['file'] );
					$this->log(theme_locals("remote_4") . size_format($max_size));
					return new WP_Error( 'import_file_error', sprintf(theme_locals("remote_4"), size_format($max_size) ) );
				}

				// keep track of the old and new urls so we can substitute them later
				$this->url_remap[$url] = $upload['url'];
				$this->url_remap[$post['guid']] = $upload['url']; // r13735, really needed?
				// keep track of the destination if the remote url is redirected somewhere else
				if ( isset($headers['x-final-location']) && $headers['x-final-location'] != $url )
					$this->url_remap[$headers['x-final-location']] = $upload['url'];

				return $upload;
			}

			/**
			 * Attempt to associate posts and menu items with previously missing parents
			 *
			 * An imported post's parent may not have been imported when it was first created
			 * so try again. Similarly for child menu items and menu items which were missing
			 * the object (e.g. post) they represent in the menu
			 */
			function backfill_parents() {
				global $wpdb;

				// find parents for post orphans
				foreach ( $this->post_orphans as $child_id => $parent_id ) {
					$local_child_id = $local_parent_id = false;
					if ( isset( $this->processed_posts[$child_id] ) )
						$local_child_id = $this->processed_posts[$child_id];
					if ( isset( $this->processed_posts[$parent_id] ) )
						$local_parent_id = $this->processed_posts[$parent_id];

					if ( $local_child_id && $local_parent_id )
						$wpdb->update( $wpdb->posts, array( 'post_parent' => $local_parent_id ), array( 'ID' => $local_child_id ), '%d', '%d' );
				}

				// all other posts/terms are imported, retry menu items with missing associated object
				$missing_menu_items = $this->missing_menu_items;
				foreach ( $missing_menu_items as $item )
					$this->process_menu_item( $item );

				// find parents for menu item orphans
				foreach ( $this->menu_item_orphans as $child_id => $parent_id ) {
					$local_child_id = $local_parent_id = 0;
					if ( isset( $this->processed_menu_items[$child_id] ) )
						$local_child_id = $this->processed_menu_items[$child_id];
					if ( isset( $this->processed_menu_items[$parent_id] ) )
						$local_parent_id = $this->processed_menu_items[$parent_id];

					if ( $local_child_id && $local_parent_id )
						update_post_meta( $local_child_id, '_menu_item_menu_item_parent', (int) $local_parent_id );
				}
			}

			/**
			 * Use stored mapping information to update old attachment URLs
			 */
			function backfill_attachment_urls() {
				global $wpdb;
				// make sure we do the longest urls first, in case one is a substring of another
				uksort( $this->url_remap, array(&$this, 'cmpr_strlen') );

				foreach ( $this->url_remap as $from_url => $to_url ) {
					// remap urls in post_content
					$wpdb->query( $wpdb->prepare("UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, %s)", $from_url, $to_url) );
					// remap enclosure urls
					$result = $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, %s) WHERE meta_key='enclosure'", $from_url, $to_url) );
				}
			}

			/**
			 * Update _thumbnail_id meta to new, imported attachment IDs
			 */
			function remap_featured_images() {
				// cycle through posts that have a featured image
				foreach ( $this->featured_images as $post_id => $value ) {
					if ( isset( $this->processed_posts[$value] ) ) {
						$new_id = $this->processed_posts[$value];
						// only update if there's a difference
						if ( $new_id != $value )
							update_post_meta( $post_id, '_thumbnail_id', $new_id );
					}
				}
			}

			/**
			 * Parse a WXR file
			 *
			 * @param string $file Path to WXR file for parsing
			 * @return array Information gathered from the WXR file
			 */
			function parse( $file ) {
				$parser = new WXR_Parser();
				return $parser->parse( $file );
			}

			// Display import page title
			function header($step) {
				echo '<div class="clearfix">';
				echo '<h4 class="head">';
				switch ($step) {
					case 1:
						echo theme_locals("step_1");
						break;
					case 2:
						echo theme_locals("step_2");
						break;
					default:
						break;
				}
				echo '</h4>';
			}

			// Close div.wrap
			function footer() {
				echo '</div><!--/div.wrap-->';
			}

			/**
			 * Display introductory text and file upload form
			 */
			function greet() {
				$this->server_settings();
				echo '<p class="text-style">'.theme_locals("select_xml").'</p>';
				if ( get_option('cherry_sample_data') ) {
					echo "<p class='text-style' style='color:#BD362F'>".theme_locals('sample_data_import_warning')."</p>";
				}
				wp_import_upload_form( $this->step2 );
				echo '<form enctype="multipart/form-data" id="skip-import-data" method="post" action="'.$this->step3.'">';
				echo '<p class="submit"><input type="submit" class="btn-link" value="'.theme_locals("skip").'"></p>';
				echo '</form>';
				echo '<div class="clear"></div>';
			}

			function skip() {
				$this->settings();
				wp_redirect( admin_url( 'options-permalink.php' ) );
				exit;
				// echo '<div class="indent-top"><a class="button-primary fnone" href="' . admin_url() . 'options-permalink.php">' . theme_locals("finish"). '</a></div>';
			}

			function settings() {
				// Set Appearance -> Menu
				$menus = get_terms('nav_menu');
				$save = array();
				foreach($menus as $menu){
					if($menu->name == 'Header Menu'){
						$save['header_menu'] = $menu->term_id;
					}else if($menu->name == 'Footer Menu'){
						$save['footer_menu'] = $menu->term_id;
					}
				}
				if($save){
					set_theme_mod( 'nav_menu_locations', array_map( 'absint', $save ) );
				}

				// Set the front page
				update_option( 'show_on_front', 'page' );
				$home_pages = get_pages(
					array(
						'meta_key'   => '_wp_page_template',
						'meta_value' => 'page-home.php'
					)
				);
				if (!empty($home_pages)) {
					$home = $home_pages[0]->ID;
					update_option( 'page_on_front', $home );
				}

				// Set the blog page
				$default_pages = get_pages(
					array(
						'meta_key'   => '_wp_page_template',
						'meta_value' => 'default'
					)
				);
				if (!empty($default_pages)) {
					$blog = $default_pages[0]->ID;
					update_option( 'page_for_posts', $blog );
				}

				// Set post count for blog
				update_option( 'posts_per_page', 4 );

				// Set permalink custom structure
				update_option( 'permalink_structure', '/%category%/%postname%/' );

				// write to .htaccess MIME Type
				$htaccess = ABSPATH .'/.htaccess';
				if (file_exists($htaccess)) {
					$fp = fopen($htaccess, 'a+');
					if ($fp) {
						$contents = fread($fp, filesize($htaccess));
						$pos = strpos('# AddType TYPE/SUBTYPE EXTENSION', $contents);
						if ( $pos!==false ) {
							fwrite($fp, "\r\n# AddType TYPE/SUBTYPE EXTENSION\r\n");
							fwrite($fp, "AddType audio/mpeg mp3\r\n");
							fwrite($fp, "AddType audio/mp4 m4a\r\n");
							fwrite($fp, "AddType audio/ogg ogg\r\n");
							fwrite($fp, "AddType audio/ogg oga\r\n");
							fwrite($fp, "AddType audio/webm webma\r\n");
							fwrite($fp, "AddType audio/wav wav\r\n");
							fwrite($fp, "AddType video/mp4 mp4\r\n");
							fwrite($fp, "AddType video/mp4 m4v\r\n");
							fwrite($fp, "AddType video/ogg ogv\r\n");
							fwrite($fp, "AddType video/webm webm\r\n");
							fwrite($fp, "AddType video/webm webmv\r\n");
							fclose($fp);
						}
					}
				}

				$this->set_to_draft('hello-world');
				$this->set_to_draft('sample-page');
			}

			/**
			 * Decide if the given meta key maps to information we will want to import
			 *
			 * @param string $key The meta key to check
			 * @return string|bool The key if we do want to import, false if not
			 */
			function is_valid_meta_key( $key ) {
				// skip attachment metadata since we'll regenerate it from scratch
				// skip _edit_lock as not relevant for import
				if ( in_array( $key, array( '_wp_attached_file', '_wp_attachment_metadata', '_edit_lock' ) ) )
					return false;
				return $key;
			}

			/**
			 * Decide whether or not the importer is allowed to create users.
			 * Default is true, can be filtered via import_allow_create_users
			 *
			 * @return bool True if creating users is allowed
			 */
			function allow_create_users() {
				return apply_filters( 'import_allow_create_users', true );
			}

			/**
			 * Decide whether or not the importer should attempt to download attachment files.
			 * Default is true, can be filtered via import_allow_fetch_attachments. The choice
			 * made at the import options screen must also be true, false here hides that checkbox.
			 *
			 * @return bool True if downloading attachments is allowed
			 */
			function allow_fetch_attachments() {
				return apply_filters( 'import_allow_fetch_attachments', true );
			}

			/**
			 * Decide what the maximum file size for downloaded attachments is.
			 * Default is 0 (unlimited), can be filtered via import_attachment_size_limit
			 *
			 * @return int Maximum attachment file size to import
			 */
			function max_attachment_size() {
				return apply_filters( 'import_attachment_size_limit', 0 );
			}

			/**
			 * Added to http_request_timeout filter to force timeout at 60 seconds during import
			 * @return int 60
			 */
			function bump_request_timeout( $val ) {
				return 60;
			}

			// return the difference in length between two strings
			function cmpr_strlen( $a, $b ) {
				return strlen($b) - strlen($a);
			}

			/**
			 * Check server settings
			 */
			function server_settings() {
				// correct settings for server
				$must_settings = array(
					'safe_mode'           => 'off',
					'file_uploads'        => 'on',
					'memory_limit'        => 128,
					'post_max_size'       => 8,
					'upload_max_filesize' => 8,
					'max_input_time'      => 60,
					'max_execution_time'  => 30
				);
				// curret server settings
				$current_settings = array();

				//result array
				$result = array();

				if ( ini_get('safe_mode') ) $current_settings['safe_mode'] = 'on';
					else $current_settings['safe_mode'] = 'off';
				if ( ini_get('file_uploads') ) $current_settings['file_uploads'] = 'on';
					else $current_settings['file_uploads'] = 'off';
				$current_settings['memory_limit'] = (int)ini_get('memory_limit');
				$current_settings['post_max_size'] = (int)ini_get('post_max_size');
				$current_settings['upload_max_filesize'] = (int)ini_get('upload_max_filesize');
				$current_settings['max_input_time'] = (int)ini_get('max_input_time');
				$current_settings['max_execution_time'] = (int)ini_get('max_execution_time');

				$diff = array_diff_assoc($must_settings, $current_settings);

				if ( strcmp($must_settings["safe_mode"], $current_settings["safe_mode"]) )
					$result["safe_mode"] = $must_settings["safe_mode"];
				if ( strcmp($must_settings["file_uploads"], $current_settings["file_uploads"]) )
					$result["file_uploads"] = $must_settings["file_uploads"];

				foreach ($diff as $key => $value) {
					if ( $current_settings[$key] < $value ) {
						$result[$key] = $value;
					}
				}
				if ( !empty($result) ) {
					echo "<h4 class='title'>" . theme_locals('server_settings_error') . "</h4>";
					echo "<table width='50%' border='0' cellspacing='0' cellpadding='4' style='border-radius:3px; border-collapse: collapse;'>";
					echo "<thead><tr border='0' align='center' bgcolor='#87c1ee' style='color:#fff;'>";
					echo "<th style='border:1px solid #87c1ee;'>" . theme_locals('server_settings') . "</th>";
					echo "<th style='border:1px solid #87c1ee;'>" . theme_locals('current') . "</th>";
					echo "<th style='border:1px solid #87c1ee;'>" . theme_locals('required') . "</th>";
					echo "</tr></thead>";
					echo "<tbody>";
					$count = 0;
					foreach ($result as $key => $value) {
						$units = '';
						if ( $key=='memory_limit' || $key=='post_max_size' || $key=='upload_max_filesize' ) {
							$units = ' (Mb)';
						}
						if ( $key=='max_input_time' || $key=='max_execution_time' ) {
							$units = ' (s)';
						}
						echo "<tr>";
						echo "<td style='border:1px solid #9BCDF1;'>" . $key . $units . "</td>";
						echo "<td align='center' style='color:#BD362F; border:1px solid #9BCDF1;'>" . $current_settings[$key] . "</td>";
						echo "<td align='center' style='border:1px solid #9BCDF1;'>" . $must_settings[$key] . "</td>";
						$count++;
						if ( $count == 3 ) {
							echo "</tr>";
						}
					}
					echo "</tbody>";
					echo "</table>";
					echo "<div class='note'><p><strong>" . theme_locals('note') . ": </strong>" . theme_locals('settings_can_not_be_adjusted') . "</p>" . theme_locals('template_installation') . "</div>";
					// echo "<p class='text-style'>" . theme_locals('template_installation') . "</p>";
				}
				do_action('check_shop_activation');
			}

			/**
			 * Set post_status for default WP posts (post_status = draft)
			 */
			function set_to_draft($title) {
				global $wpdb;

				$id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '$title'");
				if ($id) {
					$default_post = array(
						'ID'           => $id,
						'post_status' => 'draft'
					);
					// Update the post into the database
					wp_update_post( $default_post );
				}
				$comment_id = $wpdb->get_var("SELECT comment_ID FROM $wpdb->comments WHERE comment_author = 'Mr WordPress'");
				if ($comment_id) wp_delete_comment($comment_id, false);
			}
		}

} // class_exists( 'MY_Importer' )