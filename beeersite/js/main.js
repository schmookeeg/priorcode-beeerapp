$(document).ready(function() {
	$(".ablink").click(function(e) {
		$(".ab").hide();
		$(".ab." + $(e.target).attr("target")).show();
	});
	var vars = [], hash;
	    var q = document.URL.split('?')[1];
	    if(q != undefined){
	        q = q.split('&');
	        for(var i = 0; i < q.length; i++){
	            hash = q[i].split('=');
	            vars.push(hash[1]);
	            vars[hash[0]] = hash[1];
	        }
	}
	if(vars['mobile'] == "true")
		$("body").addClass("mobile");	

	fadeItem();
	var userID = 0;//1124451366;
	
	$('input#search_input').live({
		keyup: function() {
			if($('#search_cont form input#search_input').val() == "")
				pullRecentBeers(userID);
			else
				pullSearchBeers(userID,$('#search_cont form input#search_input').val());
				
		},
		focus: function() {
			if($('input#search_input').val()=='Search Beeers') {
				$('input#search_input').val('');
			}
		}
	});
	
	$("#wall_of_beer_about").live({
		click: function() {
			$("ul#beeers").hide();
			$("#top_bar").hide();
			$('#othercontent').hide();
			$(".holder").show();
			$("#get_bar").show();
		}
	});	
	
	$("#get_button").live({
		click: function() {
			$("#get_bar").hide();
			$(".holder").hide();
			$("body").css("background-image","none");
			$('#othercontent').hide();
			$("ul#beeers").show();
			$("#top_bar").show();

		}
	});
	$("#btn_get_app").live({
		click: function() { 
		$.get('about.html', function(beerFrameTmpl) {
			$("ul#beeers").hide();
			$("#top_bar").hide();
			$("#get_bar").show();
			$("body").css("background-image","url(/Images/BG.jpg)");
			$('#othercontent').html(beerFrameTmpl).show();
	
		});
		
		}
	});	
		
	$(".rightArrow").live({ click: function() { 
		var img = $("div.phoneImage img").attr("image");
		if(img == 5)
			img = 1;
		else
			img++;
			
		$("div.phoneImage img").hide("slide", { direction: "right", complete: function() { $("div.phoneImage img").attr("src","Images/PhoneImage" + img + ".jpg").attr("image",img).show("slide", { direction: "left" }, 200); } }, 200);
	 }
	 });

	$(".leftArrow").live({ click: function() { 
		var img = $("div.phoneImage img").attr("image");
		if(img == 1)
			img = 5;
		else
			img--;
			
		$("div.phoneImage img").hide("slide", { direction: "left", complete: function() { $("div.phoneImage img").attr("src","Images/PhoneImage" + img + ".jpg").attr("image",img).show("slide", { direction: "right" }, 200); } }, 200);
	 }
	 });				
	
	$(".interactive_btn").hover(function () {
	    //$(this).css("backgroundPosition", "bottom left");
	}, function () {
	    //$(this).css("backgroundPosition", "top left");
	});

	$("#btn_recent").live({
		click: function() { pullRecentBeers(userID); }
	});
	$("#btn_rating").live({
		click: function() { pullRatingBeers(userID); }
	});
	$("#btn_popular").live({
		click: function() { pullPopularBeers(userID); }
	});
	
	$("#btn_back").live({
		click: function() { 
			//pullRecentBeers(userID); 
			$("#btn_recent").show();
			$("#btn_rating").show();
			$("#btn_popular").show();
			$("#btn_back").hide();	
			$("#aggbeeers").hide();
			$("#aggbeeers li").hide();
			$("#beeers").show();	
			$("#beeers li").show();	
			}
	});
	

	$(".beeer_frame").live({
        mouseenter:
           function()
           {
				$(this).children(".img_cont").removeClass("fifty_per_opacity");
           },
		mouseleave:
			function()
			{
				$(this).children(".img_cont").addClass("fifty_per_opacity");
			},
		click:
			function(e)
			{
				var beerID = $(this).data('beerID');
				var beerName = $(this).data('beerName');
				if($(e.target).parents("li").hasClass("aggregateBack"))
					{
					if($(e.target).parents("li").attr("aggregate")=="true")
						{
						loadBeerDetailDataAggSingle(userID,beerName);
						}
					else
						{
						loadBeerDetailData(userID,beerName);
						}
					var newtop = $(e.target).offset().top;
					$("#beeer_detail").css("top",newtop);
					}
				else
					{
					
					if($(e.target).parents("li").attr("aggregate") == "true")
						{
						$("#btn_recent").hide();
						$("#btn_rating").hide();
						$("#btn_popular").hide();
						$("#btn_back").show();	
						pullAggregateBeers(userID,beerName);
					
						}
					else
						{

						if($(e.target).parents("li").data("beerID"))
							loadBeerDetailDataByID(userID,$(e.target).parents("li").data("beerID"));
						else
							loadBeerDetailData(userID,beerName);
						
						
						var newtop = $(e.target).offset().top;
						$("#beeer_detail").css("top",newtop);
						//alert(beerID);
						}
					}
		}
       }
    );		

	$('#beeer_detail_close').click(function() {
		$('#beeer_detail_mask').hide();
		$('#beeer_detail').hide();
	});
	
	$("#beeer_detail_mask").click(function() {
		$('#beeer_detail_mask').hide();
		$('#beeer_detail').hide();		
	});
	
	pullRecentBeers(userID);
});

