<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $template->title??'Cartoonacard' }}</title>
        <meta name="description" content="{{ $template->description??'' }}"/>

        <meta property="og:type" content="website">
        <meta property="og:site_name" content="Cartoonacard">
        <meta property="og:title" content="{{$template->title??'Cartoonacard'}}">
        <meta property="og:description" content="{{ $template->description }}">
        <meta property="og:url" content="https://cartoonacard.com/{{$url??$template->url}}">
        <meta property="og:image" content="https://cartoonacard.com{{(!empty($template->getMedia()[0]))?$template->getMedia()[0]->getUrl('share'):''}}">
        <meta property="og:image:width" content="968">
        <meta property="og:image:height" content="504">

        <meta name=“yandex-verification” content=“e77314bac6a92bba” />
        <link href="/css/animate.css" rel="stylesheet" type="text/css">
        <link href="/css/styles.css?v=<?=time()?>" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <?php if (!empty($template->font) && !in_array($template->font, ['Arial','Helvetica','Times New Roman','Times','Courier New','Courier','Verdana','Georgia','Palatino','Garamond','Bookman','Comic Sans MS','Trebuchet MS','Impact'])){?>
        <link href="https://fonts.googleapis.com/css?family=<?=$template->font?>&subset=cyrillic,cyrillic-ext" rel="stylesheet">
        <?php }?>
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <?php if (!empty($template->cursor)){?>
        <script type="text/javascript" src="/js/cursor/<?=$template->cursor?>.js"></script>
        <?php }?>
        <?php if (!empty($template->animation)){?>
        <script type="text/javascript" src="/js/animations/<?=$template->animation?>/<?=$template->animation?>.js"></script>
        <?php }?>
        <script type="text/javascript" src="/js/yall.min.js"></script>
        <meta name="csrf-token" content="{{csrf_token()}}"/>
        <?php
            $style = $bgfile = $cardtyle = '';

            if (!empty($template->background_file))
            {
                $bgfile = json_decode($template->background_file,true);
                $bgfile = Voyager::image($bgfile[0]['download_link']);
            }

            if (!empty($bgfile) && strpos($bgfile, '.mp4')==false)
                $cardtyle .= 'background:url('.$bgfile.') no-repeat 50% 50%; background-size:cover;';

            if (!empty($template->background_color))
                $cardtyle .= 'background-color:'.$template->background_color.';';

            if (!empty($template->font))
                $cardtyle .= ' font-family:'.$template->font.';';

            if (!empty($template->fontcolor))
                $cardtyle .= ' color:'.$template->fontcolor.';';

            if (!empty($template->fontsize))
                $cardtyle .= ' font-size:'.$template->fontsize.'px;';

            if (!empty($cardtyle))
                $style .= '#cart-template {'.$cardtyle.'}';

            if (!empty($template->border_image))
            {
                $image = Voyager::image($template->border_image);
                $style .= '.left-image img {'.$template->border_style.' border-image-source:url('.$image.');}';
            }

            if (!empty($style))
                echo '<style type="text/css">'.$style.'</style>';

            echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.1.1/howler.min.js"></script>
                  <script>var alldata = '.json_encode($json).';</script>';
        ?>
        <script type="text/javascript" src="/js/main.js?v=<?=time()?>"></script>
    </head>
    <body>
        <div id="buttons">
            <a class="active sound" href="#"></a>
            <a class="share-button" href="#"></a>
        </div>
    	<div id="cart-template">
            <?php if (!empty($bgfile) && strpos($bgfile, '.mp4')){?>
            <video autoplay loop muted playsinline id="BackgroundVideo">
              <source src="<?=$bgfile?>" type="video/mp4">
            </video>
            <?php }?>
    		<div class="left-image">
                <h1 id="header"><?=$json[0]['title']??''?></h1>
                <div class="frames">
                    <?php foreach ($json as $key => $frame) {?>
                    <div class="frame" data-id="<?=$frame['id_frame']?>">
                        <?php if (!empty($frame['img'])) {?>
                        <img class="lazy" src="<?=$frame['img']?>" alt=""/>
                        <?php }?>
                    </div>
                    <?php }?>
                    <div id="help-click" class="animated pulse">Tap on image</div>
                </div>
                @if(true || !($id_card??0))
                <div class="last-frame">
                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- Cartoona -->
                    <div>
                        <ins class="adsbygoogle"
                             style="display:inline-block;width:300px;height:250px"
                             data-ad-client="ca-pub-8030762302890815"
                             data-ad-slot="7778836722"></ins>
                    </div>
                    <script>
                        // (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                    <!--
                    <div class="money-info">
                        Get card with custom text<br/> for only 2.99 USD<br/>
                        <a href="#">Partner program for you</a>
                        <a class="btn" href="/personal/new/<?=$template->id_cardtemplate?>">Customize card</a>
                    </div>
                    -->
                    <!--a class="btn" href="/personal/new/<?=$template->id_cardtemplate?>">Customize card</a-->
                    <a class="replay" href="">Replay</a>&nbsp; &nbsp;<a class="customize" href="/personal/new/<?=$template->id_cardtemplate?>">Customize</a>
                    <div class="likes">
                        <a data-id="<?=$template->id_cardtemplate?>" class="like" href="javascript:"><span><?=$template->like?></span> 👍</a>
                        <a data-id="<?=$template->id_cardtemplate?>" class="unlike" href="javascript:"><span><?=$template->unlike?></span> 👎</a>
                    </div>
                </div>
                @endif
    		</div>
    		<div class="middle-section">
    		</div>
    		<div class="right-text">
                <div class="frames">
                    <?php foreach ($json as $key => $frame) {?>
                    <div class="frame" data-id="<?=$frame['id_frame']?>" id="content<?=$frame['id_frame']?>">
                        <?=$frame['text']?>
                    </div>
                    <?php }?>
                </div>
    		</div>
    	</div>
    	<footer>
    		<a id="logo" href="/">Cartoona card</a>
    		<nav>
    			<!--a href="#">About us</a>
    			<a href="#">Send a cart</a>
    			<a href="#">Contacts</a>
    			<a href="#">Random cart</a-->
                <a href="/personal/new/<?=$template->id_cardtemplate?>">Customize card</a>
                <a href="/personal/login">Login</a>
    		</nav>
    	</footer>
        <!-- Yandex.Metrika counter -->
        <script type="text/javascript" >
          (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
          m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
          (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
          ym(52375480, "init", {
               id:52375480,
               clickmap:true,
               trackLinks:true,
               accurateTrackBounce:true,
               webvisor:true
          });
        </script>
        <noscript><div><img src="https://mc.yandex.ru/watch/5237548" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
        <!-- /Yandex.Metrika counter -->
        @php
            $shareurl = urlencode("https://cartoonacard.com/".($url??$template->url));
        @endphp
        <div class="share">
            <a class="close" href="#">&times;</a>
            <a class="resp-sharing-button__link" href="https://facebook.com/sharer/sharer.php?u={{$shareurl}}" target="_blank" rel="noopener" aria-label="Facebook">
              <div class="resp-sharing-button resp-sharing-button--facebook resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg></div>Facebook</div>
            </a>
            <a class="resp-sharing-button__link" href="https://twitter.com/intent/tweet/?url={{$shareurl}}" target="_blank" rel="noopener" aria-label="Twitter">
              <div class="resp-sharing-button resp-sharing-button--twitter resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/></svg></div>Twitter</div>
            </a>
            <a class="resp-sharing-button__link" href="whatsapp://send?text={{$shareurl}}" target="_blank" rel="noopener" aria-label="WhatsApp">
              <div class="resp-sharing-button resp-sharing-button--whatsapp resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20.1 3.9C17.9 1.7 15 .5 12 .5 5.8.5.7 5.6.7 11.9c0 2 .5 3.9 1.5 5.6L.6 23.4l6-1.6c1.6.9 3.5 1.3 5.4 1.3 6.3 0 11.4-5.1 11.4-11.4-.1-2.8-1.2-5.7-3.3-7.8zM12 21.4c-1.7 0-3.3-.5-4.8-1.3l-.4-.2-3.5 1 1-3.4L4 17c-1-1.5-1.4-3.2-1.4-5.1 0-5.2 4.2-9.4 9.4-9.4 2.5 0 4.9 1 6.7 2.8 1.8 1.8 2.8 4.2 2.8 6.7-.1 5.2-4.3 9.4-9.5 9.4zm5.1-7.1c-.3-.1-1.7-.9-1.9-1-.3-.1-.5-.1-.7.1-.2.3-.8 1-.9 1.1-.2.2-.3.2-.6.1s-1.2-.5-2.3-1.4c-.9-.8-1.4-1.7-1.6-2-.2-.3 0-.5.1-.6s.3-.3.4-.5c.2-.1.3-.3.4-.5.1-.2 0-.4 0-.5C10 9 9.3 7.6 9 7c-.1-.4-.4-.3-.5-.3h-.6s-.4.1-.7.3c-.3.3-1 1-1 2.4s1 2.8 1.1 3c.1.2 2 3.1 4.9 4.3.7.3 1.2.5 1.6.6.7.2 1.3.2 1.8.1.6-.1 1.7-.7 1.9-1.3.2-.7.2-1.2.2-1.3-.1-.3-.3-.4-.6-.5z"/></svg></div>WhatsApp</div>
            </a>
            <a class="resp-sharing-button__link" href="http://vk.com/share.php?url={{$shareurl}}" target="_blank" rel="noopener" aria-label="VK">
              <div class="resp-sharing-button resp-sharing-button--vk resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21.547 7h-3.29a.743.743 0 0 0-.655.392s-1.312 2.416-1.734 3.23C14.734 12.813 14 12.126 14 11.11V7.603A1.104 1.104 0 0 0 12.896 6.5h-2.474a1.982 1.982 0 0 0-1.75.813s1.255-.204 1.255 1.49c0 .42.022 1.626.04 2.64a.73.73 0 0 1-1.272.503 21.54 21.54 0 0 1-2.498-4.543.693.693 0 0 0-.63-.403h-2.99a.508.508 0 0 0-.48.685C3.005 10.175 6.918 18 11.38 18h1.878a.742.742 0 0 0 .742-.742v-1.135a.73.73 0 0 1 1.23-.53l2.247 2.112a1.09 1.09 0 0 0 .746.295h2.953c1.424 0 1.424-.988.647-1.753-.546-.538-2.518-2.617-2.518-2.617a1.02 1.02 0 0 1-.078-1.323c.637-.84 1.68-2.212 2.122-2.8.603-.804 1.697-2.507.197-2.507z"/></svg></div>VK</div>
            </a>
            <a class="resp-sharing-button__link" href="https://telegram.me/share/url?url={{$shareurl}}" target="_blank" rel="noopener" aria-label="Telegram">
              <div class="resp-sharing-button resp-sharing-button--telegram resp-sharing-button--medium"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M.707 8.475C.275 8.64 0 9.508 0 9.508s.284.867.718 1.03l5.09 1.897 1.986 6.38a1.102 1.102 0 0 0 1.75.527l2.96-2.41a.405.405 0 0 1 .494-.013l5.34 3.87a1.1 1.1 0 0 0 1.046.135 1.1 1.1 0 0 0 .682-.803l3.91-18.795A1.102 1.102 0 0 0 22.5.075L.706 8.475z"/></svg></div>Telegram</div>
            </a>
        </div>
    </body>
</html>
