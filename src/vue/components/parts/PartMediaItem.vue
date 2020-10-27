<template>
	<div
		class="media-wrap"
		:class="`${lazyloading ? 'media-wrap--lazy-loader ' : ''}media-wrap--${size}`"
	>
		<img
			class="media-item cover-img"
			:class="lazyloading ? 'lazy' : ''"
			:src="lazyloading ? '' : src"
			:data-src="lazyloading ? src : undefined"
			:alt="alt"
		>
	</div>
</template>

<script>
export default {
	props: {
		src: {
			type: String,
			required: true
		},
		size: {
			type: String,
			required: true
		},
		alt: {
			type: String,
			default: ''
		},
		lazyloading: {
			type: Boolean
		}
	}
};
</script>
