<template>
	<div class="vue-example">
		<div class="vue-example__container container">
			<BlockCount :title="title" :count="count" :inc="inc"/>
			<BlockList :title="subtitle" :pages="pages"/>
			<BlockGallery :slides="slides"/>
		</div>
	</div>
</template>

<script>
	import BlockCount from '../components/blocks/BlockCount.vue';
	import BlockList from '../components/blocks/BlockList.vue';
	import BlockGallery from '../components/blocks/BlockGallery';

	export default {
		beforeMount() {
			this.$store.dispatch('setPages');
		},

		data() {
			return {
				subtitle: 'This is subtitle',
				slides: [
					'https://images.unsplash.com/photo-1548199973-03cce0bbc87b',
					'https://images.unsplash.com/photo-1582068955580-dcc6c0812b21',
					'https://images.unsplash.com/photo-1444212477490-ca407925329e',
					'https://images.unsplash.com/photo-1532353949707-2e77707ee8a7',
					'https://images.unsplash.com/photo-1573394342377-09c9da05a3f8'

				]
			};
		},

		components: {
			BlockCount,
			BlockList,
			BlockGallery
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
