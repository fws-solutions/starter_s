<template>
	<VueSlickCarousel
		class="gallery"
		:slidesToShow="4"
		:slidesToScroll="1"
		:arrows="false"
	>
		<PartMediaItem
			class="gallery__item"
			v-for="(slide, i) in slides"
			:src="slide"
			size="square"
			:key="`galslide-${i}`"
			lazyloading
		/>
	</VueSlickCarousel>
</template>

<script>
import PartMediaItem from '@/vue/components/parts/PartMediaItem';
import VueSlickCarousel from 'vue-slick-carousel';
import LazyLoad from 'vanilla-lazyload';

export default {
	props: {
		slides: {
			type: Array,
			required: true
		}
	},
	data() {
		return {
			lazyload: null
		};
	},
	components: {
		PartMediaItem,
		VueSlickCarousel
	},
	mounted() {
		this.lazyload = new LazyLoad({
			// Avoid executing the function multiple times
			unobserve_entered: true, // eslint-disable-line camelcase
			callback_loaded: (el) => {el.parentNode.classList.add('is-hidden');}
		});
	}
};
</script>

<style lang="scss" scoped>
.gallery {
	/deep/ {
		.slick-slide > div {
			outline: 0;
		}

		.media-wrap {
			outline: 0;
			display: block !important;
		}

		.media-item {
			pointer-events: none;
		}
	}
}
</style>
