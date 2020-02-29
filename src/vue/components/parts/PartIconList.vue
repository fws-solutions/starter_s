<template>
	<ul class="icon-list">
		<li class="icon-list__item" v-for="item in items" :key="item.key">
			<SvgIcon class="icon-list__icon" iconName="ico-happy"/>

			<span class="icon-list__content">
				<a
					v-if="item.url"
					class="icon-list__link"
					:href="item.url"
					:target="item.target ? item.target : null"
					:rel="item.rel ? item.rel : null"
				>
					{{item.title}}
				</a>
				<span v-else>{{item.title}}</span>
			</span>
		</li>
	</ul>
</template>

<script>
	import SvgIcon from '../base/SvgIcon/SvgIcon.vue';

	export default {
		props: {
			items: {
				type: Array,
				required: true
			}
		},
		components: {
			SvgIcon
		}
	};
</script>

<style lang="scss" scoped>
	.icon-list {
		padding-left: 15px;
		margin: 20px 0 0 10px;
	}

	.icon-list__item {
		list-style: initial;
		display: flex;
		align-items: center;
		margin-bottom: 10px;

		&:last-child {
			margin: 0;
		}
	}

	.icon-list__icon {
		color: $green;
		margin-right: 10px;
	}

	.icon-list__content {
		display: block;
	}

	.icon-list__link:hover {
		color: $red;
	}
</style>
