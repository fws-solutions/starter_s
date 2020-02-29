<template>
	<div class="vue-example">
		<div class="vue-example__container container">
			<BlockCount :title="title" :count="count" :inc="inc"/>
			<BlockList :title="subtitle" :pages="pages"/>
		</div>
	</div>
</template>

<script>
	import BlockCount from './blocks/BlockCount.vue';
	import BlockList from './blocks/BlockList.vue';

	export default {
		beforeMount() {
			this.$store.dispatch('setPages');
		},

		data() {
			return {
				subtitle: 'This is subtitle'
			};
		},

		components: {
			BlockCount,
			BlockList
		},

		computed: {
			title() {
				return this.$store.getters.getTitle;
			},
			count() {
				return this.$store.getters.getCount;
			},
			pages() {
				return this.$store.getters.getPages;
			}
		},

		methods: {
			inc() {
				this.$store.commit('increment');
			}
		}
	};
</script>

<style lang="scss" scoped>
	.vue-example {
		padding: 50px 0;
	}

	.vue-example__container {
		border-width: 1px 0 1px 0;
		border-color: rgba($black, .3);
		border-style: solid;
		padding-top: 50px;
		padding-bottom: 50px;
	}
</style>
