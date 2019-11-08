<?php
namespace LiteSpeed ;
defined( 'WPINC' ) || exit ;
?>

<h3 class="litespeed-title-short">
	<?php echo __( 'JS Settings', 'litespeed-cache' ) ; ?>
	<?php $this->learn_more( 'https://www.litespeedtech.com/support/wiki/doku.php/litespeed_wiki:cache:lscwp:configuration:js', false, 'litespeed-learn-more' ) ; ?>
</h3>

<table class="wp-list-table striped litespeed-table"><tbody>

	<tr>
		<th>
			<?php $id = Base::O_OPTM_JS_MIN ; ?>
			<?php $this->title( $id ) ; ?>
		</th>
		<td>
			<?php $this->build_switch( $id ) ; ?>
			<div class="litespeed-desc">
				<?php echo __( 'Minify JS files.', 'litespeed-cache' ) ; ?>
			</div>
		</td>
	</tr>

	<tr>
		<th>
			<?php $id = Base::O_OPTM_JS_COMB ; ?>
			<?php $this->title( $id ) ; ?>
		</th>
		<td>
			<?php $this->build_switch( $id ) ; ?>
			<div class="litespeed-desc">
				<?php echo __( 'Combine JS files.', 'litespeed-cache' ) ; ?>
				<a href="https://www.litespeedtech.com/support/wiki/doku.php/litespeed_wiki:cache:lscwp:optimize-issue" target="_blank"><?php echo __( 'How to Fix Problems Caused by CSS/JS Optimization.', 'litespeed-cache' ) ; ?></a>
			</div>
		</td>
	</tr>

	<tr>
		<th>
			<?php $id = Base::O_OPTM_JS_HTTP2 ; ?>
			<?php $this->title( $id ) ; ?>
		</th>
		<td>
			<?php $this->build_switch( $id ) ; ?>
			<div class="litespeed-desc">
				<?php echo __( 'Pre-send internal JS files to the browser before they are requested. (Requires the HTTP/2 protocol)', 'litespeed-cache' ) ; ?>
			</div>
		</td>
	</tr>

	<tr>
		<th>
			<?php $id = Base::O_OPTM_JS_DEFER ; ?>
			<?php $this->title( $id ) ; ?>
		</th>
		<td>
			<?php $this->build_switch( $id ) ; ?>
			<div class="litespeed-desc">
				<?php echo __( 'Doing so can help reduce resource contention and improve performance.', 'litespeed-cache' ) ; ?>
				<?php echo __( 'This can improve your speed score in services like Pingdom, GTmetrix and PageSpeed.', 'litespeed-cache' ) ; ?>
			</div>
		</td>
	</tr>

	<tr>
		<th>
			<?php $id = Base::O_OPTM_JS_INLINE_DEFER ; ?>
			<?php $this->title( $id ) ; ?>
		</th>
		<td>
			<div class="litespeed-switch">
				<?php $this->build_radio( $id, Base::VAL_OFF, __( 'Default', 'litespeed-cache' ) ) ; ?>
				<?php $this->build_radio( $id, 1, __( 'After Dom Ready', 'litespeed-cache' ) ) ; ?>
				<?php $this->build_radio( $id, 2, __( 'Deferred', 'litespeed-cache' ) ) ; ?>
			</div>
			<div class="litespeed-desc">
				<?php echo __( 'Load inline JS after dom is fully loaded.', 'litespeed-cache' ) ; ?>
				<br /><?php echo __( 'This can increase JS compatibility and reduce JS error when turned on the above JS optimization features.', 'litespeed-cache' ) ; ?>
			</div>
		</td>
	</tr>

	<tr>
		<th>
			<?php $id = Base::O_OPTM_EXC_JQ ; ?>
			<?php $this->title( $id ) ; ?>
		</th>
		<td>
			<?php $this->build_switch( $id ) ; ?>
			<div class="litespeed-desc">
				<?php echo sprintf( __( 'Improve compatibility with inline JS by preventing jQuery optimization. (Recommended Setting: %s)', 'litespeed-cache' ), __( 'ON', 'litespeed-cache' ) ) ; ?>
				<br /><font class="litespeed-warning">
					⚠️
					<?php echo sprintf( __( 'If there is any JS error related to %1$s when enabled %2$s, please turn on this option.', 'litespeed-cache' ), 'jQuery', __( 'JS Combine', 'litespeed-cache' ) ) ; ?>
				</font>
			</div>
		</td>
	</tr>

</tbody></table>