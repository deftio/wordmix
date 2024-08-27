<?php
	/*   
	Copyright (C) <2005-2011>  <M. A. Chatterjee>

	This software is provided 'as-is', without any express or implied
	warranty. In no event will the authors be held liable for any damages
	arising from the use of this software.

	Permission is granted to anyone to use this software for any purpose,
	including commercial applications, and to alter it and redistribute it
	freely, subject to the following restrictions:

	1. The origin of this software must not be misrepresented; you must not
	claim that you wrote the original software. If you use this software
	in a product, an acknowledgment in the product documentation would be
	appreciated but is not required.

	2. Altered source versions must be plainly marked as such, and must not be
	misrepresented as being the original software.

	3. This notice may not be removed or altered from any source
	distribution.

	*/

	// log site visits, assumes write access
	$addr  = $_SERVER["REMOTE_HOST"];
	$host  = $_SERVER["REMOTE_ADDR"];
	$local = $_SERVER["SERVER_ADDR"];
	if( ($_SERVER["SERVER_ADDR"] != "127.0.0.1") && ( $_SERVER["SERVER_PORT"] == "80" )) 
	{	
		$dns=gethostbyaddr($_SERVER["REMOTE_ADDR"]);
		$tm = strftime("%a %m/%d/%Y %H:%M:%S [$host,$addr,$dns]\n");
		$f=fopen('logbasea.txt','a');
		if( $f ) {
			fprintf($f,$tm);
			fclose($f);
		}
	}		
?>
<html> 
<script src="https://cdn.jsdelivr.net/npm/bitwrench@1.2.16/bitwrench.min.js"></script>


<!-- 

    @Title  FrabjousMix.html
    HTML/Javascript WordMix / maker script M A Chatterjee 2015 
  	@copy Copyright (C) <2015>  <M. A. Chatterjee>
    @author M A Chatterjee <deftio [at] deftio [dot] com>
  	@version 1.0 M. A. Chatterjee, 
  	
  	also on github:  https://github.com/deftio/frabjousmix
  
    This file builds words for fun.  Javascript / HTML / CSS required. should work
      in all browsers.  Mobile friendly if left to 4 columns
  
    @license: 
  	This software is provided 'as-is', without any express or implied
  	warranty. In no event will the authors be held liable for any damages
  	arising from the use of this software.
  
  	Permission is granted to anyone to use this software for any purpose,
  	including commercial applications, and to alter it and redistribute it
  	freely, subject to the following restrictions:
  
  	1. The origin of this software must not be misrepresented; you must not
  	claim that you wrote the original software. If you use this software
  	in a product, an acknowledgment in the product documentation would be
  	appreciated but is not required.
  
  	2. Altered source versions must be plainly marked as such, and must not be
  	misrepresented as being the original software.
  
  	3. This notice may not be removed or altered from any source
  	distribution.
-->
<!-- <script src="../frabjousmix.js" type="text/javascript"></script> <!-- js lib of word mix goodness -->
<head>
<link href='http://fonts.googleapis.com/css?family=Lato:300,400,600,700' rel='stylesheet' type='text/css'>


<style>
body {
    padding-top: 0.75rem;
    margin : 1% auto;
}
button {
    padding : 10px;
    border-radius: 5px;
    border:none;
    background-color: #3495eb;
    color: white;
}
</style>
<title>Word Mixer</title>
</head>
<body class="bw-font-sans-serif">
<h2>Word Mixer</h2>

