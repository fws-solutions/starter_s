<template>
	<div class="vue-listing">
		<div class="vue-listing__head">
			<SvgIcon class="vue-listing__icon" iconName="ico-dog"/>
			<h3 class="vue-listing__title" v-if="title">{{title}}</h3>
		</div>

		<PartIconList v-if="pagesData" :items="pagesData"/>
	</div>
</template>

<script>
	import SvgIcon from '../base/SvgIcon/SvgIcon.vue';
	import PartIconList from '../parts/PartIconList.vue';

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
			SvgIcon,
			PartIconList
		},
		computed: {
			pagesData() {
				if (!this.pages) {
					return null;
				}

				return this.pages.reduce((agg, cur) => {
					const page = {
						key: cur.id,
						url: cur.link,
						title: cur.title.rendered,
						target: '_blank',
						rel: 'noopener'
					};

					if (page.title) {
						agg.push(page);
					}

					return agg;
				}, []);
			}
		}
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
</style>
