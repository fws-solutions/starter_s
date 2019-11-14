<template>
	<div>
		<h2 class="vue-count__title section-title">{{title}}</h2>

		<div class="entry-content">
			<p>This count is: <strong>{{count}}</strong></p>
		</div>

		<button class="vue-block__btn btn" @click="inc">Incrament!</button>
	</div>
</template>

<script>
	export default {
		props: {
			title: {
				type: String,
				required: true
			},
			count: {
				type: Number,
				required: true
			},
			inc: {
				type: Function,
				required: true
			}
		}
	};
</script>

<style lang="scss" scoped>
	.vue-count__title {
		margin-bottom: 30px;
		color: $red;
	}

	.vue-count__btn {
		margin-top: 10px;
	}
</style>