var input = $('input[type=text]');

input.focus(function() {
    $(this).val('');
}).blur(function() {
    var el = $(this);

    /* use the elements title attribute to store the 
       default text - or the new HTML5 standard of using
       the 'data-' prefix i.e.: data-default="some default" */
    if(el.val() == '')
        el.val(el.attr('title'));
});


function fadeItem() 
{
   $('#entries ul li:hidden:first').fadeIn(350, fadeItem);  
}

function parseBeerWheel(wheelData) {

	$('#beer_graph').empty();
	
	var paper = new Raphael(document.getElementById('beer_graph'), 238, 238);      
	
	var center_x = 238/2;
	var center_y = 238/2;
    
	pointArray = [];
	pointArray[0] = wheelData.bitter;
	pointArray[1] = wheelData.body;
	pointArray[2] = wheelData.linger;
	pointArray[3] = wheelData.aroma;
	pointArray[4] = wheelData.citrus;
	pointArray[5] = wheelData.hoppy;
	pointArray[6] = wheelData.floral;
	pointArray[7] = wheelData.malty;
	pointArray[8] = wheelData.toffee;
	pointArray[9] = wheelData.burnt;
	pointArray[10] = wheelData.sweet;
	pointArray[11] = wheelData.sour;
	
	var radius = 226 / 2;
	var twoPI = Math.PI * 2 ;
	var xValue;
	var yValue;
	
	var serialized_points_arr = "M ";
	
	for (var i = 0; i < 12; i++) {
		
		var deg = (360 / 12) *  (i);
		var rad = deg * (Math.PI / 180);
		
		xValue = Math.floor( Math.sin( rad ) * (radius * (pointArray[i] *.25)) );
		yValue = Math.floor( Math.cos( rad ) * (radius * (pointArray[i] *.25)) ) * -1;
		
		serialized_points_arr += xValue + center_x;
		serialized_points_arr += " ";
		serialized_points_arr += (yValue + center_y);// * -1;
		
		$('#beeer_wheel_dots li:eq('+( i)+')').css({
			top: (yValue + center_y - 3.5)+"px",
			left: (xValue + center_x - 3.5)+"px"
		})
		
		if(i == 11)
		{
			serialized_points_arr += " Z ";
		}else
		{
			serialized_points_arr += " L ";
		}
		
	}
	
	var flavor = paper.path(serialized_points_arr);
	flavor.attr({fill: '#c34e00', stroke: '#c34e00', 'stroke-width': 1});
	
}

function parseServing(servingSize) {
	$('#beeer_detail_serving_'+servingSize+' div').addClass('sq_radio_selected');
}

