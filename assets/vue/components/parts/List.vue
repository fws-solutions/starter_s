<template>
	<div>
		<h3 class="vue-listing__subtitle" v-if="title">{{title}}</h3>

		<ul class="vue-listing__list" v-if="pages">
			<li class="vue-listing__item" v-for="page in pages" :key="page.id">
				<a class="vue-listing__link" :href="page.guid">{{page.title}}</a>
			</li>
		</ul>
	</div>
</template>

<script>
	export default {
		props: {
			title: {
				type: String
			},
			pages: {
				type: Array
			}
		}
	};
</script>

<style lang="scss" scoped>
	.vue-listing__subtitle {
		font-size: 20px;
		margin: 30px 0 10px;
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
