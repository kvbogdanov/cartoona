<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Cartoon</title>
        <link href="/css/styles.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" src="/js/main.js"></script>
        <script type="text/javascript" src="/js/main.js"></script>
        <meta name="csrf-token" content="{{csrf_token()}}"/>
        <meta name=“yandex-verification” content=“e77314bac6a92bba” />
    </head>
    <body>
        <div class="cards">
        <?php foreach ($templates as $data){?>
            <a href="/<?=($data->url)?$data->url:'cart/'.$data->id_cardtemplate?>" class="card">
            <?php
            $coverimage = $data->getMedia()[0]??false;
            if($coverimage) {
                ?>
                <img src="<?= $coverimage->getUrl('thumb')?>">
            <?php
                }
            ?>
            <br/>
            <?=$data->name?><br>
            👍 <?=$data->like?>
           </a>
        <?php }?>
        </div>
    	<footer>
    		<a id="logo" href="/">Cartoona card</a>
    		<nav>
    			<a href="#">About us</a>
    			<a href="#">Send a cart</a>
    			<a href="#">Contacts</a>
    			<a href="#">Random cart</a>
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
    </body>
</html>