function parseBeerRating(starNum)
{
	var stars = '';
	for (var i=0;i<10;i++) {
		if(i < starNum) {
			stars = stars + '<li class="rating_on"></li>';
		} else {		
			stars = stars + '<li class="rating_off"></li>';
		}
	}
	return stars;
}

function parseBeerDetail(jsonData) 
{
	var jData = $.parseJSON(jsonData);
	var beer = jData.beerArray;
	var stars;

	$('#beeer_detail_name').html(beer.name);
	if(!beer.brewery)
		addClass = "empty";
	else
		addClass = "";
	$('#beeer_detail_brewery').html(beer.brewery).removeClass("empty").addClass(addClass);
	
	if(!beer.style)
		addClass = "empty";
	else
		addClass = "";
	
	$('#beeer_detail_type').html(beer.style).removeClass("empty").addClass(addClass);
	
	var stars = parseBeerRating(beer.rating);
	$('#beeer_detail_rating ul.star_rating').html(stars);

	if(beer.abv == 0 && beer.ibu == 0 && beer.og == 0 && beer.tg == 0)
		$("#beeer_detail_stats").addClass("empty");
	else
		$("#beeer_detail_stats").removeClass("empty");

	$('#beeer_detail_abv h5').html(beer.abv+'%');
	$('#beeer_detail_ibu h5').html(beer.ibu);
	$('#beeer_detail_og h5').html(beer.og);
	$('#beeer_detail_tg h5').html(beer.tg);
	
	$('.sq_radio').removeClass('sq_radio_selected');
	$('.sq_radio').addClass('sq_radio_unselected');
	$('#beeer_detail_serving_'+beer.serving.toLowerCase()+' .sq_radio').removeClass('sq_radio_unselected');
	$('#beeer_detail_serving_'+beer.serving.toLowerCase()+' .sq_radio').addClass('sq_radio_selected');
	
	if(!beer.serving)
		$("#beeer_detail_serving").addClass("empty");
	else
		$("#beeer_detail_serving").removeClass("empty");
	
	wheelObj = new Object();
	wheelObj.bitter = beer.bitter;
	wheelObj.body = beer.body;
	wheelObj.linger = beer.linger;
	wheelObj.aroma = beer.aroma;
	wheelObj.citrus = beer.citrus;
	wheelObj.hoppy = beer.hoppy;
	wheelObj.floral = beer.floral;
	wheelObj.malty = beer.malty;
	wheelObj.toffee = beer.toffee;
	wheelObj.burnt = beer.burnt;
	wheelObj.sweet = beer.sweet;
	wheelObj.sour = beer.sour;
	
	addClass = "empty";
	$(wheelObj).each(function(i,v)
		{
		if(v)
			addClass = "";
		});
	
	parseBeerWheel(wheelObj);

    if(beer.photoURL == "")
    {
    	//beer.photoURL = "http://wallofbeeer.com/i/no_beeer.jpg";
		$('#beeer_detail_photo').addClass("empty");
		$('#beeer_detail_photo').hide();
	}
	else
		{
		
		$('#beeer_detail_photo').removeClass("empty").find("img").attr("src",beer.photoURL);
		}
	if(!beer.notes || beer.num>1)
		addClass = "empty";
	else
		addClass = "";	
	$('#beeer_detail_desc').html(beer.notes).removeClass("empty").addClass(addClass);
	
	$("#beeer_detail_mask").show();
	$('#beeer_detail').show();
	
}
/*
function parseBeerFrame(jsonData) 
{
	var jData = $.parseJSON(jsonData);
	var beer = jData.beerArray;
	var stars;

	$('#beeer_detail_name').html(beer.name);
	$('#beeer_detail_brewery').html(beer.brewery);
	$('.img_cont').attr('src',beer.photoURL);
		
	var stars = parseBeerRating(beer.rating);
	$('ul.star_rating').html(stars);
		
}
*/
function buildBeerList(resp, agg)
	{
		
		if(!agg)
			{
			agg = "ul#beeers";
			nonagg = "ul#aggbeeers";
			}
		else
			{
			agg = "ul#aggbeeers";
			nonagg = "ul#beeers";
			}
					
			$(agg + ' li').hide();
			$(agg).empty();
			$.ajaxSetup({
				async: false
			});
			$.get('index_beerFrame.html', function(beerFrameTmpl) {

				$(agg).html(beerFrameTmpl);
				var jsonData = $.parseJSON(resp);				
		
				if(jsonData != null)
				{
					for (var c = 1; c < jsonData.beerArray.length; c++)
					{	
						$(agg).find('li.beeer_frame:first').clone(true).appendTo($('ul#beeers'));
					}
					if(jsonData.beerArray.length>0)
					{
						var beersFrames = $(agg).find('li.beeer_frame');
						beersFrames.each(function(i){
							$(this).attr("title",jsonData.beerArray[i].name); 
							if(jsonData.beerArray[i].num>1)
								{
								$(this).attr("aggregate","true");
								$(this).addClass("aggregate");
								}
							else
								{
								$(this).attr("aggregate","false");
								$(this).removeClass("aggregate");
								$(this).data('beerID',jsonData.beerArray[i].id);
								}
							$(this).data('beerID',jsonData.beerArray[i].id);
							$(this).data('beerName',jsonData.beerArray[i].name);
							
							
							
							
							if(jsonData.beerArray[i].photoURL == null || jsonData.beerArray[i].photoURL == "")
						    {
						    	$(this).find(".img_cont img").attr('src',"http://wallofbeeer.com/i/no_beeer.jpg");
					    	}else
					    	{
						    	$(this).find(".img_cont img").attr('src',jsonData.beerArray[i].photoURL);
					    	}
							if(jsonData.beerArray[i].name.length > 25)
								addClass = "tall2";
							else
								addClass = "";
							
							$(this).find(".lable_cont h3").html(jsonData.beerArray[i].name).parent().addClass(addClass);
							
							if(jsonData.beerArray[i].brewery.length > 25)
								addClass = "tall";
							else
								addClass = "";
							
							$(this).find(".lable_cont h4").html(jsonData.beerArray[i].brewery).parent().addClass(addClass);
							var stars = parseBeerRating(jsonData.beerArray[i].rating);
							$(this).find('ul.star_rating').html(stars);
							$(this).find('div.stars').html(Math.round(jsonData.beerArray[i].rating));
						});
						$(nonagg + ' li').hide();
						$(agg + ' li').show();
						$(nonagg).hide();
						$(agg).show();
					}
				}
		
			});
	}
	
