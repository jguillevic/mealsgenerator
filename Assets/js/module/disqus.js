var disqus_config = function () 
{
    this.page.url = window.location.href;
    var urlParams = new URLSearchParams(window.location.search);
    var slug = urlParams.get("slug");
    this.page.identifier = slug;
};

(function() 
{  // DON'T EDIT BELOW THIS LINE
    var d = document, s = d.createElement('script');
    
    s.src = 'https://devnmore.disqus.com/embed.js';
    
    s.setAttribute('data-timestamp', +new Date());
    (d.head || d.body).appendChild(s);
})();