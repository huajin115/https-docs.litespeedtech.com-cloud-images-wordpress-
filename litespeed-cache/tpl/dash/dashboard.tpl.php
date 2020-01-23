<?php
namespace LiteSpeed;
defined( 'WPINC' ) || exit;

$lscache_stats = GUI::get_instance()->lscache_stats();

$health_scores = Health::get_instance()->scores();

$crawler_summary = Crawler::get_summary();

// Image related info
$optm_summary = Img_Optm::get_summary();
$img_count = Img_Optm::get_instance()->img_count();
if ( ! empty( $img_count[ 'groups_all' ] ) ) {
	$img_gathered_percentage = 100 - floor( $img_count[ 'groups_not_gathered' ] * 100 / $img_count[ 'groups_all' ] );
}
else {
	$img_gathered_percentage = 0;
}

if ( ! empty( $img_count[ 'imgs_gathered' ] ) ) {
	$img_finished_percentage = 100 - floor( $img_count[ 'img.' . Img_Optm::STATUS_RAW ] * 100 / $img_count[ 'imgs_gathered' ] );
}
else {
	$img_finished_percentage = 0;
}

$cloud_summary = Cloud::get_summary();
$css_summary = CSS::get_summary();
$placeholder_summary = Placeholder::get_summary();

?>