function appendBeerList(resp)
	{
			//$('ul#beeers li').hide();
			//$('ul#beeers').empty();
			
			$.get('index_beerFrame.html', function(beerFrameTmpl) {

				//$('ul#beeers').html(beerFrameTmpl);
				var jsonData = $.parseJSON(resp);				
		
				if(jsonData != null)
				{
					for (var c = 0; c < jsonData.beerArray.length; c++)
					{	
						$('ul#aggbeeers').find('li.beeer_frame:first').removeClass("aggregate").attr("aggregate","false").clone(true).appendTo($('ul#aggbeeers'));
					}
					$('ul#aggbeeers').find('li.beeer_frame:first').addClass("aggregate").addClass("aggregateBack");
					$('ul#aggbeeers').find('li.beeer_frame:first').attr("aggregate","true");
					if(jsonData.beerArray.length>0)
					{
						var beersFrames = $('ul#aggbeeers').find('li.beeer_frame').not('li.beeer_frame:first');
						beersFrames.each(function(i){
							$(this).attr("title",jsonData.beerArray[i].name); 
							$(this).data('beerID',jsonData.beerArray[i].id);
							$(this).data('beerName',jsonData.beerArray[i].name);
							$(this).data('beerID',jsonData.beerArray[i].id);
							
							
							
							if(jsonData.beerArray[i].photoURL == null || jsonData.beerArray[i].photoURL == "")
						    {
						    	$(this).find(".img_cont img").attr('src',"http://wallofbeeer.com/i/no_beeer.jpg");
					    	}else
					    	{
						    	$(this).find(".img_cont img").attr('src',jsonData.beerArray[i].photoURL);
					    	}
							if(jsonData.beerArray[i].name.length > 28)
								addClass = "tall2";
							else
								addClass = "";
							
							$(this).find(".lable_cont h3").html(jsonData.beerArray[i].name).parent().addClass(addClass);
							
							if(jsonData.beerArray[i].brewery.length > 30)
								addClass = "tall";
							else
								addClass = "";
							
							$(this).find(".lable_cont h4").html(jsonData.beerArray[i].brewery).parent().addClass(addClass);
							var stars = parseBeerRating(jsonData.beerArray[i].rating);
							$(this).find('ul.star_rating').html(stars);
						});
						$('ul#aggbeeers li').show();
					}
				}
		
			});
	}

