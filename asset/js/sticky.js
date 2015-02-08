jQuery(document).ready(function ($)
{
    $('#header-image .entries.sticky').show();
    $('#header-image .entries.sticky > .post:gt(0)').hide();

    setInterval(function()
    {
        $('#header-image .entries.sticky > .post:first')
        .fadeOut(1000)
        .next()
        .fadeIn(1000)
        .end()
        .appendTo('#header-image .entries.sticky');
    }, 4000);
})