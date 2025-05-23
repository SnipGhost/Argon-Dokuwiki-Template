<?php
	// must be run from within DokuWiki
	if (!defined('DOKU_INC')) die();

	// include hook for template functions
	@require_once dirname(__FILE__) . '/tpl_functions.php';

	$showTools = !tpl_getConf('hideTools') || (tpl_getConf('hideTools') && !empty($_SERVER['REMOTE_USER']));
	$showSiteTools = !tpl_getConf('hideSiteTools');
	$showSidebar = page_findnearest($conf['sidebar']);
	$showIcon = tpl_getConf('showIcon');
?>
<!DOCTYPE html>
<html lang="<?php echo $conf['lang']; ?>" dir="<?php echo $lang['direction']; ?>">
<!--
=========================================================
*  Argon Dokuwiki Template
*  Based on the Argon Design System by Creative Tim
*  Ported to Dokuwiki by Anchit (@IceWreck)
=========================================================
 -->
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>
		<?php tpl_pagetitle(); ?> | <?php echo strip_tags($conf['title']); ?>
	</title>
	<?php tpl_metaheaders(); ?>
	<?php echo tpl_favicon(array('favicon', 'mobile')); ?>

	<?php tpl_includeFile('meta.html'); ?>

	<!-- Fonts and icons -->
	<?php echo tpl_asset_link('fonts.css', 'css'); ?>
	<!-- CSS Files -->
	<?php echo tpl_asset_link('doku.css', 'css', '3h'); ?>
	<!-- JS for scroll to top button -->
	<?php echo tpl_asset_link('floating-top-button.js', 'js', '2h', 'defer'); ?>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

	<?php
		// Init translation plugin
		$translation = plugin_load('helper','translation');
		$mainpage_link = tpl_get_translink($translation, $conf['lang'], $conf['start']);
	?>
</head>

