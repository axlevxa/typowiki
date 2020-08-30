<?php
/**
 * Typowiki
 *  
 * @author   axlevxa
 * @license  GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

if (!defined('DOKU_INC')) die(); /* must be run from within DokuWiki */
@require_once(dirname(__FILE__).'/tpl_functions.php'); /* include hook for template functions */
header('X-UA-Compatible: IE=edge,chrome=1');

$showTools = !tpl_getConf('hideTools') || ( tpl_getConf('hideTools') && !empty($_SERVER['REMOTE_USER']) );
$showSidebar = page_findnearest($conf['sidebar']) && ($ACT=='show');
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $conf['lang'] ?>"
  lang="<?php echo $conf['lang'] ?>" dir="<?php echo $lang['direction'] ?>" class="no-js">
<head>
    <meta charset="UTF-8" />
    <title><?php tpl_pagetitle() ?> | <?php echo strip_tags($conf['title']) ?></title>
    <script>(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)</script>
    <?php tpl_metaheaders() ?>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <?php echo tpl_favicon(array('favicon', 'mobile')) ?>
    <?php tpl_includeFile('meta.html') ?>
    <link href="<?php echo tpl_basedir(); ?>assets/fonts/microns/microns.css" rel="stylesheet">
</head>

<body>
    <?php /* with these Conditional Comments you can better address IE issues in CSS files,
             precede CSS rules by #IE8 for IE8 (div closes at the bottom) */ ?>
    <!--[if lte IE 8 ]><div id="IE8"><![endif]-->

    <?php /* the "dokuwiki__top" id is needed somewhere at the top, because that's where the "back to top" button/link links to */ ?>
    <?php /* tpl_classes() provides useful CSS classes; if you choose not to use it, the 'dokuwiki' class at least
             should always be in one of the surrounding elements (e.g. plugins and templates depend on it) */ ?>
    <div id="dokuwiki__site"><div id="dokuwiki__top" class="site <?php echo tpl_classes(); ?> <?php
        echo ($showSidebar) ? 'hasSidebar' : ''; ?>">
        <?php tpl_includeFile('header.html') ?>

        <!-- ********** HEADER ********** -->
        <nav>
            <div class="nav-bar dark">
                <div class="container">
                    <div class="row">
                    <div class="col-4 col-md-7 nav-brand">
                        <h2><?php tpl_link(wl(),$conf['title'],'accesskey="h" title="[H]"') ?></h2>
                    </div>
                    <div class="col-8 col-md-5 nav-actions">
                        <?php tpl_button('edit')?>
                        <a onclick="togglediv('search')" aria-label="<?php echo $lang['btn_search'] ?>" tabindex="0" class="no-outline">
                            <div class="nav-item nav-searchicon">
                                <span>
                                    <span class="mu mu-search txt-24"></span>
                                </span>
                            </div>
                        </a>
                        <a onclick="togglediv('user')" aria-label="<?php echo $lang['profile'] ?>" tabindex="0" class="no-outline">
                            <div class="nav-item nav-searchicon">
                                    <span class="mu mu-user txt-24"></span>
                            </div>
                        </a>
                    </div>

                    </div>
                </div>
            </div>
        <ul class="a11y skip">
                    <li><a href="#dokuwiki__content"><?php echo $lang['skip_to_content'] ?></a></li>
        </ul>
        <div class="nav-expanded dark">
            <div id="search" class="nav-expanded-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4 left">
                            <span class="ui-title"><?php echo $lang['btn_search'] ?></span>
                        </div>
                        <div class="col-sm-8 right">
                            <?php tpl_searchform() ?>
                        </div>
                    </div>
                </div>
            </div>
            <div id="user" class="nav-expanded-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4 left">
                            <?php
                                if (!empty($_SERVER['REMOTE_USER'])) {
                                    echo '<span class="ui-title">';
                                    tpl_userinfo(); /* 'Logged in as ...' */
                                    echo '</span>';
                                }
                            ?>
                        </div>
                        <div class="col-sm-8 right">
                            <ul role="navigation">
                                <?php tpl_toolsevent('usertools', array(
                                    'userpage'  => _tpl_action('userpage', 1, 'li', 1),
                                    'profile'   => tpl_action('profile', 1, 'li', 1),
                                    'register'  => tpl_action('register', 1, 'li', 1),
                                    'login'     => tpl_action('login', 1, 'li', 1),
                                    'admin'     => tpl_action('admin', 1, 'li', 1),
                                )); ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

        <div class="wrapper">

            <!-- ********** CONTENT ********** -->
        <div class="usr-content">
            <div id="dokuwiki__content">
                <div class="container usr-container">
                        <?php if($conf['breadcrumbs']){ ?>
                                        <div class="breadcrumbs"><?php tpl_breadcrumbs() ?></div>
                                    <?php } ?>
                                    <?php if($conf['youarehere']){ ?>
                                        <div class="breadcrumbs"><?php tpl_youarehere() ?></div>
                        <?php } ?>
                    <div class="row usr-inner">
                        <div class="col-md-9 col-main">
                        <?php tpl_flush() /* flush the output buffer */ ?>
                        <?php tpl_includeFile('pageheader.html') ?>

                            <div class="page">
                                <?php html_msgarea() /* occasional error and info messages on top of the page */ ?>

                                <!-- BREADCRUMBS -->

                                <!-- wikipage start -->
                                <?php tpl_content(false) /* the main content */ ?>
                                <!-- wikipage stop -->
                                <div class="clearer"></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-side">
                            <?php tpl_flush() ?>
                            <div class="nav-side" role="navigation">
                                <div class="nav-side-inner">
                                    <?php tpl_toc()?>

                                    <!-- ********** ASIDE ********** -->
                                    <?php if ($showSidebar): ?>
                                        <div id="dokuwiki__aside"><div class="pad aside include group ul-raw">
                                            <?php tpl_includeFile('sidebarheader.html') ?>
                                            <?php tpl_include_page($conf['sidebar'], 1, 1) /* includes the nearest sidebar page */ ?>
                                            <?php tpl_includeFile('sidebarfooter.html') ?>
                                            <div class="clearer"></div>
                                        </div></div><!-- /aside -->
                                    <?php endif; ?>

                                    <div class="back-to-top-wrapper">
                                        <a href="#dokuwiki__top" class="back-to-top-link primary"><span class="mu mu-arrow-up"></span> Back to Top</a>
                                    </div>
                                </div>
                            </div>
                            <?php tpl_includeFile('pagefooter.html') ?>
                        </div>
                    </div><!-- /row -->
                </div><!-- /container -->
            </div><!-- /content -->
        </div>

            <div class="clearer"></div>
            <hr class="a11y" />

        </div><!-- /wrapper -->

        <!-- ********** FOOTER ********** -->
        <footer id="dokuwiki__footer">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-md-8">
                        <div class="doc"><?php tpl_pageinfo() /* 'Last modified' etc */ ?></div>
                        <?php tpl_license('button') /* content license, parameters: img=*badge|button|0, imgonly=*0|1, return=*0|1 */ ?>
                    </div>
                    <div class="col-sm-3 col-md-2">
                        <!-- PAGE ACTIONS -->
                        <?php if ($showTools): ?>
                            <div id="dokuwiki__pagetools">
                                <ul class="footer-menu ul-raw">
                                    <?php tpl_toolsevent('pagetools', array(
                                        'edit'      => tpl_action('edit', 1, 'li', 1),
                                        'discussion'=> _tpl_action('discussion', 1, 'li', 1),
                                        'revisions' => tpl_action('revisions', 1, 'li', 1),
                                        'backlink'  => tpl_action('backlink', 1, 'li', 1),
                                        'subscribe' => tpl_action('subscribe', 1, 'li', 1),
                                        'revert'    => tpl_action('revert', 1, 'li', 1),
                                        'top'       => tpl_action('top', 1, 'li', 1),
                                    )); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-3 col-md-2">
                        <!-- SITE TOOLS -->
                        <div id="dokuwiki__sitetools">
                            <ul class="footer-menu ul-raw">
                                <?php tpl_toolsevent('sitetools', array(
                                    'recent'    => tpl_action('recent', 1, 'li', 1),
                                    'media'     => tpl_action('media', 1, 'li', 1),
                                    'index'     => tpl_action('index', 1, 'li', 1),
                                )); ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <div class="no"><?php tpl_indexerWebBug() /* provide DokuWiki housekeeping, required in all templates */ ?></div>
        </footer><!-- /footer -->

        <?php tpl_includeFile('footer.html') ?>
    </div></div><!-- /site -->

    <script>
        function togglediv(id) {
            var div = document.getElementById(id);
            div.style.display = div.style.display == "block" ? "none" : "block";
        }
    </script>
</body>
</html>
