=== SEO Auto Links & Related Posts ===

Contributors: poer
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=10748556
Tags: seo, links, post, slide out, related posts, similar posts, internal linking, auto smart links, auto links, amazon
Requires at least: 3.0
Tested up to: 3.5
Stable tag: 1.2.12.12

Automatically create auto link, related posts, and slide out related posts when user reach the end of article.

== Description ==

SEO Auto Links & Related Posts Main Features

* Auto Links

Auto create internal link to posts related to the current post base on Meta Keywords and Post Tags. Authority sites like Wikipedia always have a lot of internal link, and this feature will automatically help you getting the same results.

* Related Posts with 8 layout & style

Displaying related posts based on the Content of articles, Post Tags and Meta Keywords. This feature is useful to improve internal links and to keep visitors stay on your site.

* Slide Out Related Posts with 6 theme color

It is a sliding box, with related/recent posts in it, that will automatically appeared at the lower right corner when user scroll down and reach the end of article. It will grab visitors attention right after they finish reading your article, and giving them more options about what to do next. The impact is a longer visitors time spent on your site.

* Cache All Results and Thumbnail

Visitors and Google likes fast loading websites. That's why we use a persistent cache to store all results from auto links, related posts, slide out and thumbnail to get a better loading time and save server resources. All requests will be served from the cache, except on the first request.

* Custom Auto Link to Affiliate Products

Manually adding a couple of keywords and links for the auto link. You can use this feature to automatically create a link to a trusted site for better SEO results or to affiliate products to earn additional profit.

* Custom Ads in Slide Out Related Posts

The slide out feature would definitely attract the attention of visitors, so it is one of the best places to display your ads. This feature will add your ad on the first row of the slide out related posts. You have the option to show Amazon.com Today's Best Deals with your affiliate ID embedded or your own custom ad.

Hosting requirement: 
- ionCube PHP Loader extension

== Installation ==

1. Download it here, or search from your WordPress Admin > Plugin > Add New page.
1. Install and activate from WordPress Admin > Plugins page.
1. Go to the WordPress Admin page > SEO ALRP, and set the options as you wish.

== Frequently Asked Questions ==

1. I got ionCube PHP Loader error, what should I do? Contact your hosting, and ask them to activate ionCube PHP Loader extension.
1. Slide out related posts is not displayed and can't be found when I view the page source code! Make sure that your theme calling this code &lt;?php wp_footer(); ?&gt; inside the footer.php file, right before &lt;/body> tag.
1. How to check whether ALRP served my page from cache or not? Refresh your page, right click any where, select view page source, and find a text like these: &lt;!-- ALRP: Autolinks served/NOT served from the cache --&gt; &lt;!-- ALRP: Related Posts served/NOT served from the cache --&gt; &lt;!-- ALRP: Slidebox served/NOT served from the cache --&gt;

== Screenshots ==

1. 8 related posts layout and style, with css style for each layout available in CSS folder
1. 6 slide out related posts theme, will appear when visitor reach the end of article
1. Auto Links settings page
1. Related Posts settings page
1. Slide Out Related Posts setting page
1. Performance comparison

== Changelog ==
= 1.2.12.12 =
* minor bug fix

= 1.2.9.23 =
* bug fix: slidebox not working with theme that use loop file
* bug fix: slidebox having transparent background in non webkit browser

= 1.2.9.22 =
* 5 new slide out theme (total 6 theme)
* 3 new related post layout and style (total 8 layout and style)
* 2 tooltip style
* prevent create manual auto link inside pre and code html tag
* accept html header tag inside related post title
* show slide out on posts/pages option
* option to change the numbers of related posts in the slide out
* 3 ads slot in slide out (previously 1)
* set cache expired option

= 1.2.9.6 =
* New: Add slide out related posts feature
* New: All results cached in database
* New: Prevent creating auto link inside pre and code tags
* New: Five different related posts layout, with easy to edit css file
* New: Beautiful tooltip for auto link and related posts
* New: Add support to cache image from 7 Amazon locale sites
* Some minor bug fix and code optimization

= 0.120414 =
* Bug fix

= 0.120217 =
* Auto resize thumbnail image for better page loading time.

= 0.120126 =
* Add rebuild fulltext index menu.
* Fix some bug.

= 0.110528 =
* Prevent auto link to create link to the post it self.
* Prevent auto link to change any keyword inside HTML tag.

= 0.110424 =
* Fix missing number in 404 title

= 0.110415 =
* Auto links post tags.
* Display thumbnail in the related posts.

= 0.110324 =
* Bug fix, can't save some options.

= 0.110323 =
* Auto link meta keyword found inside the blog post.
* Related posts now is based on post title, post content, and meta keyword.
* Related posts for 404 error page.
* Exclude category from related posts.

= 0.110308 =
* Bug fix, admin page now using input validation
* Prevent duplicate content from related posts

= 0.110307 =
* Remove stopwords; 
* Give more weight on post title; 
* Auto link related posts from inside post content; 
* Using built in wp cache;

== Upgrade Notice ==
= 0.120923 =
Plugin make over with a lot of new features like: Slide out related posts to grab visitors attention; All results now cached in database for better page loading time and less burden  of mysql server; Five related posts layout, with easy to edit css file; Beautiful tooltip in auto link and related posts; Prevent creating auto link inside pre and code tags.