<div class="litespeed-dashboard">


	<div class="litespeed-dashboard-header">
		<h3 class="litespeed-dashboard-title">
			<?php echo __( 'Usage Statistics', 'litespeed-cache' ); ?>
			<a href="<?php echo Utility::build_url( Router::ACTION_CLOUD, Cloud::TYPE_SYNC_USAGE ); ?>">
				<span class="dashicons dashicons-update"></span>
				<span class="screen-reader-text"><?php echo __( 'Sync data from Cloud', 'litespeed-cache' ); ?></span>
			</a>
		</h3>
		<hr>
		<a href="#" target="_blank" class="litespeed-learn-more"><?php echo __( 'Learn More', 'litespeed-cache' );?></a>
	</div>

	<div class="litespeed-dashboard-stats-wrapper">
		<?php
		$cat_list = array(
			'img_optm'	=> __( 'Image Optimization', 'litespeed-cache' ),
			'ccss'		=> __( 'CCSS', 'litespeed-cache' ),
			'cdn'		=> __( 'CDN Bandwidth', 'litespeed-cache' ),
			'lqip'		=> __( 'LQIP', 'litespeed-cache' ),
		);
		if ( ! Conf::val( Base::O_MEDIA_PLACEHOLDER_LQIP ) ) {
			$cat_list[ 'placeholder' ] = __( 'Placeholder', 'litespeed-cache' );
		}

		foreach ( $cat_list as $svc => $title ) :
			$finished_percentage = 0;
			$used = $quota = $pag_used = $pag_total = '-';
			$pag_width = 0;
			if ( ! empty( $cloud_summary[ 'usage.' . $svc ] ) ) {
				$finished_percentage = floor( $cloud_summary[ 'usage.' . $svc ][ 'used' ] * 100 / $cloud_summary[ 'usage.' . $svc ][ 'quota' ] );
				$used = $cloud_summary[ 'usage.' . $svc ][ 'used' ];
				$quota = $cloud_summary[ 'usage.' . $svc ][ 'quota' ];
				$pag_used = ! empty( $cloud_summary[ 'usage.' . $svc ][ 'pag_used' ] ) ? $cloud_summary[ 'usage.' . $svc ][ 'pag_used' ] : 0;
				$pag_bal = ! empty( $cloud_summary[ 'usage.' . $svc ][ 'pag_bal' ] ) ? $cloud_summary[ 'usage.' . $svc ][ 'pag_bal' ] : 0;
				$pag_total = $pag_used + $pag_bal;

				if ( $pag_total ) {
					$pag_width = round( $pag_used / $pag_total * 100 ) . '%';
				}

				if ( $svc == 'cdn' ) {
					$used = Utility::real_size( $used * 1024 * 1024 );
					$quota = Utility::real_size( $quota * 1024 * 1024 );
					$pag_used = Utility::real_size( $pag_used * 1024 * 1024 );
					$pag_total = Utility::real_size( $pag_total * 1024 * 1024 );
				}
			}
		?>
			<div class="postbox litespeed-postbox">
				<div class="inside">
					<h3 class="litespeed-title"><?php echo $title; ?></h3>

					<div class="litespeed-flex-container">
						<div class="litespeed-icon-vertical-middle">
							<?php echo GUI::pie( $finished_percentage, 70, true ); ?>
						</div>
						<div>
							<div class="litespeed-dashboard-stats">
								<h3><?php echo __('Used','litespeed-cache'); ?></h3>
								<p><strong><?php echo $used; ?></strong> <span class="litespeed-desc">of <?php echo $quota; ?></span></p>
								<p class="litespeed-desc" style="background-color: pink;" title="Pay As You Go"><span style="background-color: cyan;width: <?php echo $pag_width; ?>"><?php echo $pag_used; ?> / <?php echo $pag_total; ?><span></p>
							</div>
						</div>
					</div>

				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="litespeed-dashboard-group">
		<hr>
		<div class="litespeed-flex-container">

			<div class="postbox litespeed-postbox">
				<div class="inside">
					<h3 class="litespeed-title">
						<?php echo __( 'Page Load Time', 'litespeed-cache' ); ?>
						<a href="<?php echo Utility::build_url( Router::ACTION_HEALTH, Health::TYPE_SPEED ); ?>">
							<span class="dashicons dashicons-update"></span>
							<span class="screen-reader-text"><?php echo __('Refresh page load time', 'litespeed-cache'); ?></span>
						</a>
					</h3>

					<div>
						<div class="litespeed-row-flex" style="margin-left: -10px;">
							<?php if ( $health_scores[ 'speed_before' ] ) : ?>
							<div class="litespeed-width-1-3 litespeed-padding-space litespeed-margin-x5">
								<div>
									<p class="litespeed-text-grey litespeed-margin-y-remove">
										<?php echo __( 'Before', 'litespeed-cache' ); ?>
									</p>
								</div>
								<div class="litespeed-top10 litespeed-text-jumbo litespeed-text-grey">
									<?php echo $health_scores[ 'speed_before' ]; ?><span class="litespeed-text-large">s</span>
								</div>

							</div>
							<div class="litespeed-width-1-3 litespeed-padding-space litespeed-margin-x5">
								<div>
									<p class="litespeed-text-grey litespeed-margin-y-remove">
										<?php echo __( 'After', 'litespeed-cache' ); ?>
									</p>
								</div>
								<div class="litespeed-top10 litespeed-text-jumbo litespeed-success">
									<?php echo $health_scores[ 'speed_after' ]; ?><span class="litespeed-text-large">s</span>
								</div>
							</div>
							<div class="litespeed-width-1-3 litespeed-padding-space litespeed-margin-x5">
								<div>
									<p class="litespeed-text-grey litespeed-margin-y-remove" style="white-space: nowrap;">
										<?php echo __( 'Improved by', 'litespeed-cache' ); ?>
									</p>
								</div>
								<div class="litespeed-top10 litespeed-text-jumbo litespeed-text-fern">
									<?php echo $health_scores[ 'speed_improved' ]; ?><span class="litespeed-text-large">%</span>
								</div>
							</div>
							<?php endif; ?>

						</div>
					</div>
				</div>

				<?php if ( ! empty( $cloud_summary[ 'last_request.health-speed' ] ) ) : ?>
					<div class="inside litespeed-postbox-footer litespeed-postbox-footer--compact">
						<?php echo __( 'Last requested', 'litespeed-cache' ) . ': ' . Utility::readable_time( $cloud_summary[ 'last_request.health-speed' ] ) ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="postbox litespeed-postbox">
				<div class="inside">
					<h3 class="litespeed-title">
						<?php echo __( 'PageSpeed Score', 'litespeed-cache' ); ?>
						<a href="<?php echo Utility::build_url( Router::ACTION_HEALTH, Health::TYPE_SCORE ); ?>">
							<span class="dashicons dashicons-update"></span>
							<span class="screen-reader-text"><?php echo __('Refresh page score', 'litespeed-cache'); ?></span>
						</a>
					</h3>

					<div>

						<div class="litespeed-margin-bottom20">
							<div class="litespeed-row-flex" style="margin-left: -10px;">

							<?php if ( ! empty( $health_scores[ 'score_before' ] ) ) : ?>
								<div class="litespeed-width-1-3 litespeed-padding-space litespeed-margin-x5">
									<div>
										<p class="litespeed-text-grey litespeed-text-center litespeed-margin-y-remove">
											<?php echo __( 'Before', 'litespeed-cache' ); ?>
										</p>
									</div>
									<div class="litespeed-promo-score" style="margin-top:-5px;">
										<?php echo GUI::pie( $health_scores[ 'score_before' ], 45, false, true, 'litespeed-pie-' . GUI::get_instance()->get_cls_of_pagescore( $health_scores[ 'score_before' ] ) ); ?>
									</div>
								</div>
								<div class="litespeed-width-1-3 litespeed-padding-space litespeed-margin-x5">
									<div>
										<p class="litespeed-text-grey litespeed-text-center litespeed-margin-y-remove">
											<?php echo __( 'After', 'litespeed-cache' ); ?>
										</p>
									</div>
									<div class="litespeed-promo-score" style="margin-top:-5px;">
										<?php echo GUI::pie( $health_scores[ 'score_after' ], 45, false, true, 'litespeed-pie-' . GUI::get_instance()->get_cls_of_pagescore( $health_scores[ 'score_after' ] ) ); ?>
									</div>
								</div>
								<div class="litespeed-width-1-3 litespeed-padding-space litespeed-margin-x5">
									<div>
										<p class="litespeed-text-grey litespeed-margin-y-remove" style="white-space: nowrap;">
											<?php echo __( 'Improved by', 'litespeed-cache' ); ?>
										</p>
									</div>
									<div class="litespeed-top10 litespeed-text-jumbo litespeed-text-fern">
										<?php echo $health_scores[ 'score_improved' ]; ?><span class="litespeed-text-large">%</span>
									</div>
								</div>
							<?php endif; ?>

							</div>

						</div>
					</div>
				</div>

				<?php if ( ! empty( $cloud_summary[ 'last_request.health-score' ] ) ) : ?>
					<div class="inside litespeed-postbox-footer litespeed-postbox-footer--compact">
						<?php echo __( 'Last requested' ) . ': ' . Utility::readable_time( $cloud_summary[ 'last_request.health-score' ] ) ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="postbox litespeed-postbox">
				<div class="inside">
					<h3 class="litespeed-title">
						<?php echo __( 'Cache Status', 'litespeed-cache' ); ?>
					</h3>

				<?php
					$cache_list = array(
						Base::O_CACHE			=> __( 'Public Cache', 'litespeed-cache' ),
						Base::O_CACHE_PRIV		=> __( 'Private Cache', 'litespeed-cache' ),
						Base::O_OBJECT			=> __( 'Object Cache', 'litespeed-cache' ),
						Base::O_CACHE_BROWSER	=> __( 'Browser Cache', 'litespeed-cache' ),
					);
					foreach ( $cache_list as $id => $title ) :
						$v = Conf::val( $id );
				?>
						<p>
							<?php if ( $v ) : ?>
								<span class="litespeed-label-success litespeed-label-dashboard">ON</span>
							<?php else: ?>
								<span class="litespeed-label-danger litespeed-label-dashboard">OFF</span>
							<?php endif; ?>
							<?php echo $title; ?>
						</p>
					<?php endforeach; ?>
				</div>
				<div class="inside litespeed-postbox-footer litespeed-postbox-footer--compact">
					<div>
						<a href="<?php echo admin_url( 'admin.php?page=litespeed-cache' ); ?>">Manage Cache</a>
					</div>
				</div>
			</div>

			<?php if ( $lscache_stats ) : ?>
			<div class="postbox litespeed-postbox">
				<div class="inside">
					<h3 class="litespeed-title">
						<?php echo __( 'Cache Stats', 'litespeed-cache' ); ?>
					</h3>

				<?php foreach ( $lscache_stats as $title => $val ) : ?>
					<p><?php echo $title; ?>: <?php echo $val ? "<code>$val</code>" : '-'; ?></p>
				<?php endforeach; ?>

				</div>
			</div>
			<?php endif; ?>

			<div class="postbox litespeed-postbox">
				<div class="inside">
					<h3 class="litespeed-title">
						<?php echo __( 'Critical CSS', 'litespeed-cache' ); ?>
					</h3>

					<?php if ( ! empty( $css_summary[ 'last_request' ] ) ) : ?>
						<p>
							<?php echo __( 'Last generated', 'litespeed-cache' ) . ': <code>' . Utility::readable_time( $css_summary[ 'last_request' ] ) . '</code>'; ?>
						</p>
						<p>
							<?php echo __( 'Last requested cost', 'litespeed-cache' ) . ': <code>' . $css_summary[ 'last_spent' ] . 's</code>'; ?>
						</p>
					<?php endif; ?>

					<p>
						<?php echo __( 'Requests in queue', 'litespeed-cache' ); ?>: <code><?php echo ! empty( $css_summary[ 'queue' ] ) ? count( $css_summary[ 'queue' ] ) : '-' ?></code>
						<a href="<?php echo ! empty( $css_summary[ 'queue' ] ) ? Utility::build_url( Router::ACTION_CSS, CSS::TYPE_GENERATE_CRITICAL ) : 'javascript:;'; ?>" class="button button-secondary button-small <?php if ( empty( $css_summary[ 'queue' ] ) ) echo 'disabled'; ?>">
							<?php echo __( 'Force cron', 'litespeed-cache' ); ?>
						</a>
					</p>

				</div>

				<?php if ( ! empty( $cloud_summary[ 'last_request.ccss' ] ) ) : ?>
					<div class="inside litespeed-postbox-footer litespeed-postbox-footer--compact">
						<?php echo __( 'Last requested' ) . ': ' . Utility::readable_time( $cloud_summary[ 'last_request.ccss' ] ) ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="postbox litespeed-postbox">
				<div class="inside">
					<h3 class="litespeed-title">
						<?php echo __( 'LQIP Placeholder', 'litespeed-cache' ); ?>
					</h3>

					<?php if ( ! empty( $placeholder_summary[ 'last_request' ] ) ) : ?>
						<p>
							<?php echo __( 'Last generated', 'litespeed-cache' ) . ': <code>' . Utility::readable_time( $placeholder_summary[ 'last_request' ] ) . '</code>'; ?>
						</p>
						<p>
							<?php echo __( 'Last requested cost', 'litespeed-cache' ) . ': <code>' . $placeholder_summary[ 'last_spent' ] . 's</code>'; ?>
						</p>
					<?php endif; ?>

					<p>
						<?php echo __( 'Requests in queue', 'litespeed-cache' ); ?>: <code><?php echo ! empty( $placeholder_summary[ 'queue' ] ) ? count( $placeholder_summary[ 'queue' ] ) : '-' ?></code>
						<a href="<?php echo ! empty( $placeholder_summary[ 'queue' ] ) ? Utility::build_url( Router::ACTION_PLACEHOLDER, Placeholder::TYPE_GENERATE ) : 'javascript:;'; ?>" class="button button-secondary button-small <?php if ( empty( $placeholder_summary[ 'queue' ] ) ) echo 'disabled'; ?>">
							<?php echo __( 'Force cron', 'litespeed-cache' ); ?>
						</a>
					</p>

				</div>

				<?php if ( ! empty( $cloud_summary[ 'last_request.lqip' ] ) ) : ?>
					<div class="inside litespeed-postbox-footer litespeed-postbox-footer--compact">
						<?php echo __( 'Last requested' ) . ': ' . Utility::readable_time( $cloud_summary[ 'last_request.lqip' ] ) ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="postbox litespeed-postbox">
				<div class="inside">
					<h3 class="litespeed-title">
						<?php echo __( 'Crawler Status', 'litespeed-cache' ); ?>
					</h3>

					<p>
						<code><?php echo count( Crawler::get_instance()->list_crawlers() );?></code> <?php echo __( 'Crawler(s)', 'litespeed-cache' ); ?>
					</p>
					<p>
						<?php echo __( 'Current on crawler', 'litespeed-cache' ); ?>: <code><?php echo $crawler_summary[ 'curr_crawler' ] ?></code>
					</p>

					<?php if ( $crawler_summary[ 'curr_crawler_beginning_time' ] ) : ?>
					<p>
						<b><?php echo __('Current crawler started at', 'litespeed-cache'); ?>:</b>
						<?php echo Utility::readable_time( $crawler_summary[ 'curr_crawler_beginning_time' ] ); ?>
					</p>
					<?php endif; ?>

					<?php if ( $crawler_summary[ 'last_start_time' ] ) : ?>
					<p class='litespeed-desc'>
						<b><?php echo __('Last interval', 'litespeed-cache'); ?>:</b>
						<?php echo Utility::readable_time( $crawler_summary[ 'last_start_time' ] ); ?>
					</p>
					<?php endif; ?>

					<?php if ( $crawler_summary[ 'end_reason' ] ) : ?>
					<p class='litespeed-desc'>
						<b><?php echo __( 'Ended reason', 'litespeed-cache' ); ?>:</b>
						<?php echo $crawler_summary[ 'end_reason' ]; ?>
					</p>
					<?php endif; ?>

					<?php if ( $crawler_summary[ 'last_crawled' ] ) : ?>
					<p class='litespeed-desc'>
						<?php echo sprintf(__('<b>Last crawled:</b> %s item(s)', 'litespeed-cache'), $crawler_summary[ 'last_crawled' ] ); ?>
					</p>
					<?php endif; ?>

				</div>
				<div class="inside litespeed-postbox-footer litespeed-postbox-footer--compact">
					<a href="<?php echo admin_url( 'admin.php?page=litespeed-crawler' ); ?>"><?php echo __( 'Manage Crawler', 'litespeed-cache' ); ?></a>
				</div>
			</div>

			<div class="postbox litespeed-postbox">
				<div class="inside">
					<h3 class="litespeed-title">
						<?php echo __( 'Image Optimization Summary', 'litespeed-cache' ); ?>
					</h3>

					<div class="litespeed-flex-container">
						<div class="litespeed-icon-vertical-middle">
							<?php echo GUI::pie( $img_gathered_percentage, 70, true ); ?>
						</div>
						<div>
							<div class="litespeed-dashboard-stats">
								<h3><?php echo __('Image Prepared','litespeed-cache'); ?></h3>
								<p>
									<strong><?php echo Admin_Display::print_plural( $img_count[ 'groups_all' ] - $img_count[ 'groups_not_gathered' ] ); ?></strong>
									<span class="litespeed-desc">of <?php echo Admin_Display::print_plural( $img_count[ 'groups_all' ] ); ?></span>
								</p>
							</div>
						</div>
					</div>

					<div class="litespeed-flex-container">
						<div class="litespeed-icon-vertical-middle">
							<?php echo GUI::pie( $img_finished_percentage, 70, true ); ?>
						</div>
						<div>
							<div class="litespeed-dashboard-stats">
								<h3><?php echo __('Image Requested','litespeed-cache'); ?></h3>
								<p>
									<strong><?php echo Admin_Display::print_plural( $img_count[ 'imgs_gathered' ] - $img_count[ 'img.' . Img_Optm::STATUS_RAW ], 'image' ); ?></strong>
									<span class="litespeed-desc">of <?php echo Admin_Display::print_plural( $img_count[ 'imgs_gathered' ], 'image' ); ?></span>
								</p>
							</div>
						</div>
					</div>

					<?php if ( ! empty( $img_count[ 'group.' . Img_Optm::STATUS_REQUESTED ] ) ) : ?>
					<p class="litespeed-success">
						<?php echo __('Images requested', 'litespeed-cache'); ?>:
						<code>
							<?php echo Admin_Display::print_plural( $img_count[ 'group.' . Img_Optm::STATUS_REQUESTED ] ); ?>
							(<?php echo Admin_Display::print_plural( $img_count[ 'img.' . Img_Optm::STATUS_REQUESTED ], 'image' ); ?>)
						</code>
					</p>
					<?php endif; ?>

					<?php if ( ! empty( $img_count[ 'group.' . Img_Optm::STATUS_NOTIFIED ] ) ) : ?>
						<p class="litespeed-success">
							<?php echo __('Images notified to pull', 'litespeed-cache'); ?>:
							<code>
								<?php echo Admin_Display::print_plural( $img_count[ 'group.' . Img_Optm::STATUS_NOTIFIED ] ); ?>
								(<?php echo Admin_Display::print_plural( $img_count[ 'img.' . Img_Optm::STATUS_NOTIFIED ], 'image' ); ?>)
							</code>

						</p>
					<?php endif; ?>

					<p>
						<?php echo __( 'Last Request', 'litespeed-cache' ); ?>: <code><?php echo ! empty( $optm_summary[ 'last_requested' ] ) ? Utility::readable_time( $optm_summary[ 'last_requested' ] ) : '-'; ?></code>
					</p>
					<p>
						<?php echo __( 'Last Pull', 'litespeed-cache' ); ?>: <code><?php echo ! empty( $optm_summary[ 'last_pull' ] ) ? Utility::readable_time( $optm_summary[ 'last_pull' ] ) : '-'; ?></code>
					</p>

					<?php
					$cache_list = array(
						Base::O_IMG_OPTM_AUTO	=> __( 'Auto Request Cron', 'litespeed-cache' ),
						Base::O_IMG_OPTM_CRON	=> __( 'Auto Pull Cron', 'litespeed-cache' ),
					);
					foreach ( $cache_list as $id => $title ) :
						$v = Conf::val( $id );
					?>
						<p>
							<?php if ( $v ) : ?>
								<span class="litespeed-label-success litespeed-label-dashboard">ON</span>
							<?php else: ?>
								<span class="litespeed-label-danger litespeed-label-dashboard">OFF</span>
							<?php endif; ?>
							<?php echo $title; ?>
						</p>
					<?php endforeach; ?>

				</div>
			</div>


		</div>

	</div>


</div>