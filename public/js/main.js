document.addEventListener("DOMContentLoaded", yall);

var sound = false;
var sound_active = true;
var newindex = 0;
var index = 0;

$(document).ready(function()
{
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

	$(".share-button").click(function(){
		$(".share").css("display", "flex")
					    .hide()
					    .fadeIn();
	});

	$(".close").click(function(){
		$(".share").fadeOut('fast');
		return false;
	});

	setTimeout(function(){
		$("#help-click").addClass('animated fadeOutDown');
	},5000);
	
	$(".sound").click(function(){
		$(this).toggleClass('active');

		if ($(this).hasClass('active'))
		{
			if (alldata[newindex].audios!='')
	    	{
	    		if (sound)
	    			sound.stop();

	    		sound = new Howl({
					html5: true,
					autoplay: true,
					src: [alldata[newindex].audios[0]]
				});

				sound.play();
	    	}
		}
		else if (sound)
	    		sound.stop();

	    return false;
	});

	if (alldata!=undefined)
	{
	    if (document.location.hash != '')
	    {
	    	$(".frame").hide();
	    	$(".frame[data-id="+document.location.hash.replace('#','')+"]").show();

			for (let i = 0; i < alldata.length; i++) {
				if(alldata[i].id_frame == document.location.hash.replace('#',''))
					$('#header').text(alldata[i].title);
			}
	    }
	    else
	    {

	    	index = 0;

	    	if (alldata[0].audios!='')
	    	{
	    		//console.log(alldata.audios[0][0]);
	    		sound = new Howl({
				  src: [alldata[0].audios[0]],
				  html5: true,
				  autoplay: true,
				});

				if (sound_active)
					sound.play();
	    	}

	    	$("#slider img").each(function(){
	    		$(this).attr('width',$(this).width()).attr('height',$(this).height());
	    	});

			$(".frame img, #help-click").click(function(){

				$("#help-click").addClass('animated fadeOutDown');
				newindex = index+1;

				if ((newindex+1)>alldata.length)
				{
					$(".last-frame")
					    .css("display", "flex")
					    .hide()
					    .fadeIn(function(){
							(adsbygoogle = window.adsbygoogle || []).push({});
						});

					//$(".share").show();
					$(".share-footer").addClass('uplift');
				}
				else
				{
					if (alldata[newindex].audios!='')
			    	{
			    		if (sound)
			    			sound.stop();

			    		sound = new Howl({
							html5: true,
							autoplay: true,
							src: [alldata[newindex].audios[0]]
						});

						if (sound_active)
							sound.play();
			    	}

					if (alldata[newindex].title!='')
						$("h1").text(alldata[newindex].title);

					if (alldata[newindex].text!='')
					{
						$(".right-text .frame:visible").fadeOut(200,function(){
							$(".right-text .frame:eq("+newindex+")").fadeIn('fast');
						});
					}

					if (alldata[newindex].img!='')
					{
						$(".left-image .frame:visible img").addClass('animated '+alldata[index].effectOut);

						setTimeout(function(){
							$(".left-image .frame:visible").hide();
							$(".left-image .frame:eq("+newindex+")").css("display", "flex").find('img').addClass('animated '+alldata[newindex].effect);
						} ,800);
					}
				}

				ym(52375480, 'reachGoal', 'frame'+index);

				index++;
			});
		}

		$(".like, .unlike").click(function(){

			var $link = $(this);

			var t = 0;

			if ($link.hasClass('like'))
				t = 1;

			$.ajax({
		      url: '/like/'+$link.data('id'),
		      type: 'post',
		      dataType: "json",
		      data: 'l='+t,
		      success: function(data)
		      {
		      	$(".like").find('span').html(data.like);
		      	$(".unlike").find('span').html(data.unlike);
		      }
		    });

			return false;
		})
	}
})