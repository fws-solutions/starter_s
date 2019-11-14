<template>
	<div class="vue-block">
		<div class="vue-block__container container">
			<h1 class="vue-block__title section-title">{{title}}</h1>

			<div class="entry-content">
				<p>This count is: <strong>{{count}}</strong></p>
			</div>

			<button class="vue-block__btn btn" @click="inc">Incrament!</button>
		</div>
	</div>
</template>

<script>
	export default {
		computed: {
			title() {
				return this.$store.getters.getTitle;
			},
			count() {
				return this.$store.getters.getCount;
			}
		},
		methods: {
			inc() {
				this.$store.commit('increment');
			}
		}
	};
</script>

<style lang="scss">
	.vue-block {
		padding: 50px 0;
	}

	.vue-block__container {
		border-width: 1px 0 1px 0;
		border-color: rgba($black, .3);
		border-style: solid;
		padding-top: 50px;
		padding-bottom: 50px;
	}

	.vue-block__title {
		margin-bottom: 30px;
		color: $red;
	}

	.vue-block__btn {
		margin-top: 30px;
	}
</style>
