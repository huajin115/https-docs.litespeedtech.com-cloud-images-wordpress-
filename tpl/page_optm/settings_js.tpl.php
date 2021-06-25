<?php
namespace LiteSpeed;
defined( 'WPINC' ) || exit;
?>

<h3 class="litespeed-title-short">
	<?php echo __( 'JS Settings', 'litespeed-cache' ); ?>
	<?php Doc::learn_more( 'https://docs.litespeedtech.com/lscache/lscwp/pageopt/#js-settings-tab' ); ?>
</h3>

<table class="wp-list-table striped litespeed-table"><tbody>

	<tr>
		<th>
			<?php $id = Base::O_OPTM_JS_MIN; ?>
			<?php $this->title( $id ); ?>
		</th>
		<td>
			<?php $this->build_switch( $id ); ?>
			<div class="litespeed-desc">
				<?php echo __( 'Minify JS files and inline JS codes.', 'litespeed-cache' ); ?>
			</div>
		</td>
	</tr>

	<tr>
		<th>
			<?php $id = Base::O_OPTM_JS_COMB; ?>
			<?php $this->title( $id ); ?>
		</th>
		<td>
			<?php $this->build_switch( $id ); ?>
			<div class="litespeed-desc">
				<?php echo __( 'Combine all local JS files into a single file.', 'litespeed-cache' ); ?>
				<a href="https://docs.litespeedtech.com/lscache/lscwp/ts-optimize/" target="_blank"><?php echo __( 'How to Fix Problems Caused by CSS/JS Optimization.', 'litespeed-cache' ); ?></a>
				<br /><font class="litespeed-danger">
					🚨 <?php echo __( 'This option may result in JS error or layout issue on frontend pages on certain themes/plugins.', 'litespeed-cache' ); ?>
					<?php echo __( 'JS error can be found from the developer console of browser by right click and choose Inspect.', 'litespeed-cache' ); ?>
				</font>
			</div>
		</td>
	</tr>

	<tr>
		<th>
			<?php $id = Base::O_OPTM_JS_COMB_EXT_INL; ?>
			<?php $this->title( $id ); ?>
		</th>
		<td>
			<?php $this->build_switch( $id ); ?>
			<div class="litespeed-desc">
				<?php echo sprintf( __( 'Include external JS and inline JS in combined file when %1$s is also enabled. This option helps maintain the priorities of JS execution, which should minimize potential errors caused by JS Combine.', 'litespeed-cache' ), '<code>' . Lang::title( Base::O_OPTM_JS_COMB ) . '</code>' ); ?>
			</div>
		</td>
	</tr>

	<tr>
		<th>
			<?php $id = Base::O_OPTM_JS_HTTP2; ?>
			<?php $this->title( $id ); ?>
		</th>
		<td>
			<?php $this->build_switch( $id ); ?>
			<div class="litespeed-desc">
				<?php echo __( 'Pre-send internal JS files to the browser before they are requested. (Requires the HTTP/2 protocol)', 'litespeed-cache' ); ?>
			</div>
		</td>
	</tr>

	<tr>
		<th>
			<?php $id = Base::O_OPTM_JS_DEFER; ?>
			<?php $this->title( $id ); ?>
		</th>
		<td>
			<?php $this->build_switch( $id, array( __( 'OFF', 'litespeed-cache' ), __( 'Deferred', 'litespeed-cache' ), __( 'Delayed', 'litespeed-cache' ) ) ); ?>
			<div class="litespeed-desc">
				<?php echo __( 'Doing so can help reduce resource contention and improve performance causing a lower FID (Core Web Vitals metric).', 'litespeed-cache' ); ?>
				<?php Doc::learn_more( 'https://docs.litespeedtech.com/lscache/lscwp/pageopt/#load-js-deferred' ); ?><br />
				<?php echo __( 'This can improve your speed score in services like Pingdom, GTmetrix and PageSpeed.', 'litespeed-cache' ); ?>
				<?php Doc::learn_more( 'https://web.dev/fid/#what-is-fid' ); ?>
				<br /><font class="litespeed-danger">
					🚨 <?php echo __( 'This option may result in JS error or layout issue on frontend pages on certain themes/plugins.', 'litespeed-cache' ); ?>
				</font>
			</div>
		</td>
	</tr>

</tbody></table>
