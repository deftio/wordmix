# Wordmix

Wordmix is a utlity for generating new words from fragments.  Its can be useful for creating made-up words or brand names.

## Usage

To use wordmix run a php server and go to the directory with wordmix.php in it.

Add fragments as GET parameters like this:

Word Mixer allows mixing of word fragments to create new words. Bring your own words or word fragments by appending to the URL as shown here:

wordmix.php?
pre=my,prefixes
frags=these,can,appear,in,any,part,of,new,word
suf=only,at,end
words=full,words,to,appear,anywhere
sort=false   #note: default is sorted
cols=5
row=30

Note you must combine the above in the URL such as this:
wordmix.php?cols=5&sort=false&words=this,that,and,the,other


## Live site here:

[wordmix demo](https://deftio.com/wordmix/wordmix.php?pre=be,re,foo,wo,ra,mea,leo,for,wha,whi,lem,foo&frag=craft,data,fact,form,line,log,logic,method,mind,mod,path,&suf=end,tion,ed,er,os,ium,endium,at,ord,alt,ently,ordly,ortly,der,fer,wer,ter,raw,law,seo,asi,sio,nio,bio,bent,aft,wex,don,min,man,ium&words=__skip__&cols=5&sort=true&rows=40)
