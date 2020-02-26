<template>
	<div class="vue-listing">
		<div class="vue-listing__head">
			<SvgIcon class="vue-listing__icon" iconName="ico-happy"/>
			<h3 class="vue-listing__title" v-if="title">{{title}}</h3>
		</div>

		<ul class="vue-listing__list" v-if="pages">
			<li class="vue-listing__item" v-for="page in pages" :key="page.id">
				<a class="vue-listing__link" :href="page.link">{{page.title.rendered}}</a>
			</li>
		</ul>
	</div>
</template>

<script>
	import SvgIcon from '../parts/SvgIcon/SvgIcon.vue';

	export default {
		props: {
			title: {
				type: String
			},
			pages: {
				type: Array
			}
		},
		components: {
			SvgIcon
		},
	};
</script>

<style lang="scss" scoped>
	.vue-listing__head {
		display: flex;
		align-items: center;
		margin: 30px 0 10px;
	}

	.vue-listing__icon {
		color: $green;
		font-size: 30px;
		margin-right: 15px;
	}

	.vue-listing__title {
		font-size: 20px;
	}

	.vue-listing__list {
		padding-left: 15px;
		margin-left: 10px;
	}

	.vue-listing__item {
		list-style: initial;
	}

	.vue-listing__link:hover {
		color: $red;
	}
</style>