function pullRecentBeers(userID) 
    {
        $(".interactive_btn").css("backgroundPosition", "top left");
        $("#btn_recent").css("backgroundPosition", "bottom left");
		asyncService("services.php", "do=getBeersRecent&userID="+userID, function(resp){
			//alert("RESPONSE: "+resp);
			buildBeerList(resp)
		});
    }

function pullRatingBeers(userID) 
    {
        $(".interactive_btn").css("backgroundPosition", "top left");
        $("#btn_rating").css("backgroundPosition", "bottom left");

		asyncService("services.php", "do=getBeersRating&userID="+userID, function(resp){
			//alert("RESPONSE: "+resp);
			buildBeerList(resp)
		});
    }

function pullPopularBeers(userID) 
    {
        $(".interactive_btn").css("backgroundPosition", "top left");
        $("#btn_popular").css("backgroundPosition", "bottom left");

		asyncService("services.php", "do=getBeersPopular&userID="+userID, function(resp){
			//alert("RESPONSE: "+resp);
			buildBeerList(resp)
		});
    }

// pullSearchBeers(userID,$('#search_cont form input#search_input').val());

function pullSearchBeers(userID,beerName) 
    {
		asyncService("services.php", "do=getBeersSearch&userID="+userID+"&beerName="+beerName, function(resp){
			//alert("RESPONSE: "+resp);
			buildBeerList(resp)
		});
    }
    
    
function pullAggregateBeers(userID,beerName) 
    {
		asyncService("services.php", "do=getBeersSearchAgg&userID="+userID+"&beerName="+beerName, function(resp){
			//alert("RESPONSE: "+resp);
			buildBeerList(resp,1);
			
			asyncService("services.php", "do=getAllBeersSearch&userID="+userID+"&beerName="+beerName, function(resp){
				//alert("RESPONSE: "+resp);
				appendBeerList(resp)
			});		
			
		});
    }

function loadBeerDetailData(userID,beerName) 
	{
		asyncService("services.php", "do=getBeer&userID="+userID+"&beerName="+beerName, function(resp){
			//alert("RESPONSE: "+resp);
			parseBeerDetail(resp);
			});
	}

function loadBeerDetailDataAggSingle(userID,beerName) 
	{
		asyncService("services.php", "do=getBeersSearchAggSingle&userID="+userID+"&beerName="+beerName, function(resp){
			//alert("RESPONSE: "+resp);
			parseBeerDetail(resp);
			});
	}
	
function loadBeerDetailDataByID(userID,beerID) 
	{
		asyncService("services.php", "do=getBeerByID&userID="+userID+"&ID="+beerID, function(resp){
			//alert("RESPONSE: "+resp);
			parseBeerDetail(resp);
			});
	}

var asyncService = function(targetWebservice, parameters, callback)
{
    $.post(targetWebservice, parameters,
        function(response)
        {
            if(typeof callback == 'function')
            {
            	try
                  {
                    callback(eval(response));
                  }
                catch(err)
                  {
                    callback(response);
                  }

            }
        }
    );
}

function imgError(t)
	{
	$(t).attr('src',"http://wallofbeeer.com/i/no_beeer.jpg");
	}


