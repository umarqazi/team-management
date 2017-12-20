$(window, document, undefined).ready(function() {

    /*$('input').blur(function() {
        var $this = $(this);
        if ($this.val())
            $this.addClass('used');
        else
            $this.removeClass('used');
    });*/

    $('.loginForm input#password').blur(function(){
        tmpval = $(this).val();
        if(tmpval == '') {
            $(this).addClass('empty');
            $(this).removeClass('not-empty');
        } else {
            $(this).addClass('not-empty');
            $(this).removeClass('empty');
        }
    });


    $('.loginForm input#email').blur(function(){
        tmpval = $(this).val();
        if(tmpval == '') {
            $(this).addClass('empty');
            $(this).removeClass('not-empty');
        } else {
            $(this).addClass('not-empty');
            $(this).removeClass('empty');
        }
    });


    var $ripples = $('.ripples');

    $ripples.on('click.Ripples', function(e) {

        var $this = $(this);
        var $offset = $this.parent().offset();
        var $circle = $this.find('.ripplesCircle');

        var x = e.pageX - $offset.left;
        var y = e.pageY - $offset.top;

        $circle.css({
            top: y + 'px',
            left: x + 'px'
        });

        $this.addClass('is-active');

    });

    $ripples.on('animationend webkitAnimationEnd mozAnimationEnd oanimationend MSAnimationEnd', function(e) {
        $(this).removeClass('is-active');
    });

});