<body class="docs ">
	<div id="dokuwiki__site">


		<header class="navbar navbar-horizontal navbar-expand navbar-dark flex-row align-items-md-center ct-navbar bg-primary py-2">

			<!-- Logo + start page link -->
			<?php if ($showIcon) { ?>
				<div class="header-title">
					<?php
						// get logo either out of the template images folder or data/media folder
						$logoSize = array();
						$logo = tpl_getMediaFile(array(':wiki:logo.png', ':logo.png', 'images/logo.png', 'images/logo.svg', ':wiki:dokuwiki-128.png'), false, $logoSize);
						// display logo and wiki title in a link to the home page
						tpl_link(
							wl($mainpage_link),
							'<img class="logo" src="'.$logo.'" alt="" /><span>'.$conf['title'].'</span>',
							'accesskey="h" title="[H]"'
						);
					?>
				</div>
			<?php } else { ?>
				<div class="btn btn-neutral btn-icon">
					<span class="btn-inner--icon">
						<!-- <i class=""></i> -->
					</span>
					<span id="nav-link-main" class="nav-link-inner--text">
						<?php tpl_link(wl($mainpage_link), $conf['title'], 'accesskey="h" title="[H]"'); ?>
					</span>
				</div>
			<?php } ?>

			<!-- Navbar user-menu + search -->
			<div class="d-none d-sm-none d-md-block ml-auto">
				<ul class="navbar-nav ct-navbar-nav flex-row align-items-center">
					<?php
						$menu_items = (new \dokuwiki\Menu\UserMenu())->getItems();
						foreach($menu_items as $item) {
							if ($item->getType() != 'register') {
								echo '<li class="'.$item->getType().'">'
									.'<a class="nav-link" href="'.$item->getLink().'" title="'.$item->getTitle().'">'
									.'<i class="argon-doku-navbar-icon" aria-hidden="true">'.inlineSVG($item->getSvg()).'</i>'
									. '<span class="a11y">'.$item->getLabel().'</span>'
									. '</a></li>';
							}
						}
					?>
					<li class="nav-item">
						<div class="search-form">
							<?php tpl_searchform(); ?>
						</div>
					</li>
				</ul>
			</div>
			<button class="navbar-toggler ct-search-docs-toggle d-block d-md-none ml-auto ml-sm-0" type="button"
				data-toggle="collapse" data-target="#ct-docs-nav" aria-controls="ct-docs-nav" aria-expanded="false"
				aria-label="Toggle docs navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

		</header>


		<div class="container-fluid">
			<div class="row flex-xl-nowrap">

				<?php
					// Render the content initially
					ob_start();
					tpl_content(false);
					$buffer = ob_get_clean();
				?>

				<!-- left sidebar -->
				<div class="col-12 col-md-3 col-xl-2 ct-sidebar">
					<nav class="collapse ct-links small-top-padding" id="ct-docs-nav">
						
						<div class="mx-auto" style="max-width: fit-content;">
						<?php
							if (!empty($_SERVER['REMOTE_USER'])) {
								echo '<a href="'.wl($mainpage_link, 'do=profile').'" id="user-info" class="nav-item nav-link"> ';
								tpl_userinfo();
								echo '</a>';
							}
						?>
						
						<!-- mobile-navbar user-menu -->
						<div class="d-sm-block d-md-none">
							<ul class="navbar-nav ct-navbar-nav flex-row align-items-center mobile-navbar mx-auto">
								<?php
									$menu_items = (new \dokuwiki\Menu\UserMenu())->getItems();
									foreach($menu_items as $item) {
										if ($item->getType() != 'register') {
											echo '<li class="'.$item->getType().'">'
												.'<a class="nav-link" href="'.$item->getLink().'" title="'.$item->getTitle().'">'
												.'<i class="" aria-hidden="true">'.inlineSVG($item->getSvg()).'</i>'
												. '<span class="a11y">'.$item->getLabel().'</span>'
												. '</a></li>';
										}
									}
								?>
							</ul>
						</div>
						<!-- mobile-navbar search -->
						<div class="d-sm-block d-md-none mt-3 mb-3 mobile-navbar">
							<div class="navbar-nav ct-navbar-nav flex-row align-items-center">
								<div class="search-form">
									<?php tpl_searchform()?>
								</div>
							</div>
						</div>
						</div>

						<?php if ($showSidebar): ?>
						<div id="dokuwiki__aside" class="ct-toc-item active">
							<div class="leftsidebar">
								<div class="ct-toc-link">
									<?php
										$sidebar_link = tpl_get_translink($translation, $conf['lang'], $conf['sidebar']);
										tpl_link(wl($sidebar_link, '', true), $lang['toc'], 'class="wikilink1"');
									?>
								</div>
								<?php tpl_includeFile('sidebarheader.html')?>
								<?php tpl_include_page($conf['sidebar'], 1, 1)?>
								<?php tpl_includeFile('sidebarfooter.html')?>
							</div>
						</div>
						<?php endif; ?>

						<?php if ($showTools && !tpl_getConf('movePageTools')): ?>
						<div id="dokuwiki__pagetools" class="ct-toc-item active">
							<a class="ct-toc-link">
								<?php echo $lang['page_tools'] ?>
							</a>
							<ul class="nav ct-sidenav">
								<?php
									$menu_items = (new \dokuwiki\Menu\PageMenu())->getItems();
									foreach($menu_items as $item) {
									echo '<li class="'.$item->getType().'">'
										.'<a class="'.$item->getLinkAttributes('')['class'].'" href="'.$item->getLink().'" title="'.$item->getTitle().'">'
										. $item->getLabel()
										. '</a></li>';
									}
								?>
							</ul>
						</div>
						<?php endif; ?>

						<?php if ($showSiteTools): ?>
						<div class="ct-toc-item active">
							<a class="ct-toc-link">
								<?php echo $lang['site_tools'] ?>
							</a>
							<ul class="nav ct-sidenav">
								<?php
									$menu_items = (new \dokuwiki\Menu\SiteMenu())->getItems();
									foreach($menu_items as $item) {
									echo '<li class="'.$item->getType().'">'
										.'<a class="" href="'.$item->getLink().'" title="'.$item->getTitle().'">'
										. $item->getLabel()
										. '</a></li>';
									}
								?>
							</ul>
						</div>
						<?php endif; ?>

						<?php if ($translation) echo $translation->showTranslations(); ?>

					</nav>
				</div>


				<!-- center content -->
				<main class="col-12 col-md-9 col-xl-8 py-md-3 pl-md-5 ct-content dokuwiki" role="main">

					<div id="dokuwiki__top" class="site
						<?php echo tpl_classes(); ?>
						<?php echo ($showSidebar) ? 'hasSidebar' : ''; ?>">
					</div>

					<?php html_msgarea()?>
					<?php tpl_includeFile('header.html')?>

					<!-- Trace/Navigation -->
					<nav aria-label="breadcrumb" role="navigation">
						<ol class="breadcrumb">
							<?php if ($conf['breadcrumbs']) { ?>
							<div class="breadcrumbs"><?php tpl_breadcrumbs(); ?></div>
							<?php } ?>
							<?php if ($conf['youarehere']) { ?>
							<div class="breadcrumbs"><?php tpl_youarehere(); ?></div>
							<?php } ?>
						</ol>
					</nav>

					<!-- Page Menu -->
					<?php if ($showTools && tpl_getConf('movePageTools')): ?>
					<div class="argon-doku-page-menu">
						<?php
							$menu_items = (new \dokuwiki\Menu\PageMenu())->getItems();
							foreach($menu_items as $item) {
								if ($item->getType() == "top") {
									$add_wrapper_top = '<div id="floating-top-button" class="floating-top-button">';
									$add_wrapper_cls = 'btn ';
									$add_wrapper_end = '</div>';
								} else {
									$add_wrapper_top = '';
									$add_wrapper_cls = '';
									$add_wrapper_end = '';
								}
								echo $add_wrapper_top.'<li class="'.$item->getType().'">'
									.'<a class="page-menu__link '.$add_wrapper_cls.$item->getLinkAttributes('')['class'].'" href="'.$item->getLink().'" title="'.$item->getTitle().'">'
									.'<i class="">'.inlineSVG($item->getSvg()).'</i>'
									. '<span class="a11y">'.$item->getLabel().'</span>'
									. '</a></li>'.$add_wrapper_end;
							}
						?>
					</div>
					<?php endif;?>
				
					<!-- Wiki Contents -->
					<div id="dokuwiki__content">
						<div class="pad">
							<div class="page">
								<?php echo $buffer; ?>
							</div>
						</div>							
					</div>

					<hr/>

					<!-- Footer -->
					<div class="card footer-card">
						<div class="card-body">
							<div class="container">

								<div class="row">
									<div class="col">
										<div id="dokuwiki__footer">
											<div class="pad">
												<!-- 'Last modified' etc -->
												<div class="doc">
													<?php tpl_pageinfo(); ?>
												</div>
												<?php tpl_license('0'); /* content license, parameters: img=*badge|button|0, imgonly=*0|1, return=*0|1 */ ?>
											</div>
										</div>
									</div>
								</div>

								<br/>
								<div class="row">
									<div class="argon-doku-footer-fullmenu">
										<?php
											$menu_items = (new \dokuwiki\Menu\MobileMenu())->getItems();
											foreach($menu_items as $item) {
												if ($item->getType() != 'media' && $item->getType() != 'index' && $item->getType() != 'register') {
													echo '<li class="'.$item->getType().'">'
														.'<a class="" href="'.$item->getLink().'" title="'.$item->getTitle().'">'
														.'<i class="" aria-hidden="true">'.inlineSVG($item->getSvg()).'</i>'
														. '<span class="a11y">'.$item->getLabel().'</span>'
														. '</a></li>';
												}
											}
										?>
									</div>
									<?php tpl_includeFile('footer.html'); ?>
								</div>
								
								<br/>
								<div class="row">
									<div class="footer-search">
										<?php tpl_searchform(); ?>
									</div>
								</div>

							</div>
						</div>
					</div>
					<?php tpl_indexerWebBug(); ?>
				</main>


				<!-- Right Sidebar -->
				<div class="d-none d-xl-block col-xl-2 ct-toc">
					<div>
						<?php tpl_toc(); ?>
					</div>
				</div>


			</div>
		</div>
	</div>
</body>

</html>
