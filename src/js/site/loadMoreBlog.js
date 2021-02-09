const $ = jQuery.noConflict();

'use strict';
const LoadMoreBlog = {
	$domLoadBtn: $('.js-load-more'),

	init: function() {
		this.bindEvents();
	},

	bindEvents: function() {
		const _this = this;

		this.$domLoadBtn.on('click', function() {
			_this.loadBlogPostsDummy();

			// USING JAVASCRIPT OLD SCHOOL
			// var request = new XMLHttpRequest();
			// request.open('GET', 'https://jsonplaceholder.typicode.com/todos/1', true);
			//
			// request.onload = function() {
			// 	if (this.status >= 200 && this.status < 400) {
			// 		console.log(JSON.parse(this.response));
			// 	}
			// };
			//
			// request.send();

			// USING JAVASCRIPT FETCH
			// fetch('https://jsonplaceholder.typicode.com/todos/1')
			// 	.then(function(response) {
			// 		return response.json();
			// 	})
			// 	.then(function(json) {
			// 		console.log(json);
			// 	})

			// USING JQUERY
			// $.ajax({
			// 	method: 'GET',
			// 	url: 'https://jsonplaceholder.typicode.com/todos/1',
			// 	success: function(response) {
			// 		console.log(response);
			// 	},
			// 	error: function(error) {
			//
			// 	}
			// });
		});
	},

	loadBlogPosts: function() {
		$.ajax({
			method: 'POST',
			url: window.fwsLocalized.ajaxUrl,
			data: {
				action: 'testAjaxData',
			},
			success: function(response) {
				$('.blog-listing').find('.row').append(response);
			}
		});
	},

	// todo - make ajax request from this
	loadBlogPostsDummy: function() {
		setTimeout(function() {
			const response = '<article id="post-1" class="blog-article col-lg-4 post-1 post type-post status-publish format-standard hentry category-uncategorized"> <a class="blog-article__thumb-wrap" href="http://starter.lndo.site/2020/12/08/hello-world/"><div class="blog-article__thumb media-wrap media-wrap--400x280"> <img class="media-item cover-img" src="http://starter.lndo.site/wp-content/themes/starter_s/src/assets/images/post-thumb.jpg" alt=""></div> </a><div class="blog-article__box"><h2 class="blog-article__title"> <a class="blog-article__link" href="http://starter.lndo.site/2020/12/08/hello-world/">Hello world!</a></h2><div class="blog-article__meta entry-meta"><div class="entry-meta"><span class="posted-on">Posted on <a href="http://starter.lndo.site/2020/12/08/hello-world/" class="post_url"><span>December 8, 2020</span></a> by <a class="author_name" href="http://starter.lndo.site/author/starter/">starter</a></span></div></div></div> </article><article id="post-1788" class="blog-article col-lg-4 post-1788 post type-post status-publish format-standard hentry category-block tag-content tag-image"> <a class="blog-article__thumb-wrap" href="http://starter.lndo.site/2018/11/03/block-image/"><div class="blog-article__thumb media-wrap media-wrap--400x280"> <img class="media-item cover-img" src="http://starter.lndo.site/wp-content/themes/starter_s/src/assets/images/post-thumb.jpg" alt=""></div> </a><div class="blog-article__box"><h2 class="blog-article__title"> <a class="blog-article__link" href="http://starter.lndo.site/2018/11/03/block-image/">Block: Image</a></h2><div class="blog-article__meta entry-meta"><div class="entry-meta"><span class="posted-on">Posted on <a href="http://starter.lndo.site/2018/11/03/block-image/" class="post_url"><span>November 3, 2018</span></a> by <a class="author_name" href="http://starter.lndo.site/author/starter/">starter</a></span></div></div></div> </article><article id="post-1785" class="blog-article col-lg-4 post-1785 post type-post status-publish format-standard hentry category-block tag-content"> <a class="blog-article__thumb-wrap" href="http://starter.lndo.site/2018/11/03/block-button/"><div class="blog-article__thumb media-wrap media-wrap--400x280"> <img class="media-item cover-img" src="http://starter.lndo.site/wp-content/themes/starter_s/src/assets/images/post-thumb.jpg" alt=""></div> </a><div class="blog-article__box"><h2 class="blog-article__title"> <a class="blog-article__link" href="http://starter.lndo.site/2018/11/03/block-button/">Block: Button</a></h2><div class="blog-article__meta entry-meta"><div class="entry-meta"><span class="posted-on">Posted on <a href="http://starter.lndo.site/2018/11/03/block-button/" class="post_url"><span>November 3, 2018</span></a> by <a class="author_name" href="http://starter.lndo.site/author/starter/">starter</a></span></div></div></div> </article><article id="post-1784" class="blog-article col-lg-4 post-1784 post type-post status-publish format-standard hentry category-block tag-image"> <a class="blog-article__thumb-wrap" href="http://starter.lndo.site/2018/11/03/block-cover/"><div class="blog-article__thumb media-wrap media-wrap--400x280"> <img class="media-item cover-img" src="http://starter.lndo.site/wp-content/themes/starter_s/src/assets/images/post-thumb.jpg" alt=""></div> </a><div class="blog-article__box"><h2 class="blog-article__title"> <a class="blog-article__link" href="http://starter.lndo.site/2018/11/03/block-cover/">Block: Cover</a></h2><div class="blog-article__meta entry-meta"><div class="entry-meta"><span class="posted-on">Posted on <a href="http://starter.lndo.site/2018/11/03/block-cover/" class="post_url"><span>November 3, 2018</span></a> by <a class="author_name" href="http://starter.lndo.site/author/starter/">starter</a></span></div></div></div> </article><article id="post-1787" class="blog-article col-lg-4 post-1787 post type-post status-publish format-standard has-post-thumbnail hentry category-block tag-columns tag-content tag-gallery tag-image"> <a class="blog-article__thumb-wrap" href="http://starter.lndo.site/2018/11/03/block-gallery/"><div class="blog-article__thumb media-wrap media-wrap--400x280"> <img width="400" height="280" src="http://starter.lndo.site/wp-content/uploads/2008/06/img_8399-1-400x280.jpg" class="media-item cover-img wp-post-image" alt="Boat Barco Texture" loading="lazy"></div> </a><div class="blog-article__box"><h2 class="blog-article__title"> <a class="blog-article__link" href="http://starter.lndo.site/2018/11/03/block-gallery/">Block: Gallery</a></h2><div class="blog-article__meta entry-meta"><div class="entry-meta"><span class="posted-on">Posted on <a href="http://starter.lndo.site/2018/11/03/block-gallery/" class="post_url"><span>November 3, 2018</span></a> by <a class="author_name" href="http://starter.lndo.site/author/starter/">starter</a></span></div></div></div> </article><article id="post-1783" class="blog-article col-lg-4 post-1783 post type-post status-publish format-standard hentry category-block tag-columns tag-content"> <a class="blog-article__thumb-wrap" href="http://starter.lndo.site/2018/11/02/column-blocks/"><div class="blog-article__thumb media-wrap media-wrap--400x280"> <img class="media-item cover-img" src="http://starter.lndo.site/wp-content/themes/starter_s/src/assets/images/post-thumb.jpg" alt=""></div> </a><div class="blog-article__box"><h2 class="blog-article__title"> <a class="blog-article__link" href="http://starter.lndo.site/2018/11/02/column-blocks/">Block: Columns</a></h2><div class="blog-article__meta entry-meta"><div class="entry-meta"><span class="posted-on">Posted on <a href="http://starter.lndo.site/2018/11/02/column-blocks/" class="post_url"><span>November 2, 2018</span></a> by <a class="author_name" href="http://starter.lndo.site/author/starter/">starter</a></span></div></div></div> </article><article id="post-1786" class="blog-article col-lg-4 post-1786 post type-post status-publish format-standard hentry category-block tag-content"> <a class="blog-article__thumb-wrap" href="http://starter.lndo.site/2018/11/01/block-quotes/"><div class="blog-article__thumb media-wrap media-wrap--400x280"> <img class="media-item cover-img" src="http://starter.lndo.site/wp-content/themes/starter_s/src/assets/images/post-thumb.jpg" alt=""></div> </a><div class="blog-article__box"><h2 class="blog-article__title"> <a class="blog-article__link" href="http://starter.lndo.site/2018/11/01/block-quotes/">Block: Quote</a></h2><div class="blog-article__meta entry-meta"><div class="entry-meta"><span class="posted-on">Posted on <a href="http://starter.lndo.site/2018/11/01/block-quotes/" class="post_url"><span>November 1, 2018</span></a> by <a class="author_name" href="http://starter.lndo.site/author/starter/">starter</a></span></div></div></div> </article><article id="post-1778" class="blog-article col-lg-4 post-1778 post type-post status-publish format-standard hentry category-block tag-content tag-embeds-2 tag-gallery tag-image tag-video"> <a class="blog-article__thumb-wrap" href="http://starter.lndo.site/2018/11/01/block-category-common/"><div class="blog-article__thumb media-wrap media-wrap--400x280"> <img class="media-item cover-img" src="http://starter.lndo.site/wp-content/themes/starter_s/src/assets/images/post-thumb.jpg" alt=""></div> </a><div class="blog-article__box"><h2 class="blog-article__title"> <a class="blog-article__link" href="http://starter.lndo.site/2018/11/01/block-category-common/">Block category: Common</a></h2><div class="blog-article__meta entry-meta"><div class="entry-meta"><span class="posted-on">Posted on <a href="http://starter.lndo.site/2018/11/01/block-category-common/" class="post_url"><span>November 1, 2018</span></a> by <a class="author_name" href="http://starter.lndo.site/author/starter/">starter</a></span></div></div></div> </article><article id="post-1781" class="blog-article col-lg-4 post-1781 post type-post status-publish format-standard hentry category-block category-media-2 tag-content tag-embeds-2"> <a class="blog-article__thumb-wrap" href="http://starter.lndo.site/2018/11/01/blocks-embeds/"><div class="blog-article__thumb media-wrap media-wrap--400x280"> <img class="media-item cover-img" src="http://starter.lndo.site/wp-content/themes/starter_s/src/assets/images/post-thumb.jpg" alt=""></div> </a><div class="blog-article__box"><h2 class="blog-article__title"> <a class="blog-article__link" href="http://starter.lndo.site/2018/11/01/blocks-embeds/">Block category: Embeds</a></h2><div class="blog-article__meta entry-meta"><div class="entry-meta"><span class="posted-on">Posted on <a href="http://starter.lndo.site/2018/11/01/blocks-embeds/" class="post_url"><span>November 1, 2018</span></a> by <a class="author_name" href="http://starter.lndo.site/author/starter/">starter</a></span></div></div></div> </article>';

			$('.blog-listing').find('.row').append(response);

		}, 1000);
	}
};

export default LoadMoreBlog;
