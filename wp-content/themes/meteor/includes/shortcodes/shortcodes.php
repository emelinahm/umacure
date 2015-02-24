<?php
  add_shortcode('title_heading', 'meteor_title_heading');
  add_shortcode('skill', 'meteor_skillset');
  add_shortcode('accordion', 'meteor_accordion');
  add_shortcode('tab', 'meteor_tabs');
  add_shortcode('quote', 'meteor_quote');
  add_shortcode('toggle', 'meteor_toggle');
  add_shortcode('clear', 'meteor_styling_shortcodes');
  add_shortcode('push', 'meteor_styling_shortcodes');
  add_shortcode('hidden', 'meteor_visibility_shortcode');
  add_shortcode('visible', 'meteor_visibility_shortcode');
  add_shortcode('container', 'meteor_container_shortcode');
  /****** TOPの新着記事 ******/
  add_shortcode('newentry','newentry');
  add_shortcode('newentry_push','newentry_push');
  add_shortcode('newentry_news','newentry_news');
  add_shortcode('newentry_top','newentry_top');

  /**********サイドバーカテゴリ一覧****************/
  add_shortcode('catlist','catlist');

  /****メイン領域のカテゴリ一覧****/
    add_shortcode('catlist_main','catlist_main');

  /******single パン屑とSNS********/
  add_shortcode('posthead','posthead');
  add_shortcode('postbottom','postbottom');

  /*******single 投稿タイトル*******/
  add_shortcode('posttitle','posttitle');
  add_shortcode('singletags','singletags');
  /*** header出力 ***/
  add_shortcode('uma_header','uma_header');
  add_shortcode('uma_header_page','uma_header_page');
  /*** footer出力 ***/
  add_shortcode('uma_footer','uma_footer');
  /*** split　草画像　出力 ***/
  add_shortcode('uma_split','uma_split');
  /*** サブメニュー 出力 ***/
  add_shortcode('uma_sub_menu','uma_sub_menu');

  /****** サイト情報 *********/
  add_shortcode('site_info','site_info');


  /*** single　制御リンク　出力 ***/
  add_shortcode('uma_single_controll','uma_single_controll');
  /*******外部リンクを出力********/
  add_shortcode('gaibu_link','gaibu_link');
  add_shortcode('youtube_link','youtube_link');
  /*******引用出力********/
  add_shortcode('inyo','inyo');

  /*******画像出力********/
  add_shortcode('img_large','img_large');
  add_shortcode('img_middle','img_middle');
  add_shortcode('img_small','img_small');

  /********query内記事出力*********/
  add_shortcode('show_query_posts','show_query_posts');

  add_shortcode('manage_list','manage_list');
  add_shortcode('manage_list2','manage_list2');


  function manage_list2(){
    global $post;
    $the_query = new WP_query(array('post_type' => 'post','post_status' => 'any','nopaging'=>'true'));
    if($the_query->have_posts()){
      $export = "<button id='csv' type='button'>CSV</button>";
      $str = $export."<table id='manage-table'><tr><th>管理ID</th><th>タイトル</th><th>URL</th><th>ステータス</th><th>下書き保存日時</th><th>公開日</th><th>作成者</th></tr>";
      while($the_query->have_posts()){
        $the_query->the_post();
        $status = array('future' => '予約済み' , 'publish' => '公開済み', 'draft' => '下書き');
        $str .= "<tr>";
        $str .= "<td>".get_post_meta($post->ID,"manageid_post", $single = true)."</td>";
        $str .= "<td>".get_the_title()."</td>";
        $str .= "<td><a href='".get_permalink()."''>".get_permalink()."</a></td>";
        $str .= "<td>".$status[get_post_status()]."</td>";
        $str .= "<td>".get_the_modified_time('Y/m/d H:i:s')."</td>";
        $str .= ( strcmp($status[get_post_status()],'下書き') ) ? "<td>".get_the_date('Y/m/d H:i:s')."</td>" : "<td></td>";
        $str .= "<td>".get_the_author()."</td>";
        $str .= "</tr>";
      }
      $str .= "</table>";
    }
    return $str;
  }

  function manage_list(){
    global $paged;
    global $post;
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $the_query = new WP_query(array('post_type' => 'post','posts_per_page'=>'300','post_status' => 'any', 'paged'=>$paged));
    if($the_query->have_posts()){
      $export = "<button id='csv' type='button'>CSV</button>";
      $maxpage = $the_query->max_num_pages;
      if($maxpage>1){
        $pagination = '<div class="cat-pagenation">';
        $maxpnum = floor(($maxpage-1)/10);
        $pnum = floor(($paged-1)/10);
        if($pnum>0) $pagination .= '<a href="'.get_pagenum_link(($pnum-1)*10 + 1).'">...</a>';
        for ($i=$pnum*10 + 1; $i <= $pnum*10 + 10 ; $i++) {
          if($i > $maxpage)break;
          $pagination .= ($paged==$i) ? '<span class="now-page">'.$i.'</span>' : '<span class="other-page"><a href="'.get_pagenum_link($i).'">'.$i.'</a></span>';
        }
        if($pnum<$maxpnum) $pagination .= '<a href="'.get_pagenum_link(($pnum+1)*10 + 1).'">...</a>';
        $pagination .= '</div><div class="clear"></div>';
      }
      $str = $export.$pagination."<table id='manage-table'><tr><th>管理ID</th><th>タイトル</th><th>URL</th><th>ステータス</th><th>公開日</th><th>作成者</th></tr>";
      while($the_query->have_posts()){
        $the_query->the_post();
        $status = array('future' => '予約済み' , 'publish' => '公開済み', 'draft' => '下書き');
        $str .= "<tr>";
        $str .= "<td>".get_post_meta($post->ID,"manageid_post", $single = true)."</td>";
        $str .= "<td>".get_the_title()."</td>";
        $str .= "<td><a href='".get_permalink()."''>".get_permalink()."</a></td>";
        $str .= "<td>".$status[get_post_status()]."</td>";
        $str .= "<td>".get_the_date()."</td>";
        $str .= "<td>".get_the_author()."</td>";
        $str .= "</tr>";
      }
      $str .= "</table>";
    }
    return $str;
  }

  function show_query_posts(){
    global $wp_query;
    global $paged;

    if($_GET['s'])
      $keyword=$_GET['s'];
    else
      $keyword=urldecode($wp_query->query_vars['tag']);
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $title ='<div class="newentry-title">
               <div class="newentry-title-left">"'.$keyword.'"での検索結果</div>
               <div class="clear"></div>
             </div>';
    $maxpage = $wp_query->max_num_pages;
    if($maxpage>1){
      $pagination = '<div class="cat-pagenation">';
      $maxpnum = floor(($maxpage-1)/5);
      $pnum = floor(($paged-1)/5);
      if($pnum>0) $pagination .= '<a href="'.get_pagenum_link(($pnum-1)*5 + 1).'">...</a>';
      for ($i=$pnum*5 + 1; $i <= $pnum*5 + 5 ; $i++) {
        if($i > $maxpage)break;
        $pagination .= ($paged==$i) ? '<span class="now-page">'.$i.'</span>' : '<span class="other-page"><a href="'.get_pagenum_link($i).'">'.$i.'</a></span>';
      }
      if($pnum<$maxpnum) $pagination .= '<a href="'.get_pagenum_link(($pnum+1)*5 + 1).'">...</a>';
      $pagination .= '</div><div class="clear"></div>';
    }
    $title .= $pagination;
    if(have_posts()){
      $str = '<div class="newentry">'.$title.'<div class="newentry-content">';
      while(have_posts()){
        the_post();
        if(!$cat || $cat==$exclude){
          $cats = get_the_category();
          foreach ($cats as $c) {
            if($c->cat_ID != $exclude && $c->category_parent == 0){
              $category = $c;
              $catslug = $category->slug;
              $catname = $category->name;
            }
          }
        }
        $str .= '<a href="'.get_permalink().'">
                  <div class="newentry-cell">

                      <div class="newentry-cell-thum">'.get_the_post_thumbnail().'</div>
                      <div class="newentry-cell-info">
                        <div class="newentry-cell-subinfo"><span class="cell-date">'.get_the_date('Y.m.d').'<br class="sp-br" /></span><span class="cell-cat">　'.$catname.'</span></div>
                        <div class="newentry-cell-posttitle"><span class="cell-title">'.get_the_title().'</span></div>
                      </div>
                  </div>
                </a>';
      }
      $str .= '<div class="clear"></div></div>'.$pagination.'</div>';
    }
    else{
      $str = '<div class="newentry-title">
               該当するページがありませんでした。別のキーワードでの検索をお試しください。
             </div>';
    }
    return $str;
  }

  function img_middle($atts){
    extract(shortcode_atts(array(
    'path' => 'path',           //画像のパス
    ), $atts));
    $str = '
    <div class="single-img-box">
      <img src="'.$path.'" alt="馬キュレ"/>
    </div>
            ';
    return $str;
  }


  function youtube_link($atts){
    extract(shortcode_atts(array(
    'title' => 'title',           //リンクタイトル
    'address' => 'address',           //アドレス
    ), $atts));
    $str = '

    <div class="single-img-box-small" style="margin-top:15px;">
      <a href="'.$address.'" target="__blank"><img src="http://umacure.net/wp-content/uploads/2014/04/movie-300x185.jpg" alt="動画を見る"/></a>
    </div>

    <div class="gaibu-link">
      <a href="'.$address.'" target="__blank">
        '.$title.'
      </a>
    </div>
            ';
    return $str;
  }

  function gaibu_link($atts){
    extract(shortcode_atts(array(
    'title' => 'title',           //リンクタイトル
    'address' => 'address',           //アドレス
    ), $atts));
    $str = '
    <div class="gaibu-link">
      <a href="'.$address.'" target="__blank">
        '.$title.'
      </a>
    </div>
            ';
    return $str;
  }


  function inyo($atts){
    extract(shortcode_atts(array(
    'naiyo' => 'naiyo',           //引用内容
    ), $atts));
    $str = '
    <div class="uma-inyo-wrap">
      <table class="inyo-table">
       <tr>
         <td class="inyo-left">
           <img src="'.get_bloginfo('url').'/wp-content/uploads/2014/03/uma_inyo.png" alt="inyofu" />
         </td>
         <td class="inyo-right">
          '.$naiyo.'
         </td>
        </tr>
      </table>
    </div>
            ';
    return $str;

  }

  function site_info(){
    global $post;
    $str = '
            <div class="info-block">
              <div class="info-midashi"><span>●</span>このサイトについて</div>
              <div class="info-naiyo">
              　「うまキュレ」は競馬情報に特化したキュレーションサイト（まとめサイト）です。<br />
              予想に役立つ情報や業界のコアな情報など競馬ファンの方はもちろん、エンタメ・雑学など競馬初心者にもお楽しみいただける情報まで、競馬に関するあらゆる情報をお届けします。
              </div>

              <div class="info-midashi"><span>●</span>ご利用上の注意および免責事項</div>
              <div class="info-naiyo">
              　「うまキュレ」（以下、「当サイト」といいます）は、インターネット上のblog、SNSなどの情報を取集し提供することを目的に運営するメディアです。<br />
              　情報の提供については、最大限の注意を払っていますが、提供する情報に誤りを生じる可能性もありますので、あらかじめご了承ください。<br />
              　当サイトは、競馬全般に関する情報提供を目的として運営し、その他の目的を意図するものではありません。<br />
              　当サイトに掲載される情報については、正確性を保証するものではなく、当サイトのご利用については、利用者の責任において行われるものとし、当サイトは利用者による当サイト利用結果についていかなる保証もせず、それについて何らの責任も負いません。<br />
              　当サイト上で提供する情報について、当サイトは一部または全部を利用者への告知なしに変更または削除する場合があります。<br />
              　当サイトは、当サイトの情報にリンクが設定されている他サイトから取得した情報およびリンク先の他サイト上の各種情報の利用によって生じたあらゆる利用者の損害について、一切の責任を負いません。
              </div>

              <div class="info-midashi"><span>●</span>推奨ブラウザについて</div>
              <div class="info-naiyo">
              　「うまキュレ」を快適にご利用いただくためには、下記のOSとバージョンのブラウザのご利用をおすすめします。<br />
                <div class="suisyo-spec">
                  <span>▽ windows</span><br />
                  　推奨OS：windows7以上<br />
                  　Microsoft Internet Explorer 10.0以上<br />
                  　Mozilla Firefox 最新版<br />
                  　Google Chrome 最新版<br />
                </div>
                <div class="suisyo-spec">
                  <span>▽ Macintosh</span><br />
                  　推奨OS：Mac OS X　10.6以上<br />
                  　Safari 最新版<br />
                  　Google Chrome 最新版<br />
                  　Mozilla Firefox 最新版<br />
                </div>
              </div>


              <div class="info-midashi2"><span>●</span>運営会社</div>
              <table class="unei-table">
                <tr>
                  <td class="unei-left">
                  会社名
                  </td>
                  <td class="unei-right">
                  株式会社ON THE BIT
                  </td>
                </tr>
                <tr>
                  <td class="unei-left">
                  所在地
                  </td>
                  <td class="unei-right">
                  東京都新宿区市谷台町6-1パープルハイム301<br />
                  03-5312-9933
                  </td>
                </tr>
                <tr>
                  <td class="unei-left">
                  代表者
                  </td>
                  <td class="unei-right">
                  代表取締役社長　松本哲
                  </td>
                </tr>
              </table>

              <div class="info-midashi2"><span>●</span>本サイト/掲載記事に関するお問合せ</div>
              <table class="unei-table">
                <tr>
                  <td class="unei-left">
                  会社名
                  </td>
                  <td class="unei-right">
                  株式会社ON THE BIT
                  </td>
                </tr>
                <tr>
                  <td class="unei-left">
                  TEL
                  </td>
                  <td class="unei-right">
                  03-5312-9933
                  </td>
                </tr>
              </table>

            </div>

            ';
    return $str;
  }


  function uma_header(){
    global $post;
    $str = '
            <div id="head-top" class="head-block">

              <div class="head-top pc-tablet">
                <div class="site-logo">
                  <a href="/"><img src="'.get_bloginfo('url').'/wp-content/uploads/2014/03/uma_logo.png" alt="logo" /></a>
                </div>
                <div class="site-discription">
                  競馬情報をまとめたキュレーションサイト「うまキュレ」
                </div>
                <div class="clear"></div>
              </div><!-- head-top-pc end -->

              <div class="head-top mobile-only">
                <div class="mobile-header"  style="padding-top:15px;">
                  <a href="/"><img src="'.get_bloginfo('url').'/wp-content/uploads/2014/03/uma_logo.png" alt="logo" /></a>
                </div>
                <div class="mobile-discription">競馬情報をまとめた<br />キュレーションサイト</div>
                <div class="clear"></div>
              </div><!-- head-top-mobile end -->


              <div class="head-middle">
                <img src="'.get_bloginfo('url').'/wp-content/uploads/2014/03/uma_kusa_line.png" alt="kusa_line" style="width:100%"/>
              </div>

              <div class="head-bottom" style="">
                <div class="main-menu-block">
                  <div class="main-menu-home pc-tablet">
                    <a href="/"><img src="'.get_bloginfo('url').'/wp-content/uploads/2014/03/uma_home_menu_button.png" alt="kusa_line" style="width:18px"/></a>
                  </div>
                  <div class="main-menu-ji">
                    <a href="/new">新着記事</a>
                  </div>
                  <div class="main-menu-ji">
                    <a href="/push">オススメ</a>
                  </div>
                  <div class="main-menu-ji pc-tablet">
                    <a href="/ranking">週間PVランキング</a>
                  </div>
                  <div class="main-menu-ji-last pc-tablet">
                    <a href="/taglist">タグ一覧</a>
                  </div>
                  <div class="main-menu-ji-last mobile-only">
                    <a href="/catlist">カテゴリ一覧</a>
                  </div>
                  <div id="search-3" class="pc-tablet search-box">
                   <form action="http://umacure.net" method="get" class="last-child" style="margin-bottom: 0px;">
                     <input type="text" name="s" value="" placeholder="サイト内検索" autocomplete="off"><span class="search-right"><img src="'.get_bloginfo('url').'/wp-content/uploads/2014/04/search.png" alt="search" /></span>
                   </form>
                  </div>


                  <div class="clear"></div>
                </div>
              </div>

            </div>

            ';
    return $str;
  }


  function uma_header_page(){
    global $post;

    $options = wp_social_bookmarking_light_options();
    $services = $options['services'];


    $str = '
            <div id="head-top" class="head-block">

              <div class="head-top pc-tablet">
                <div class="site-logo">
                  <a href="/"><img src="'.get_bloginfo('url').'/wp-content/uploads/2014/03/uma_logo.png" alt="logo" /></a>
                </div>

                <div class="site-discription">
                  競馬情報をまとめたキュレーションサイト「うまキュレ」
                </div>

                <div class="site-discription2">
                                  '.wp_social_bookmarking_light_output($services, get_permalink(), get_the_title()).'
                </div>


                <div class="clear"></div>
              </div><!-- head-top-pc end -->

              <div class="head-top mobile-only">
                <div class="mobile-header"  style="padding-top:15px;">
                  <a href="/"><img src="'.get_bloginfo('url').'/wp-content/uploads/2014/03/uma_logo.png" alt="logo" /></a>
                </div>
                <div class="mobile-discription">競馬情報をまとめた<br />キュレーションサイト</div>

                <div class="clear"></div>
              </div><!-- head-top-mobile end -->


              <div class="head-middle">
                <img src="'.get_bloginfo('url').'/wp-content/uploads/2014/03/uma_kusa_line.png" alt="kusa_line" style="width:100%"/>
              </div>

              <div class="head-bottom" style="">
                <div class="main-menu-block">
                  <div class="main-menu-home pc-tablet">
                    <a href="/"><img src="'.get_bloginfo('url').'/wp-content/uploads/2014/03/uma_home_menu_button.png" alt="kusa_line" style="width:18px"/></a>
                  </div>
                  <div class="main-menu-ji">
                    <a href="/new">新着記事</a>
                  </div>
                  <div class="main-menu-ji">
                    <a href="/push">オススメ</a>
                  </div>
                  <div class="main-menu-ji pc-tablet">
                    <a href="/ranking">週間PVランキング</a>
                  </div>
                  <div class="main-menu-ji-last pc-tablet">
                    <a href="/taglist">タグ一覧</a>
                  </div>
                  <div class="main-menu-ji-last mobile-only">
                    <a href="/catlist">カテゴリ一覧</a>
                  </div>
                  <div id="search-3" class="pc-tablet search-box">
                   <form action="http://umacure.net" method="get" class="last-child" style="margin-bottom: 0px;">
                     <input type="text" name="s" value="" placeholder="サイト内検索" autocomplete="off"><span class="search-right"><img src="'.get_bloginfo('url').'/wp-content/uploads/2014/04/search.png" alt="search" /></span>
                   </form>
                  </div>


                  <div class="clear"></div>
                </div>
              </div>

            </div>

            ';
    return $str;
  }


  function uma_footer(){
    global $post;
    $str = '

            <div class="foot-block">

              <div class="footer-top pc-tablet" style="padding-bottom:15px;">
                <div class="site-logo">
                  <a href="/"><img src="'.get_bloginfo('url').'/wp-content/uploads/2014/03/uma_logo_shiro.png" alt="logo" /></a>
                </div>
                <div class="foot-site-discription pc-tablet">
                  競馬情報をまとめたキュレーションサイト「うまキュレ」
                </div>
                <div class="clear"></div>
              </div><!-- head-top-pc end -->

              <div class="footer-top mobile-only" style="padding-bottom:15px;">
                <div class="" align="center" style="padding-top:15px;">
                  <a href="/"><img src="'.get_bloginfo('url').'/wp-content/uploads/2014/03/uma_logo_shiro.png" alt="logo" /></a>
                </div>
              </div><!-- head-top-mobile end -->


              <div class="footer-bottom pc-tablet" style="">
                <div class="footer-menu-block">
<div class="bottom-left"><a href="/site-info">このサイトについて</a></div>
<div class="bottom-right"><span>copyright©</span>on the bit Co.,Ltd.All Rights Reserved.</div>
                  <div class="clear"></div>
                </div>
              </div>

              <div class="footer-bottom mobile-only" style="">
                <div class="footer-menu-block">
<div class="" style="text-align:center;"><a href="/site-info" style="color:#5C4830;">このサイトについて</a></div>
<div class="" style="text-align:center; color:#ffffff;">copyright©<br />on the bit Co.,Ltd.All Rights Reserved.</div>
                  <div class="clear"></div>
                </div>
              </div>

            </div>

            ';
    return $str;
  }

  function uma_split(){
    global $post;
    $str = '
              <div class="uma-split" >
                <img src="'.get_bloginfo('url').'/wp-content/uploads/2014/04/kusa.png" alt="kusa_line" style="width:100%"/>
              </div>
            ';
    return $str;
  }
  function uma_sub_menu(){
    global $post;
    $str = '
              <div class="sub-menu pc-tablet" >
                  <div class="sub-menu-ji sub-4sec">
                    <a href="/race">レース</a>
                  </div>

                  <div class="sub-menu-ji  sub-4sec">
                    <a href="/betting">予想/馬券</a>
                  </div>

                  <div class="sub-menu-ji  sub-4sec">
                    <a href="/horce">競走馬</a>
                  </div>

                  <div class="sub-menu-ji  sub-4sec">
                    <a href="/jockey">騎手</a>
                  </div>

                  <div class="sub-menu-ji pc-tablet">
                    <a href="/jra-wins">JRA競馬/WINS</a>
                  </div>

                  <div class="sub-menu-ji  sub-4sec">
                    <a href="/local-races">地方競馬</a>
                  </div>

                  <div class="sub-menu-ji  sub-4sec">
                    <a href="/trainer">調教師</a>
                  </div>

                  <div class="sub-menu-ji  sub-4sec">
                    <a href="/horce-owner">馬主</a>
                  </div>

                  <div class="sub-menu-ji   sub-4sec">
                    <a href="/auction">競り</a>
                  </div>

                  <div class="sub-menu-ji mobile-only  sub-3sec">
                    <a href="/jra-wins">JRA競馬/WINS</a>
                  </div>

                  <div class="sub-menu-ji  sub-3sec">
                    <a href="/races-invite">はじめての競馬</a>
                  </div>

                  <div class="sub-menu-ji  sub-3sec">
                    <a href="/entame">エンタメ・雑学</a>
                  </div>

                  <div class="sub-menu-ji  sub-3sec">
                    <a href="/column">コラム</a>
                  </div>

                  <div class="clear"></div>
              </div>
            ';
    return $str;
  }

  function uma_single_controll(){
    global $post;
    $prev = get_adjacent_post();
    $next = get_adjacent_post(false,'',false);
    if($prev)
      $hidden = '';
    else
      $hidden = 'style="visibility:hidden"';
    $str = '
              <div class="uma-single-controll" >

                  <a href="'.get_permalink($prev->ID).'" '.$hidden.'><img src="'.get_bloginfo('url').'/wp-content/uploads/2014/03/controll-left.png" alt="prev" style="" class="controll-prev"/></a>';
    if($next)
      $hidden = '';
    else
      $hidden = 'style="visibility:hidden"';
    $str .= '
                  <a href="/"><img src="'.get_bloginfo('url').'/wp-content/uploads/2014/03/cotrol-top.png" alt="top" style=""/ class="controll-top"></a>


                  <a href="'.get_permalink($next->ID).'" '.$hidden.'><img src="'.get_bloginfo('url').'/wp-content/uploads/2014/03/controll-right.png" alt="next" style="" class="controll-next" /></a>

              </div>
            ';
    return $str;
  }



  function singletags(){
    $tags = get_the_tags();
    if($tags){
      $str = "<div class='single-tags-title'>関連タグ</div><div class='single-tags-area'>";
      foreach ($tags as $tag) {
        $str .= '<a href="/?tag='.$tag->name.'">'.$tag->name.'</a>';
      }
      $str .="</div><div class='clear' style='padding-bottom:30px;'></div>";
    }
    return $str;
  }

  function posttitle($atts){
    extract(shortcode_atts(array(
      'exclude' => '9'
    ), $atts));
    $cats = get_the_category();
    foreach ($cats as $c) {
      if($c->cat_ID != $exclude && $c->category_parent == 0){
        $category = $c;
        $catslug = $category->slug;
        $catname = $category->name;
      }
    }
    $str = '
    <div class="uma-post-head"><a class="uma-post-cat" href="/'.$catslug.'">'.$catname.'</a><span class="uma-post-date">'.get_the_date('Y.m.d').'</span>
    <h1 class="uma-post-title">'.get_the_title().'</h1></div>';
    return $str;
  }

  function posthead($atts){
    extract(shortcode_atts(array(
      'exclude' => '9'
    ), $atts));
    $cats = get_the_category();
    foreach ($cats as $c) {
      if($c->cat_ID != $exclude && $c->category_parent == 0){
        $category = $c;
        $catslug = $category->slug;
        $catname = $category->name;
      }
    }
    $str = '<div class="post_sub_info pc-tablet">
       <div class="post_pankuzu ">
         <a href="/">TOP</a> > <a href="/'.$catslug.'">'.$catname.'</a> > '.get_the_title().
      '</div>';
    $options = wp_social_bookmarking_light_options();
    $services = $options['services'];
    $str .= '<div style="float:right">'.wp_social_bookmarking_light_output($services, get_permalink(), get_the_title()).'</div><div class="clear"></div></div>';
    return $str;
  }

  function postbottom(){
    $options = wp_social_bookmarking_light_options();
    $services = $options['services'];
    $str .= '<div class="post-bottom-wrap">
    <div class="post-bottom-left">'.wp_social_bookmarking_light_output($services, get_permalink(), get_the_title()).'</div>
    <div class="post-bottom-right">
      <a href="#head-top">
<img src="http://umacure.net/wp-content/uploads/2014/04/midashi-ciecle.png" alt="side-mark" style="width:20px"> ページ上部へ
</a>
    </div>

    <div class="clear"></div></div>';
    return $str;
  }


  function catlist($atts){
    extract(shortcode_atts(array(
    'exclude' => '9,10',
    'n' => NULL
  ), $atts));
  $exclude = explode(',', $exclude);
  if($n)
  {
    $cats = get_terms("category",array('number'=>$n,'exclude'=>$exclude,'hide_empty'=>1));
  }
  else {
    $cats = get_terms("category",array('exclude'=>$exclude,'hide_empty'=>1));
  }
  if($cats)
  {
    $str = "<div class='side-title'>カテゴリから選ぶ</div>
           <div class='side-cat-list'>";
    foreach ($cats as $cat) {
      if($cat->category_parent == $exclude || $cat->cat_ID == $exclude)continue;
      $str = $str."<a href='/".$cat->slug."'  class='side-cat-link' >".$cat->name." <span>(".$cat->count.")</span>"."</a><br />";
    }
    $str .= '
<div class="side-cat" style="margin-top:6px;">
<a href="/sitemap" style="color:#EDE6DD;">
<img src="http://umacure.net/wp-content/uploads/2014/04/midashi-ciecle.png" alt="side-mark" style="width:20px"> カテゴリ一覧
</a>
</div>


    </div>';
  }
  return $str;
  }

  function catlist_main($atts){
    extract(shortcode_atts(array(
    'exclude' => '9,10',
    'n' => NULL
  ), $atts));
  $exclude = explode(',', $exclude);
  if($n)
  {
    $cats = get_terms("category",array('number'=>$n,'exclude'=>$exclude,'hide_empty'=>1));
  }
  else {
    $cats = get_terms("category",array('exclude'=>$exclude,'hide_empty'=>1));
  }
  if($cats)
  {
    $str = "
           <div class='side-cat'>";
    foreach ($cats as $cat) {
      if($cat->category_parent == $exclude || $cat->cat_ID == $exclude)continue;
      $str = $str."<a href='/".$cat->slug."'  class='side-cat-link' >".$cat->name." <span>(".$cat->count.")</span>"."</a><br />";
    }
    $str .= "<!--<a>カテゴリ一覧</a>--></div>";
  }
  return $str;
  }

    function newentry($atts){
    extract(shortcode_atts(array(
      'cat' => NULL,
      'exclude' => '9,2529,2530,2531',
      'n' => '6'
    ), $atts));
    global $wp_query;
    global $paged;
    if($cat){
      $category = get_term($cat,'category');
      if($category->term_id == $exclude || $category->parent == $exclude){
        /******　除外カテゴリに含まれる、または除外カテゴリの子である場合　*****/
        $the_query = new WP_query(array('cat'=>$cat,'posts_per_page'=>$n));
        $title ='<div class="newentry-title">
                   <div class="newentry-title-left">オススメ</div>
                   <div class="newentry-title-right"><a href="/push"><img src="http://umacure.net/wp-content/uploads/2014/04/midashi-ciecle.png" alt="side-mark" style="width:20px"> オススメ記事一覧</a></div>
                   <div class="clear"></div>
                 </div>';
        $pagination = '<div style="text-align:right; padding-right:2%;"><a href="/push"><img src="http://umacure.net/wp-content/uploads/2014/04/midashi-ciecle.png" alt="side-mark" style="width:20px"> オススメ記事一覧</a></div>';
        $cat = NULL;
      }
      else{
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $the_query = new WP_query(array('cat'=>$cat,'posts_per_page'=>$n,'paged'=>$paged));
        $catslug = $category->slug;
        $catname = $category->name;
        $title ='<div class="newentry-title">
                   <div class="newentry-title-left">'.$catname.'</div>




                   <div class="clear"></div>
                 </div>';
        $maxpage = $the_query->max_num_pages;
        if($maxpage>1){
          $pagination = '<div class="cat-pagenation">';
          $maxpnum = floor(($maxpage-1)/10);
          $pnum = floor(($paged-1)/10);
          if($pnum>0) $pagination .= '<a href="'.get_pagenum_link(($pnum-1)*10 + 1).'">...</a>';
          for ($i=$pnum*10 + 1; $i <= $pnum*10 + 10 ; $i++) {
            if($i > $maxpage)break;
            $pagination .= ($paged==$i) ? '<span class="now-page">'.$i.'</span>' : '<span class="other-page"><a href="'.get_pagenum_link($i).'">'.$i.'</a></span>';
          }
          if($pnum<$maxpnum) $pagination .= '<a href="'.get_pagenum_link(($pnum+1)*10 + 1).'">...</a>';
          $pagination .= '</div><div class="clear"></div>';
        }
        $title .= $pagination;
      }
    }
    else{
      $the_query = new WP_query(array('posts_per_page'=>$n));
      $title ='<div class="newentry-title">
                   <div class="newentry-title-left">新着記事</div>
                   <div class="newentry-title-right"><a href="/new"><img src="http://umacure.net/wp-content/uploads/2014/04/midashi-ciecle.png" alt="side-mark" style="width:20px"> 新着記事一覧</a></div>
                   <div class="clear"></div>
               </div>';
      $pagination = '<div style="text-align:right; padding-right:2%"><a href="/new"><img src="http://umacure.net/wp-content/uploads/2014/04/midashi-ciecle.png" alt="side-mark" style="width:20px"> 新着記事一覧</a></div>';
    }
    if($the_query->have_posts()){
      $str = '<div class="newentry">'.$title.'<div class="newentry-content">';
      while($the_query->have_posts()){
        $the_query->the_post();
          $cats = get_the_category();
          foreach ($cats as $c) {
            if($c->cat_ID != $exclude && $c->category_parent == 0){
              $category = $c;
              $catslug = $category->slug;
              $catname = $category->name;
            }
          }
        $str .= '<a href="'.get_permalink().'">
                  <div class="newentry-cell">

                      <div class="newentry-cell-thum">'.get_the_post_thumbnail().'</div>
                      <div class="newentry-cell-info">
                        <div class="newentry-cell-subinfo"><span class="cell-date">'.get_the_date('Y.m.d').'<br class="sp-br" /></span><span class="cell-cat">　'.$catname.'</span></div>
                        <div class="newentry-cell-posttitle"><span class="cell-title">'.get_the_title().'</span></div>
                      </div>
                  </div>
                </a>';
      }
      $str .= '<div class="clear"></div></div>'.$pagination.'</div>';
    }
    wp_reset_postdata();
    return $str;
  }

    function newentry_top($atts){
    extract(shortcode_atts(array(
      'cat' => NULL,
      'exclude' => '9',
      'n' => '6'
    ), $atts));
    global $wp_query;
    global $paged;
    if($cat){
      $category = get_term($cat,'category');
      if($category->term_id == $exclude || $category->parent == 9){
        /******　除外カテゴリに含まれる、または除外カテゴリの子である場合　*****/
        $the_query = new WP_query(array('cat'=>$cat,'posts_per_page'=>$n));
        $title ='<div class="newentry-title">
                   <div class="newentry-title-left">オススメ</div>
                   <div class="newentry-title-right pc-tablet"><a href="/push"><img src="http://umacure.net/wp-content/uploads/2014/04/midashi-ciecle.png" alt="side-mark" style="width:20px"> オススメ記事一覧</a></div>
                   <div class="clear"></div>
                 </div>';
        $pagination = '<div style="text-align:right; padding-right:2%;"><a href="/push"><img src="http://umacure.net/wp-content/uploads/2014/04/midashi-ciecle.png" alt="side-mark" style="width:20px"> オススメ記事一覧</a></div>';
        $cat = NULL;
      }
      else{
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $the_query = new WP_query(array('cat'=>$cat,'posts_per_page'=>$n,'paged'=>$paged));
        $catslug = $category->slug;
        $catname = $category->name;
        $title ='<div class="newentry-title">
                   <div class="newentry-title-left">'.$catname.'</div>




                   <div class="clear"></div>
                 </div>';
        $maxpage = $the_query->max_num_pages;
        if($maxpage>1){
          $pagination = '<div class="cat-pagenation">';
          $maxpnum = floor(($maxpage-1)/10);
          $pnum = floor(($paged-1)/10);
          if($pnum>0) $pagination .= '<a href="'.get_pagenum_link(($pnum-1)*10 + 1).'">...</a>';
          for ($i=$pnum*10 + 1; $i <= $pnum*10 + 10 ; $i++) {
            if($i > $maxpage)break;
            $pagination .= ($paged==$i) ? '<span class="now-page">'.$i.'</span>' : '<span class="other-page"><a href="'.get_pagenum_link($i).'">'.$i.'</a></span>';
          }
          if($pnum<$maxpnum) $pagination .= '<a href="'.get_pagenum_link(($pnum+1)*10 + 1).'">...</a>';
          $pagination .= '</div><div class="clear"></div>';
        }
        $title .= $pagination;
      }
    }
    else{
      $the_query = new WP_query(array('posts_per_page'=>$n));
      $title ='<div class="newentry-title">
                   <div class="newentry-title-left">新着記事</div>
                   <div class="newentry-title-right pc-tablet"><a href="/new"><img src="http://umacure.net/wp-content/uploads/2014/04/midashi-ciecle.png" alt="side-mark" style="width:20px"> 新着記事一覧</a></div>
                   <div class="clear"></div>
               </div>';
      $pagination = '<div style="text-align:right; padding-right:2%"><a href="/new"><img src="http://umacure.net/wp-content/uploads/2014/04/midashi-ciecle.png" alt="side-mark" style="width:20px"> 新着記事一覧</a></div>';
    }
    if($the_query->have_posts()){
      $str = '<div class="newentry_top">'.$title.'<div class="newentry-content">';
      while($the_query->have_posts()){
        $the_query->the_post();
        if(!$cat || $cat==$exclude){
          $cats = get_the_category();
          foreach ($cats as $c) {
            if($c->cat_ID != $exclude && $c->category_parent == 0){
              $category = $c;
              $catslug = $category->slug;
              $catname = $category->name;
            }
          }
        }
        $str .= '<a href="'.get_permalink().'">
                  <div class="newentry-cell">

                      <div class="newentry-cell-thum">'.get_the_post_thumbnail().'</div>
                      <div class="newentry-cell-info">
                        <div class="newentry-cell-subinfo"><span class="cell-date">'.get_the_date('Y.m.d').'<br class="sp-br" /></span><span class="cell-cat">　'.$catname.'</span></div>
                        <div class="newentry-cell-posttitle"><span class="cell-title">'.get_the_title().'</span></div>
                      </div>
                  </div>
                </a>';
      }
      $str .= '<div class="clear"></div></div>'.$pagination.'</div>';
    }
    wp_reset_postdata();
    return $str;
  }


    function newentry_push($atts){
    extract(shortcode_atts(array(
      'cat' => NULL,
      'exclude' => '9',
      'n' => '12'
    ), $atts));
    global $wp_query;
    global $paged;
    if($cat){
      $category = get_term($cat,'category');
      if($category->term_id == $exclude || $category->parent == $exclude){
        /******　除外カテゴリに含まれる、または除外カテゴリの子である場合　*****/
        $push = true;
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $the_query = new WP_query(array('cat'=>$cat,'posts_per_page'=>$n,'paged'=>$paged));
        $catslug = $category->slug;
        $catname = $category->name;
        $title ='<div class="newentry-title">
                   <div class="newentry-title-left">'.$catname.'</div>




                   <div class="clear"></div>
                 </div>';
        $maxpage = $the_query->max_num_pages;
        if($maxpage>1){
          $pagination = '<div class="cat-pagenation">';
          $maxpnum = floor(($maxpage-1)/10);
          $pnum = floor(($paged-1)/10);
          if($pnum>0) $pagination .= '<a href="'.get_pagenum_link(($pnum-1)*10 + 1).'">...</a>';
          for ($i=$pnum*10 + 1; $i <= $pnum*10 + 10 ; $i++) {
            if($i > $maxpage)break;
            $pagination .= ($paged==$i) ? '<span class="now-page">'.$i.'</span>' : '<span class="other-page"><a href="'.get_pagenum_link($i).'">'.$i.'</a></span>';
          }
          if($pnum<$maxpnum) $pagination .= '<a href="'.get_pagenum_link(($pnum+1)*10 + 1).'">...</a>';
          $pagination .= '</div><div class="clear"></div>';
        }
        $title .= $pagination;
      }
      else{
        $push = false;
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $the_query = new WP_query(array('cat'=>$cat,'posts_per_page'=>$n,'paged'=>$paged));
        $catslug = $category->slug;
        $catname = $category->name;
        $title ='<div class="newentry-title">
                   <div class="newentry-title-left">'.$catname.'</div>




                   <div class="clear"></div>
                 </div>';
        $maxpage = $the_query->max_num_pages;
        if($maxpage>1){
          $pagination = '<div class="cat-pagenation">';
          $maxpnum = floor(($maxpage-1)/10);
          $pnum = floor(($paged-1)/10);
          if($pnum>0) $pagination .= '<a href="'.get_pagenum_link(($pnum-1)*10 + 1).'">...</a>';
          for ($i=$pnum*10 + 1; $i <= $pnum*10 + 10 ; $i++) {
            if($i > $maxpage)break;
            $pagination .= ($paged==$i) ? '<span class="now-page">'.$i.'</span>' : '<span class="other-page"><a href="'.get_pagenum_link($i).'">'.$i.'</a></span>';
          }
          if($pnum<$maxpnum) $pagination .= '<a href="'.get_pagenum_link(($pnum+1)*10 + 1).'">...</a>';
          $pagination .= '</div><div class="clear"></div>';
        }
        $title .= $pagination;
      }
    }
    else{
      $the_query = new WP_query(array('posts_per_page'=>$n));
      $title ='<div class="newentry-title">
                   <div class="newentry-title-left">新着記事</div>
                   <div class="newentry-title-right"><a>新着記事一覧</a></div>
                   <div class="clear"></div>
               </div>';
      $pagination = '<div style="text-align:right"><a>新着記事一覧</a></div>';
    }
    if($the_query->have_posts()){
      $str = '<div class="newentry">'.$title.'<div class="newentry-content">';
      while($the_query->have_posts()){
        $the_query->the_post();
        if(!$cat || $push){
          $cats = get_the_category();
          foreach ($cats as $c) {
            if($c->cat_ID != $exclude && $c->category_parent == 0){
              $category = $c;
              $catslug = $category->slug;
              $catname = $category->name;
            }
          }
        }
        $str .= '<a href="'.get_permalink().'">
                  <div class="newentry-cell">

                      <div class="newentry-cell-thum">'.get_the_post_thumbnail().'</div>
                      <div class="newentry-cell-info">
                        <div class="newentry-cell-subinfo"><span class="cell-date">'.get_the_date('Y.m.d').'<br class="sp-br" /></span><span class="cell-cat">　'.$catname.'</span></div>
                        <div class="newentry-cell-posttitle"><span class="cell-title">'.get_the_title().'</span></div>
                      </div>
                  </div>
                </a>';
      }
      $str .= '<div class="clear"></div></div>'.$pagination.'</div>';
    }
    wp_reset_postdata();
    return $str;
  }


    function newentry_news($atts){
    extract(shortcode_atts(array(
      'cat' => NULL,
      'exclude' => '9',
      'n' => '6'
    ), $atts));
    global $wp_query;
    global $paged;
    if($cat){
      $category = get_term($cat,'category');
      if($category->term_id == $exclude || $category->parent == $exclude){
        /******　除外カテゴリに含まれる、または除外カテゴリの子である場合　*****/
        $the_query = new WP_query(array('cat'=>$cat,'posts_per_page'=>$n));
        $title ='<div class="newentry-title">
                   <div class="newentry-title-left">オススメ</div>
                   <div class="newentry-title-right"><a>オススメ記事一覧</a></div>
                   <div class="clear"></div>
                 </div>';
        $pagination = '<div style="text-align:right"><a>オススメ記事一覧</a></div>';
        $cat = NULL;
      }
      else{
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $the_query = new WP_query(array('cat'=>$cat,'posts_per_page'=>$n,'paged'=>$paged));
        $catslug = $category->slug;
        $catname = $category->name;
        $title ='<div class="newentry-title">
                   <div class="newentry-title-left">新着記事一覧</div>




                   <div class="clear"></div>
                 </div>';
        $maxpage = $the_query->max_num_pages;
        if($maxpage>1){
          $pagination = '<div class="cat-pagenation">';
          $maxpnum = floor(($maxpage-1)/5);
          $pnum = floor(($paged-1)/5);
          if($pnum>0) $pagination .= '<a href="'.get_pagenum_link(($pnum-1)*5 + 1).'">...</a>';
          for ($i=$pnum*5 + 1; $i <= $pnum*5 + 5 ; $i++) {
            if($i > $maxpage)break;
            $pagination .= ($paged==$i) ? '<span class="now-page">'.$i.'</span>' : '<span class="other-page"><a href="'.get_pagenum_link($i).'">'.$i.'</a></span>';
          }
          if($pnum<$maxpnum) $pagination .= '<a href="'.get_pagenum_link(($pnum+1)*5 + 1).'">...</a>';
          $pagination .= '</div><div class="clear"></div>';
        }
        $title .= $pagination;
      }
    }
    else{
      $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
      $the_query = new WP_query(array('posts_per_page'=>$n,'paged'=>$paged));
      $title ='<div class="newentry-title">
                   <div class="newentry-title-left">新着記事</div>
                   <div class="clear"></div>
               </div>';
      $maxpage = $the_query->max_num_pages;
        if($maxpage>1){
          $pagination = '<div class="cat-pagenation">';
          $maxpnum = floor(($maxpage-1)/10);
          $pnum = floor(($paged-1)/10);
          if($pnum>0) $pagination .= '<a href="'.get_pagenum_link(($pnum-1)*10 + 1).'">...</a>';
          for ($i=$pnum*10 + 1; $i <= $pnum*10 + 10 ; $i++) {
            if($i > $maxpage)break;
            $pagination .= ($paged==$i) ? '<span class="now-page">'.$i.'</span>' : '<span class="other-page"><a href="'.get_pagenum_link($i).'">'.$i.'</a></span>';
          }
          if($pnum<$maxpnum) $pagination .= '<a href="'.get_pagenum_link(($pnum+1)*10 + 1).'">...</a>';
          $pagination .= '</div><div class="clear"></div>';
        }
        $title .= $pagination;
    }
    if($the_query->have_posts()){
      $str = '<div class="newentry">'.$title.'<div class="newentry-content">';
      while($the_query->have_posts()){
        $the_query->the_post();
        if(!$cat || $cat==$exclude){
          $cats = get_the_category();
          foreach ($cats as $c) {
            if($c->cat_ID != $exclude && $c->category_parent == 0){
              $category = $c;
              $catslug = $category->slug;
              $catname = $category->name;
            }
          }
        }
        $str .= '<a href="'.get_permalink().'">
                  <div class="newentry-cell">

                      <div class="newentry-cell-thum">'.get_the_post_thumbnail().'</div>
                      <div class="newentry-cell-info">
                        <div class="newentry-cell-subinfo"><span class="cell-date">'.get_the_date('Y.m.d').'<br class="sp-br" /></span><span class="cell-cat">　'.$catname.'</span></div>
                        <div class="newentry-cell-posttitle"><span class="cell-title">'.get_the_title().'</span></div>
                      </div>
                  </div>
                </a>';
      }
      $str .= '<div class="clear"></div></div>'.$pagination.'</div>';
    }
    wp_reset_postdata();
    return $str;
  }





  function meteor_container_shortcode($atts, $content='', $code='') { global $der_framework;
    return '<div class="container">' . "\n" . $der_framework->shortcode($content) . "\n" . '</div><!-- .container -->';
  }

  function meteor_visibility_shortcode($atts, $content='', $code='') { global $der_framework;

    $atts = (array) $atts;

    $defaults = array(
      'on' => null,
      'margin' => null,
      'padding' => null
    );

    $args = shortcode_atts($defaults, $atts);

    $args['mode'] = $code;
    $args['content'] = $der_framework->shortcode(remove_br($content));

    foreach (array('margin', 'padding') as $opt) {
      if (isset($args[$opt])) {
        $args[$opt] = preg_replace('/\;$/', '', $args[$opt]);
        if (preg_match('/^[\-]*\d+$/', $args[$opt])) $args[$opt] .= 'px';
      }
    }

    return $der_framework->render_template('meteor-visibility.mustache', $args);

  }

  function meteor_styling_shortcodes($atts, $content='', $code='') { global $der_framework;

    $atts = (array) $atts;
    $out = null;

    switch ($code) {

      case 'clear':
        $out = '<div class="clear"></div>';
        break;

      case 'push':

        $args = shortcode_atts(array(
          'height' => '1em',
          'margin' => null,
          'class' => null
        ), $atts);

        if (preg_match('/^\d+$/', $args['height'])) $args['height'] .= 'px';
        if (isset($args['margin'])) $args['margin'] = preg_replace('/\;$/', '', $args['margin']);
        if (isset($args['class'])) $args['class'] = ' ' . $args['class'];

        $out = sprintf('<div class="push%s" style="height: %s !important; margin: %s !important;"></div>', $args['class'], $args['height'], $args['margin']);

        break;

    }

    return $out . "\n";

  }


  function meteor_toggle($atts, $content='', $code='') { global $der_framework;

    $atts = (array) $atts;

    $defaults = array(
      'title' => null,
      'icon' => null
    );

    $args = wp_parse_args($atts, $defaults);

    $args['title'] = theme_mini_shortcode($args['title']);
    $args['title'] = apply_filters('the_title', $args['title']);
    $args['content'] = $der_framework->content($content);

    if (in_array('active', $atts)) {
      $args['active'] = true;
    }

    if (in_array('medium', $atts)) {
      $args['size'] = 'medium';
    } else if (in_array('small', $atts)) {
      $args['size'] = 'small';
    } else {
      $args['size'] = 'large';
    }

    return $der_framework->render_template('meteor-toggle.mustache', $args);

  }

  function meteor_quote($atts, $content='', $code='') { global $der_framework;

    $defaults = array(
      'author' => null,
      'description' => null,
      'url' => null,
      'content' => null
    );

    $args = wp_parse_args($atts, $defaults);

    if (empty($content) && isset($args['content'])) {
      $content = $args['content'];
    }

    $content = theme_mini_shortcode(remove_br($content));

    $args['content'] = $der_framework->content($content, false);

    return $der_framework->render_template('meteor-quote.mustache', $args);

  }

  function meteor_tabs($atts, $content='', $code='') { global $der_framework;

    $atts = (array) $atts;

    $defaults = array(
      'title' => null,
      'icon' => null
    );

    $args = wp_parse_args($atts, $defaults);

    if (in_array('active', $atts)) $args['active'] = true;
    if (in_array('first', $atts)) $args['first'] = true;
    if (in_array('last', $atts)) $args['last'] = true;

    $args['content'] = $der_framework->content(remove_br($content));

    return $der_framework->render_template('meteor-tabs.mustache', $args);

  }


  function meteor_accordion($atts, $content='', $code='') { global $der_framework;

    $atts = (array) $atts;

    $defaults = array(
      'title' => null
    );

    $args = wp_parse_args($atts, $defaults);

    if (in_array('active', $atts)) $args['active'] = true;
    if (in_array('first', $atts)) $args['first'] = true;
    if (in_array('last', $atts)) $args['last'] = true;

    $args['content'] = $der_framework->content(remove_br($content));

    return $der_framework->render_template('meteor-accordion.mustache', $args);

  }


  function meteor_skillset($atts, $content='', $code='') { global $der_framework;

    $atts = (array) $atts;

    $defaults = array(
      'title' => $content,
      'percent' => '5',
      'icon' => null
    );

    $args = wp_parse_args($atts, $defaults);

    if (in_array('first', $atts)) {
      $args['first'] = true;
    } else if (in_array('last', $atts)) {
      $args['last'] = true;
    }

    $args['title'] = theme_mini_shortcode($args['title']);
    $args['percent'] = (int) $args['percent'];
    $args['icon'] = preg_replace('/^icon-/', '', $args['icon']);

    return $der_framework->render_template('meteor-skills.mustache', $args);

  }


  function meteor_title_heading($atts, $content='', $code='') { global $der_framework;

    $defaults = array(
      'text' => $content
    );

    $args = wp_parse_args($atts, $defaults);

    $content = theme_mini_shortcode(esc_html($args['text']));

    return sprintf('<h2 class="title-heading">%s</h2>', $content);

  }


  function shortcode_template($atts, $content='', $code='') { global $der_framework;

    $atts = (array) $atts;

    $defaults = array(

    );

    $args = wp_parse_args($atts, $defaults);

    return $der_framework->render_template('.mustache', $args);

  }

?>