<div id="output" style="width:70%; float:left"></div>
<div id="info"  style="width:25%; float:left"></div>
<button id='download' >Download Word List Only</button>&nbsp;&nbsp;
<button id='dl-all' >Download Words & Inputs</button><br><br>
<h3>Options</h3>
Word Mixer allows mixing of word fragments to create new words. Bring your own words or word fragments by appending to the URL as shown here:<br><br>
wordmix.php?<br>
pre=my,prefixes<br>
frags=these,can,appear,in,any,part,of,new,word<br>
suf=only,at,end<br>
words=full,words,to,appear,anywhere<br>
sort=false&nbsp;&nbsp;&nbsp;#note: default is sorted<br>
cols=5<br>row=30<br><br>
Note you must combine the above in the URL such as this:<br>
wordmix.php?cols=5&amp;sort=false&amp;words=this,that,and,the,other<br>
<script>
    // set a client side cookie.  Adapted from W3 Schools
    setCookie = function (cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; " + expires;
    }
     
    // get a client side cookie. 
    getCookie = function (cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
        }
        return "";
    }
    //read URL encoded parameters
    getURLParam = function (key,default_val) {
        params = {};
        if (location.search) {
            var parts = location.search.substring(1).split('&');
            for (var i = 0; i < parts.length; i++) {
                var nv = parts[i].split('=');
                if (!nv[0]) continue;
                params[nv[0]] = nv[1] || true;
            }
        }
         
        if (params.hasOwnProperty(key) == false)
            return default_val; // note if default_value is undefined then result is still undefined. :)
        return params[key];
    }
       
    uniq      = function (x)       {
        return x.filter (function (v, i, arr) { return (arr.indexOf (v) == i); }); 
    };
    
    saveData = function (data,fileName) {
            var a = document.createElement("a");
            document.body.appendChild(a);
            a.style = "display: none";
            {
                var json = JSON.stringify(data),
                blob = new Blob([json], {type: "octet/stream"}),
                url = window.URL.createObjectURL(blob);
                a.href = url;
                a.download = fileName;
                a.click();
                window.URL.revokeObjectURL(url);
            };
        };
        
    document.getElementById('download').onclick=function(){saveData(wList,"wordmix.json");}
    document.getElementById('dl-all').onclick=function(){saveData(
        {"about":"wordmix by deftio, deftio <at> deftio <dot> com","words":wList,"pre":pre,"suf":suf,"frag":frag,"words":words},"wordmix-all.json");}
    
    words=
    " adjustment admire agreeable ahead air alert ambitious amuck announce atom bar birth bite blood bold bolt bomb brake brass brave built buzz cast check cheer coach communicate complete cream cricket damaging describe dolls door drink dry eatable efficacious elegant fancy far fat felt fix fizz flew flex flip flow fold force forceful forge fuel fun goofy grade great grok grow hang helpful hissing hose innate jazz jibe joke jolly juju keen known languid learn level list lock low middle motion name new next obedient orange paint panicky physical pile place plug port powder power pump quantum quick quizzical recognise record road robin rub self sense shaky short show size spark spiritual square squirrel start strength stuff sugar taboo theory throw time toad transport try tug ultra uttermost vagabond value vast war weld wide wield wild wonderful wood works worm wrestle yell yield zealous zip";
    
    
    suf =" ace age ape art ed erre ian ice ile ime ine ing ing int ion ipe ire ite ium ly oob ope ope ore ourt sta tech th wab";
    
    
    pre = " femto giga im kilo mega mis pre re super un ultra"; 
    
    frag = "are plu gra de ma it bit fra fel dip ax ute ote ate ite elt alt ele ale ibe ";

    cols      = getURLParam('cols',4); 
    rows      = getURLParam('rows',50);
    hideInput = getURLParam('hide','true');
    words     = getURLParam('words',words);
    suf       = getURLParam('suf',suf);
    pre       = getURLParam('pre',pre);
    frag      = getURLParam('frag',frag);
    srt      = getURLParam('sort',"true")=="true";
    
    if (words == "__skip__")
        words = frag;
    
    rs = function(a){return a.filter(function(x){return x!="";})};
    // primitive tokenizers
    words = rs(words.split(/[;,\s]+/));//words.match(/\S+/g); 
    pre   = rs(pre.split(/[;,\s]+/));
    suf   = rs(suf.split(/[;,\s]+/));
    frag  = rs(frag.split(/[;,\s]+/));
    wl = words.length == frag.length ? 0 : words.length;

    document.getElementById("info").innerHTML=
        '<h3>Info and Stats</h3>'+ 
         wl+' words <br>' +
         pre.length  +' prefixes <br> ' +
         suf.length  +' suffixes <br>  ' +
         frag.length +' fragments <br> ' +
         (pre.length+frag.length+words.length)*(suf.length+frag.length+words.length) +' combinations <br>' +
             cols*rows+' shown <br><br><strong>Input List:</strong><br>'+ (words.concat(pre).concat(suf).concat(frag).sort()+"").replace(/,/g,' ')+'<br><br>';
    
    pre = pre.concat(words).concat(frag);
    suf = suf.concat(words).concat(frag);
    function pickRand(max) {return Math.round(Math.random()*max);}
    
    function getMixList(max) {        
        for (var s=[],i=0; i < max; i++)                       
            s.push( pre[pickRand(pre.length-1)]+suf[pickRand(suf.length-1)]);
        return s ;
    }
    wList = getMixList(rows*cols+50);
    wList = uniq(wList);
    wList = wList.slice(0,rows*cols);
    if (srt) {        
        wList.sort();
    }
    //make HTML columns
    h = "";
    for (i=0; i<cols; i++) {
        h += "<div style='float:left; width:"+99/cols+"%;'>" + '<h4>Col '+(i+1)+'</h4>';
        //h +=getMixList(rows)+"</div>"; //print out the word lists in columns
        h +=wList.slice(i*rows,(i+1)*rows).join("<br>")+"</div>"; //print out the word lists in columns
    }
    document.getElementById("output").innerHTML= h;
    
    //frabjousmix.getJSONFile('./data/wordfrags_en.js',function(w){console.log(w); words=w;} );
  
</script>
</body>
</html>


